<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Events\Login;

use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\UserLogs;
use App\Models\Property;
use App\Models\EmailVerifications;
use App\Models\ResetPasswordCodes;
use App\Models\SecondAuthCodes;

use App\Mail\SendPasswordChangedNotice;
use App\Mail\SendEmailVerificationNotice;
use App\Mail\SendNewAccountCreatedNotice;
use App\Mail\SendSecondAuthCodeNotice;
use App\Mail\SendEmailVerificationCodeNotice;
use App\Mail\SendForgotPasswordCodeNotice;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class AuthLoginController extends Controller
{

    public function login(Request $request){
        try{
            $ip=\Request::ip();
            $useragents=\Request::userAgent();
        
            // return response()->json([
            //     'status'=>500,
            //     'ip'=>$ip,
            //     'message'=>$useragents,
            // ]);

            $login = $request->input('email');
            $type = filter_var($login , FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            

            $validator='';
            if($type=='email'){
                $validator=Validator::make($request->all(),[
                    'email'=>'required|email',
                    'password'=>'required|min:8',
                ]);
            }
            else{
                $validator=Validator::make($request->all(),[
                    'email'=>'required|string',
                    'password'=>'required|min:8',
                ]);
            }


            if($validator->fails()){
                return response()->json([
                    'errors'=>$validator->messages(),
                ]);
            }
            else{
                if($type=='email'){
                    $user=User::Where('email',$request->email)->first();
                }
                else{
                    $user=User::Where('username',$request->email)->first();
                }

                if(!$user || ! Hash::check($request->password,$user->password)){
                    return response()->json([
                        'status'=>401,
                        'message'=>'These credentials do not match our records.',
                    ]);
                }
                else{

                    if($user->Status =='Reset'){
                        return response()->json([
                            'status'=>401,
                            'message'=>'Your Account has been Reset. Please Contact Support.',
                        ]);
                    }
                    if($user->Status =='Disabled'){
                        return response()->json([
                            'status'=>401,
                            'message'=>'Your Account has been De-Activated. Please Contact Support.',
                        ]);
                    }
                    if($user->Status =='New'){
                        if($cuureVe=EmailVerifications::where('email', $user->email)->get()->first()){
                            $cuureVe->delete();
                        }
            
                        $app_email_url = config('app.email_url');
                        
                        // $token= sha1($user->email);
                        $tokens = \Str::random(64);
                        // $url = $app_email_url.'email/verify/'.$user->id.'/'.$tokens;
                        $url = $app_email_url.'login';
                        $code = mt_rand(100000, 999999);
                        
                        $newVerification = new EmailVerifications;
                        $newVerification->email = $user->email;
                        $newVerification->token  = $tokens;
                        $newVerification->code  = $code;

                        $fullname=$user->Fullname;
        
                        if($newVerification->save()){
                            // Send email to user
                            Mail::to($user->email)->send(new SendEmailVerificationCodeNotice($url,$code,$fullname));
                        }
                        else{
                            $error="Could not Send Verification Code \n";
                            return response()->json([
                                'status'=>500,
                                'message'=>$error,
                            ]);
                        }

                        
                        $id=$user->id;
                        $savelog = new UserLogs;
                        $savelog->User =$id;
                        $savelog->Message ='User Requested Verification Code.';
                        $savelog->save();
                        
                        return response()->json([
                            'status'=>401,
                            'email'=>$user->email,
                            'message'=>'Your Account has not been Verified. New Verification Code has been sent to your Email.',
                        ]);

                        // return response()->json([
                        //     'status'=>401,
                        //     'message'=>'Your Account has not been Verified. Please Contact Support.',
                        // ]);
                    }

                    if (!$user->hasVerifiedEmail()) {
                        
                        if($cuureVe=EmailVerifications::where('email', $user->email)->get()->first()){
                            $cuureVe->delete();
                        }
            
                        $app_email_url = config('app.email_url');
                        
                        // $token= sha1($user->email);
                        $tokens = \Str::random(64);
                        // $url = $app_email_url.'email/verify/'.$user->id.'/'.$tokens;
                        $url = $app_email_url.'login';
                        $code = mt_rand(100000, 999999);
                        
                        $newVerification = new EmailVerifications;
                        $newVerification->email = $user->email;
                        $newVerification->token  = $tokens;
                        $newVerification->code  = $code;

                        $fullname=$user->Fullname;
        
                        if($newVerification->save()){
                            // Send email to user
                            Mail::to($user->email)->send(new SendEmailVerificationCodeNotice($url,$code,$fullname));
                        }
                        else{
                            $error="Could not Send Verification Code \n";
                            return response()->json([
                                'status'=>500,
                                'message'=>$error,
                            ]);
                        }

                        $id=$user->id;
                        $savelog = new UserLogs;
                        $savelog->User =$id;
                        $savelog->Message ='User Requested Verification Code.';
                        $savelog->save();

                        return response()->json([
                            'status'=>401,
                            'email'=>$user->email,
                            'message'=>'Your Account has not been Verified. New Verification Code has been sent to your Email.',
                        ]);
                    }
                    else{
                        $tokens = \Str::random(64);
                        // $url = $app_email_url.'email/verify/'.$user->id.'/'.$tokens;
                        $code = mt_rand(100000, 999999);

                        $all2facodes = SecondAuthCodes::where('email', $user->email)->get();
                        foreach ($all2facodes as $s2facode) {
                            $s2facode->delete();
                        }
                        
                        $newVerification = new SecondAuthCodes;
                        $newVerification->email = $user->email;
                        $newVerification->token  = $tokens;
                        $newVerification->code  = $code;

                        $fullname=$user->Fullname;
        
                        if($newVerification->save()){
                            // Send email to user
                            // Mail::to($user->email)->send(new SendSecondAuthCodeNotice($code,$fullname));

                            $id=$user->id;
                            $savelog = new UserLogs;
                            $savelog->User =$id;
                            $savelog->Message ='User Requested 2FA Code.';
                            $savelog->save();

                            return response()->json([
                                'status'=>401,
                                'email'=>$user->email,
                                'message'=>'New 2FA Code has been sent to your Email.',
                            ]);
                        }
                        else{
                            $error="Could not Send 2FA Code \n";
                            return response()->json([
                                'status'=>500,
                                'message'=>$error,
                            ]);
                        }


                        if ($user && $user->two_factor_verified==0) {
                            // $id=$user->id;
                            // $savelog = new UserLogs;
                            // $savelog->User =$id;
                            // $savelog->Message ='User Requested 2FA Code.';
                            // $savelog->save();

                        }
                        else{
                            // $user->last_login_at=$user->current_login_at ? $user->current_login_at :Carbon::now();
                            // $user->current_login_at = Carbon::now();
                            // $user->last_login_ip = request()->ip();
                            // $user->save();
                            // $token= $user->createToken($user->email.'_Token')->plainTextToken;
                            
                            // $id=$user->id;
                            // $savelog = new UserLogs;
                            // $savelog->User =$id;
                            // $savelog->Message ='User Logged in.';
                            // $savelog->save();
                            // return response()->json([
                            //     'status'=>200,
                            //     'username'=>$user->Username,
                            //     'userrole'=>$user->Userrole,
                            //     'token'=>$token,
                            //     'message'=>'Logged in',
                            // ]);
                        }
                    }

                    // $token= $user->createToken($user->email.'_Token')->plainTextToken;
                    // return response()->json([
                    //     'status'=>200,
                    //     'username'=>$user->Username,
                    //     'token'=>$token,
                    //     'message'=>'Logged in',
                    // ]);
                }

            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 

            $errors=$ex->getMessage();
            // 2002
            $beingusederror='No connection could be made because the target machine actively refused it';
            $tablenotfound='Base table or view not found: 1146 Table';
            $databasenotfound='Unknown database';
            $columnnotfound='Column not found: 1054 Unknown column';
            
            

            $error=$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Connection has been Lost. Please Contact Support\n".$errors;
            }
            if (preg_match("/$tablenotfound/i", $errors)) {
                $error="There is an issue from the server.\nPlease try again Later.\n";
            }
            if (preg_match("/$databasenotfound/i", $errors)) {
                $error="Fatal Error in the server. Support is working on it.\n";
            }
            if (preg_match("/$columnnotfound/i", $errors)) {
                $error="Something is Wrong. Support is working on it.\n";
            }

            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            \DB::rollback();
            $errors=$ex->getMessage();
            $error="Cannot Login.\n".$ex->getMessage();
            $noconnectionerror='Connection could not be established with host ';
                if (preg_match("/$noconnectionerror/i", $errors)) {
                    $error="Verification Code not Sent.\nFailed to Login.\nCheck Your Internet Connection.\n";
                }
            
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);

        }

    }

    public function verifyAccountCode(Request $request) {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'email'     => 'required|email|exists:users',
            'code'      =>'required:numeric|min:6|max:6|exists:email_verifications',
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $email = $request->input('email');
                $code = $request->input('code');
                
                $app_email_url = config('app.email_url');
                $url = $app_email_url.'login';
                // find the code
                $verifyAccountEmail = EmailVerifications::firstWhere('code', $code);

                if($verifyAccountEmail->email != $email){
                    return response()->json([
                        'status'=>500,
                        'message'=>"This Verification Code is not For your Account.",
                    ]);
                }
                // check if it does not expired: the time is one hour
                if ( now() > $verifyAccountEmail->created_at->addHour()) {
                    $verifyAccountEmail->delete();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Verification Code Expired.",
                    ]);
                }

                // find user's email 
                $user = User::firstWhere('email', $verifyAccountEmail->email);
                $user->last_login_at=$user->current_login_at ? $user->current_login_at :Carbon::now();
                $user->current_login_at = Carbon::now();
                $user->last_login_ip = request()->ip();
                $user->Status = 'Active';
                $fullname=$user->Fullname;
                $user->save();
                $token= $user->createToken($user->email.'_Token')->plainTextToken;
                
                if (!$user->hasVerifiedEmail()) {
                    if($user->save()){
                        // Send email to user
                        Mail::to($user->email)->send(new sendEmailVerificationNotice($url,$fullname));
                        if (!$user->hasVerifiedEmail()) {
                            $user->markEmailAsVerified();
                            $id=$user->id;
                            $savelog = new UserLogs;
                            $savelog->User =$id;
                            $savelog->Message ='User Verified.';
                            $savelog->save();
                        }
                        

                        $id=$user->id;
                        $savelog = new UserLogs;
                        $savelog->User =$id;
                        $savelog->Message ='User Logged in.';
                        $savelog->save();

                        $verifyAccountEmail->delete();
                        \DB::commit();
                        $success="Account Email Successfully Verified!.\n";
                        return response()->json([
                            'status'=>200,
                            'username'=>$user->Username,
                            'userrole'=>$user->Userrole,
                            'token'=>$token,
                            'message'=>'Account Email Successfully Verified!.',
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not Verify Account Email \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $id=$user->id;
                    $savelog = new UserLogs;
                    $savelog->User =$id;
                    $savelog->Message ='User Logged in.';
                    $savelog->save();

                    // Send email to user
                    Mail::to($user->email)->send(new sendEmailVerificationNotice($url,$fullname));
                        
                    $verifyAccountEmail->delete();
                    \DB::commit();
                    $success="Account Email Successfully Verified!.\n";
                    return response()->json([
                        'status'=>200,
                        'username'=>$user->Username,
                        'userrole'=>$user->Userrole,
                        'token'=>$token,
                        'message'=>'Account Email Successfully Verified!.',
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Code Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Change Password.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Password Changed Confirmation not Sent.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function verify2FACode(Request $request) {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'email'     => 'required|email|exists:users',
            'code'      =>'required:numeric|min:6|max:6|exists:second_auth_codes',
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $email = $request->input('email');
                $code = $request->input('code');
                // find the code
                $verifyAccountEmail = SecondAuthCodes::firstWhere('code', $code);

                if($verifyAccountEmail->email != $email){
                    return response()->json([
                        'status'=>500,
                        'message'=>"This 2FA Code is not For your Account.",
                    ]);
                }
                // check if it does not expired: the time is 5 minutes
                // $dateDiff = Carbon::now()->diffInMinutes($monthdate,false);
                // if ( now()->diffInMinutes($verifyAccountEmail->created_at) >5) {
                if ( now() > $verifyAccountEmail->created_at->addMinutes(5)) {
                    $verifyAccountEmail->delete();
                    return response()->json([
                        'status'=>500,
                        'message'=>"2FA Code Expired.",
                    ]);
                }

                // find user's email 
                $user = User::firstWhere('email', $verifyAccountEmail->email);
                $user->last_login_at=$user->current_login_at ? $user->current_login_at : now();
                $user->current_login_at = Carbon::now();
                $user->last_login_ip = request()->ip();
                $user->two_factor_verified = '1';
                $fullname=$user->Fullname;
                $user->save();
                $token= $user->createToken($user->email.'_Token')->plainTextToken;
                
                $id=$user->id;
                $savelog = new UserLogs;
                $savelog->User =$id;
                $savelog->Message ='User Verified Using 2FA.';
                $savelog->save();

                $id=$user->id;
                $savelog = new UserLogs;
                $savelog->User =$id;
                $savelog->Message ='User Logged in.';
                $savelog->save();

                // Send email to user
                // Mail::to($user->email)->send(new sendEmailVerificationNotice($url,$fullname));
                    
                $verifyAccountEmail->delete();
                \DB::commit();
                return response()->json([
                    'status'=>200,
                    'username'=>$user->Username,
                    'userrole'=>$user->Userrole,
                    'token'=>$token,
                    'message'=>'2FA successfully Verified!.',
                ]);
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Code Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Verify Code.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Code Confirmation not Sent.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function forgotPassword(Request $request) {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                // Delete all old code that user had sent before.
                $email = $request->input('email');
                ResetPasswordCodes::where('email', $email)->delete();

                 // Generate random code
                
                $code = mt_rand(100000, 999999);

                // Create a new code
                // $codeData = ResetCodePassword::create($data);

                $newCode = new ResetPasswordCodes;
                $newCode->email =$email;
                $newCode->code  =$code;

                $user = User::firstWhere('email', $email);
                $fullname=$user->Fullname;

                if($newCode->save()){
                    // Send email to user
                    Mail::to($email)->send(new SendForgotPasswordCodeNotice($code,$fullname));
                    \DB::commit();
                    $success="New Password Reset Code has been Sent to your email!.\n";
                    
                    return response()->json([
                        'status'=>200,
                        'email'=>$user->email,
                        'message'=>$success,
                    ]);
                }
                else{
                    \DB::rollback();
                    $error="Could not Send Code \n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Code Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Send reset Password Code.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Reset Code not Sent.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }


    public function verifyResetPassword(Request $request) {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'email'     => 'required|email|exists:users',
            'password'  =>'required|min:8',
            'code'      =>'required:numeric|min:6|max:6|exists:reset_password_codes',
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $email = $request->input('email');
                $code = $request->input('code');
                $password = $request->input('password');
                // find the code
                $passwordReset = ResetPasswordCodes::firstWhere('code', $code);

                if($passwordReset->email != $email){
                    return response()->json([
                        'status'=>500,
                        'message'=>"This Reset Code is not For your Account.",
                    ]);
                }
                // check if it does not expired: the time is one hour
                if ( now() > $passwordReset->created_at->addMinutes(5)) {
                    $passwordReset->delete();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Reset Code Expired.",
                    ]);
                }

                // find user's email 
                $user = User::firstWhere('email', $passwordReset->email);

                // update user password
                // $user->update($request->only('password'));
                $user->password =Hash::make($request->input('password'));
                $fullname=$user->Fullname;

                if($user->save()){
                   
                $user = User::firstWhere('email', $passwordReset->email);
                $user->last_login_at=$user->current_login_at ? $user->current_login_at :Carbon::now();
                $user->current_login_at = Carbon::now();
                $user->last_login_ip = request()->ip();
                $fullname=$user->Fullname;
                $user->save();
                $token= $user->createToken($user->email.'_Token')->plainTextToken;
                
                $id=$user->id;
                $savelog = new UserLogs;
                $savelog->User =$id;
                $savelog->Message ='User Password Changed.';
                $savelog->save();

                $id=$user->id;
                $savelog = new UserLogs;
                $savelog->User =$id;
                $savelog->Message ='User Logged in.';
                $savelog->save();

                // Send email to user
                
                Mail::to($email)->send(new SendPasswordChangedNotice($fullname));


                $passwordReset->delete();
                \DB::commit();
                return response()->json([
                    'status'=>200,
                    'username'=>$user->Username,
                    'userrole'=>$user->Userrole,
                    'token'=>$token,
                    'message'=>'Reset Code Verified!.',
                ]);


                }
                else{
                    \DB::rollback();
                    $error="Could not Change Account Password \n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Code Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Reset Password.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Password Reset Code Confirmation not Sent.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function logout(){
            Property::setUserLogs('User Logged out.');
            // $id=$event->user->id;
            // $savelog = new UserLogs;
            // $savelog->User =$id;
            // $savelog->Message ='User Logged out.';
            // $savelog->save();

            auth()->user()->tokens()->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Logged out',
            ]);
    }


    public function getRolesPermissions(){
            
            $user = auth()->user();
            // $permissionNames= $user->getPermissionNames; // collection of name strings
            $permissions= $user->permissions; // collection of permission objects
            // get all permissions for the user, either directly, or from roles, or from both
           
            // $permissions= $user->getDirectPermissions;
            // $permissions= $user->getPermissionsViaRoles;
            // $permissions= $user->getAllPermissions;
            // get the names of the user's roles
            // $roles= $user->getRoleNames; // Returns a collection
            $roles= $user->roles; // Returns a collection

            return response()->json([
                'status'=>200,
                // 'permissionNames' =>$permissionNames,
                'permissions' =>$permissions,
                // 'permissions' =>$permissions,
                'roles' => $roles,
                'message'=>'Retrived Permissions',
            ]);
    }

    public function getAllRolesPermissions(){
        $permissionsinfo= Permission::where('guard_name', 'web')->get(); // collection of permission objects

        $permissions= array();
        $sno=1;
        foreach ($permissionsinfo as $permission) {
            $permissionid=$permission->id;
            $thispermission=Permission::where('guard_name', 'web')->where('id',$permissionid)->get();
            
            
            $assignedroles=$permission->roles->count();
            
            $permissions[] = array(
                'sno'=>$sno,
                'id' => $permissionid,
                'name' => $permission->name,
                'roles' => $permission->roles,
                'assignedroles' => $assignedroles
            );
            
            $sno++;
        }

        
        $rolesinfo= Role::where('guard_name', 'web')->get();  // Returns a collection

        $roles= array();
        $sno1=1;
        foreach ($rolesinfo as $role) {
            $roleid=$role->id;
            // $thisrole=Role::findById($roleid);
            $rolepermissions=0;
            foreach ($permissionsinfo as $permission) {
                $permissionid=$permission->id;
                
                $rolesperm=$permission->roles;
                foreach ($rolesperm as $rol) {
                    if($roleid==$rol->id){
                        $rolepermissions++;
                    }
                }
            }

            $roleusers=0;

            if($users= User::with('roles')->get()){
                foreach ($users as $user) {
                    $userroles=$user->roles;
                    foreach ($userroles as $rolename) {
                        if($rolename->id==$roleid){
                            $roleusers++;
                        }
                    }
                }
                
            }


            
            // 'rolecount'=>$user->getRoleNames(),

            $roles[] = array(
                'sno1'=>$sno1,
                'id' => $roleid,
                'name' => $role->name,
                'rolepermissions' => $rolepermissions,
                'roleusers' => $roleusers
            );
            
            $sno1++;
        }

        return response()->json([
            'status'=>200,
            'permissions' =>$permissions,
            'roles' => $roles,
            'message'=>'Retrived Permissions $ Roles',
        ]);
    }

    public function getRolePermissions($id){
        $permissions= Permission::where('guard_name', 'web')->get(); // collection of permission objects
        
        // $role=Role::findById($id);
        // $currentpermission=Permission::findById(2);
        // $role->givePermissionTo($currentpermission);
        // $permission->assignRole($role);

        $rolepermissions= array();
        $sno=1;
        foreach ($permissions as $permission) {
            $permissionid=$permission->id;
            $thispermission=Permission::where('guard_name', 'web')->where('id',$permissionid)->get();
            
            $roles=$permission->roles;
            $hasRoles=false;
            foreach ($roles as $role) {
                if($id==$role->id){
                    $hasRoles=true;
                    break;
                }
            }
            
            $rolepermissions[] = array(
                'sno'=>$sno,
                'id' => $permissionid,
                'name' => $permission->name,
                'roles' => $permission->roles,
                'hasRoles' => $hasRoles
            );
            
            $sno++;
        }
        

        // $roles= Role::all();  // Returns a collection

        return response()->json([
            'status'=>200,
            'rolepermissions' =>$rolepermissions,
            'message'=>'Retrived Permissions for Role',
        ]);
    }

    public function getRoleUsers($id){
        $users= User::with('roles')->get(); // collection of permission objects
        
        $roleusers= array();
        $sno=1;
        foreach ($users as $user) {
            $userid=$user->id;
            // $thispermission=Permission::findById($userid);
            
            $roles=$user->roles;
            $hasRoles=false;
            foreach ($roles as $role) {
                if($id==$role->id){
                    $hasRoles=true;
                    break;
                }
            }
            
            $roleusers[] = array(
                'sno'=>$sno,
                'id' => $userid,
                'Fullname' => $user->Fullname,
                'Userrole' => $user->Userrole,
                'roles' => $user->roles,
                'hasRoles' => $hasRoles
            );
            
            $sno++;
        }
        

        // $roles= Role::all();  // Returns a collection

        return response()->json([
            'status'=>200,
            'roleusers' =>$roleusers,
            'message'=>'Retrived Users for Role',
        ]);
    }

    

    public function getPermissionRoles($id){
        $permissions= Permission::where('guard_name', 'web')->get(); // collection of permission objects
        
        $roles= Role::all();  // Returns a collection

        return response()->json([
            'status'=>200,
            'permissionroles' => $permissionroles,
            'message'=>'Retrived Roles for Permissions',
        ]);
    }




    

    public function changePassword(Request $request) {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'newpassword'  =>'required|min:8',
            'password'  =>'required|min:8',
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $user = auth()->user();
                $email = $user->email;
                $phone = '+254'.$user->Phone;
                $newpassword = $request->input('newpassword');
                $password = $request->input('password');
              
                
                if(!$user || ! Hash::check($password,$user->password)){
                    return response()->json([
                        'status'=>401,
                        'message'=>'Your Current Password Does not Match.',
                    ]);
                }

                
                // update user password
                $user->password =Hash::make($request->input('newpassword'));
                $fullname=$user->Fullname;

                if($user->save()){
                    Mail::to($email)->send(new SendPasswordChangedNotice($fullname));
                    \DB::commit();
                    $success="Account Password Successfully Changed!.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    \DB::rollback();
                    $error="Could not Change Account Password \n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Code Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Change Password.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Password Changed Confirmation not Sent.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function saveUser(Request $request){
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'email' => ['required', 'string', 'max:255'],
            'Fullname' => ['required', 'string', 'max:150'],
            'Username' => ['required', 'string', 'max:150'],   
            'Idno' => 'required:numeric|min:7|max:10',
            'Phone' => 'required:numeric|min:9|max:9',
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            try { 
                if($request->input('id') ==''){
                    if($request->input('Userrole') ==''){
                        $error="Please Select User Role";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                    $newuser = new User;
                    $newuser->email =$request->input('email');
                    $newuser->Fullname =$request->input('Fullname');
                    $newuser->Username =$request->input('Username');
                    $newuser->Phone =$request->input('Phone');
                    $newuser->Idno =$request->input('Idno');
                    $newuser->Userrole =$request->input('Userrole');
                    $newuser->status ='New';
                    $newuser->password =Hash::make($request->input('Idno'));
                    
                    if($newuser->save()){
                        $app_email_url = config('app.email_url');
                        $token = \Str::random(64);
                        $url = $app_email_url.'login';
                        // $url = $app_email_url.'email/setup/'.$newuser->id.'/'.$token;

                        $code = mt_rand(100000, 999999);
                        // $newVerification = new EmailVerifications;
                        // $newVerification->email = $newuser->email;
                        // $newVerification->token  = $token;
                        // $newVerification->code  = $code;
                        
                        $fullname=$request->input('Fullname');

                        // if($newVerification->save()){
                        Mail::to($newuser->email)->send(new SendNewAccountCreatedNotice($url,$code,$fullname));

                            // event(new Registered($newuser));
                        \DB::commit();
                        $success="New account has been created successfully!.\n Please check Your Email Address for More Instructions.";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                        // }
                        // else{
                        //     \DB::rollback();
                        //     $error="Could not Send Verification Code \n";
                        //     return response()->json([
                        //         'status'=>500,
                        //         'message'=>$error,
                        //     ]);
                        // }
                    }
                    else{
                        \DB::rollback();
                        $error="Could not create new User \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $newuser = User::findOrFail($request->input('id'));
                    $newuser->email =$request->input('email');
                    $newuser->Fullname =$request->input('Fullname');
                    $newuser->Username =$request->input('Username');
                    $newuser->Phone =$request->input('Phone');
                    $newuser->Idno =$request->input('Idno');

                    if($newuser->save()){
                        \DB::commit();
                        $success="Account has been updated successfully!.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not update a new User \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }

                }
                

                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="User With Email Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Create Account.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Notice not Sent.\nAccount Not Created.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function saveRole(Request $request){
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'Rolename' => ['required', 'string', 'max:150'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            try { 
                if($request->input('id') ==''){
                    if($request->input('Rolename') ==''){
                        $error="Please Input Role";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                    
                    // $role Role create 'name' 'writer'
                    // $permission = Permission::create(['name' => 'edit articles']);
                    $newrole = new Role;
                    $newrole->guard_name ='web';
                    $newrole->name =$request->input('Rolename');
                    
                    if($newrole->save()){
                       
                        \DB::commit();
                        $success="New Role has been created successfully!.";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not create New Role \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $newrole = Role::findOrFail($request->input('id'));
                    $newrole->guard_name ='web';
                    $newrole->name =$request->input('Rolename');

                    if($newrole->save()){
                        \DB::commit();
                        $success="Role has been updated successfully!.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not update Role \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }

                }
                

                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Role Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Create Role.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Notice not Sent.\Role Not Created.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function savePermission(Request $request){
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'Permissionname' => ['required', 'string', 'max:150'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            try { 
                if($request->input('id') ==''){
                    if($request->input('Permissionname') ==''){
                        $error="Please Input Permission";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                    
                    // $role= Role::create(['name' =>'writer']);
                    // $permission = Permission::create(['name' => 'edit articles']);
                    $newrole = new Permission;
                    $newrole->guard_name ='web';
                    $newrole->name =$request->input('Permissionname');
                    
                    if($newrole->save()){
                       
                        \DB::commit();
                        $success="New Permission has been created successfully!.";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not create New Permission \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $newrole = Permission::findOrFail($request->input('id'));
                    $newrole->guard_name ='web';
                    $newrole->name =$request->input('Permissionname');

                    if($newrole->save()){
                        \DB::commit();
                        $success="Permission has been updated successfully!.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not update Permission \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }

                }
                

                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error=$ex->getMessage();
                $duplicate='1062 ';
                
                $beingusederror='No connection could be made because the target machine actively refused it';
                $tablenotfound='Base table or view not found: 1146 Table';
                $databasenotfound='Unknown database';
                $columnnotfound='Column not found: 1054 Unknown column';
                
                
                if (preg_match("/$duplicate/i", $errors)) {
                    $error="Role Already exists.\n";
                }
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Connection has been Lost. Please Contact Support\n";
                }
                if (preg_match("/$tablenotfound/i", $errors)) {
                    $error="There is an issue from the server.\nPlease try again Later.\n";
                }
                if (preg_match("/$databasenotfound/i", $errors)) {
                    $error="Fatal Error in the server. Support is working on it.\n";
                }
                if (preg_match("/$columnnotfound/i", $errors)) {
                    $error="Something is Wrong. Support is working on it.\n";
                }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                $errors=$ex->getMessage();
                $error="Cannot Create Role.\n".$ex->getMessage();
                $noconnectionerror='Connection could not be established with host ';
                    if (preg_match("/$noconnectionerror/i", $errors)) {
                        $error="Notice not Sent.\Role Not Created.\nCheck Your Internet Connection.\n";
                    }
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);

            }
    
        }
    }

    public function deleteUser(Request $request)
    {
        $action= $request->input('action');
        try{
            $id=$request->input('id');
            
            if($event = User::find($id)){

                if(auth()->user()->id == $id){
                    $error="You Cannot ".$action." Current Logged User .\n";
                    return response()->json([
                        'status'=>401,
                        'message'=>$error,
                    ]);
                }

                if($action == 'Delete'){
                    $event->delete();
                    
                }
                else if($action == 'Reset'){
                    User::where('id',$id)->update(['status'=>'Reset']);
                }
                else if($action == 'Disable'){
                    User::where('id',$id)->update(['status'=>'Disabled']);
                }
                else if($action == 'Activate'){
                    User::where('id',$id)->update(['status'=>'Active']);
                }
                else{
                    $error="No Action Specified.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }


                $success="User ".$action."d.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="User is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="User Not ".$action."d.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="User Not ".$action."d .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="User Not ".$action."d.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function assignRoleToPermission(Request $request)
    {
        $action= $request->input('action');
        
        // $permission->assignRole($role);
        try{
            $id=$request->input('id');
            $permissionid=$request->input('permissionid');
            
            if($role = Role::findById($id)){
                if($permission = Permission::findById($permissionid)){
                    $role->givePermissionTo($permission);

                    $success="Role Given Permission.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Permission is Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            }
            else{
                $error="Role is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Role Not ".$action."d.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Role Not ".$action."d .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Role Not ".$action."d.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function assignPermissionToRole(Request $request)
    {
        $action= $request->input('action');
        
        try{
            $id=$request->input('id');
            $roleid=$request->input('roleid');
            
            if($permission = Permission::where('guard_name', 'web')->where('id',$id)->first()){
                if($role = Role::where('guard_name', 'web')->where('id',$roleid)->first()){
                    $permission->assignRole($role);

                    $success="Permission Assigned Role.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Role is Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            }
            else{
                $error="Permission is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Permission Not Assigned.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Permission Not Assigned .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Permission Not Assigned.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function assignUserToRole(Request $request)
    {
        $action= $request->input('action');
        
        try{
            $id=$request->input('id');
            $roleid=$request->input('roleid');
            
            if($user = User::find($id)){
                if($role = Role::where('guard_name', 'web')->where('id',$roleid)->first()){
                    $user->assignRole($role->name);

                    $success="User Assigned Role.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Role is Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            }
            else{
                $error="User is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="User Not Assigned.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="User Not Assigned .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="User Not Assigned.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function removePermissionFromRole(Request $request)
    {
        
        try{
            $id=$request->input('id');
            $roleid=$request->input('roleid');

            
            // $permission->removeRole($role);
            
            if($permission = Permission::where('guard_name', 'web')->where('id',$id)->first()){
                if($role = Role::where('guard_name', 'web')->where('id',$roleid)->first()){
                    $role->revokePermissionTo($permission);

                    $success="Permission Removed from Role.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Role is Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            }
            else{
                $error="Permission is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Permission Not Removed.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Permission Not Removed .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Permission Not Removed.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function removeUserFromRole(Request $request)
    {
        
        try{
            $id=$request->input('id');
            $roleid=$request->input('roleid');

            
            // $permission->removeRole($role);
            
            if($user = User::where('id',$id)->first()){
                if($role = Role::where('guard_name', 'web')->where('id',$roleid)->first()){
                    $user->removeRole($role);
                    // $role->revokePermissionTo($permission);

                    $success="User Removed from Role.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Role is Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            }
            else{
                $error="User is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="User Not Removed.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="User Not Removed .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="User Not Removed.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    

    public function deleteRole(Request $request)
    {
        $action= $request->input('action');
        try{
            $id=$request->input('id');
            
            if($event = Role::where('guard_name', 'web')->where('id',$id)){
                // ->where('guard_name', 'web')
                DB::delete('DELETE FROM roles WHERE id = ?',[$id]);


                $success="Role ".$action."d.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="Role is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Role Not ".$action."d.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Role Not ".$action."d .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Role Not ".$action."d.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function deletePermission(Request $request)
    {
        try{
            $id=$request->input('id');
            $permission = Permission::where('guard_name', 'web')->where('id',$id);
            if($permission){
                DB::delete('DELETE FROM permissions WHERE id = ?',[$id]);
               
                $success="Permission Deleted.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="Permission is Not Found.\n";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Permission Not Deleted.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Permission Not Deleted .Is Linked Somewhere.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Permission Not Deleted.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    
}
