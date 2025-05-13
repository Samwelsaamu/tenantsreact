<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Property;
use App\Models\Agency;
use App\Models\User;
use App\Models\Report;
use App\Models\UserLogs;
use App\Models\Mails;
use AfricasTalking\SDK\AfricasTalking;
use Webklex\IMAP\Facades\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        return redirect('dashboard');
    }

        public function dashboard()
    {
        
        Property::setUserLogs('Viewing dashboard');
        $propertyinfo = Property::all();
        return view('dashboard',compact('propertyinfo'));
    }

    public function create()
    {
        $propertyinfo = Property::all();
        $usersinfo = User::all();
        Property::setUserLogs('Openning New User Template');
        return view('newuser',compact('propertyinfo','usersinfo'));
    }

    public function store(Request $request)
    {
        
        Property::setUserLogs('Saving New User');
        $storeData = $request->validate([
            'Fullname' => ['required', 'string', 'max:255'],
            'Username' => ['required', 'string', 'max:255','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'Phone' => ['required', 'integer', 'min:9'],
            'Idno' => ['required', 'integer', 'min:8',],
            'Userrole' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]); 
         try { 
            $saveuser = new User;
            $saveuser->Fullname =$request->input('Fullname');
            $saveuser->Username =$request->input('Username');
            $saveuser->Phone =$request->input('Phone');
            $saveuser->Idno =$request->input('Idno');
            $saveuser->email =$request->input('email');
            $saveuser->Userrole =$request->input('Userrole');
            $saveuser->password =Hash::make($request->input('password'));
            $saveuser->save();
            Property::setUserLogs('New User '.$request->input('Fullname').' Added');
                return redirect("/homeusers/create")->with('success', 'User Saved Successfully!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                Property::setUserLogs('Error Saving New User '.$request->input('Fullname').'::'.$ex->getMessage());
                return redirect("/homeusers/create")->with('dbError', $ex->getMessage());
            }
    }

    public function profile(){
        
        Property::setUserLogs('Openning User Profile Template');
        $propertyinfo = Property::all();
        return view('profile',compact('propertyinfo'));
    }

    public function changepassword(){
        
        Property::setUserLogs('Openning User Profile Change Password Template');
        $propertyinfo = Property::all();
        return view('changepassword',compact('propertyinfo'));
    }

    public function users(){
        
        Property::setUserLogs('Openning Users Template');
        $propertyinfo = Property::all();
        $usersinfo = User::all();
        return view('users',compact('propertyinfo','usersinfo'));
    }

    public function userprofile($id){
        
        Property::setUserLogs('Openning Users Profile for '.Property::getProfilename($id));
        $propertyinfo = Property::all();
        $usersinfo = User::where('id',$id)->get();
        $logusersinfo = UserLogs::where('User',$id)->get()->sortbydesc('id');
        $logsystemusersinfo = UserLogs::where('User','System')->get()->sortbydesc('id');
        $loginusersinfo=DB::table('user_logs')->where([
            'Message'=>'Log In',
            'User'=>$id
        ])->get()->sortbydesc('id');
        return view('userprofile',compact('propertyinfo','usersinfo','logusersinfo','loginusersinfo','logsystemusersinfo'));
    }
    

    public function getappdata(){
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        // Initialize the SDK
        $AT          = new AfricasTalking($username, $apiKey);
        // Get the application service
        $application = $AT->application();
        try {
            // Fetch the application data
            $data = $application->fetchApplicationData();
            $enjson=json_encode($data);
            $characters = json_decode($enjson,true);
            return $characters["data"]["UserData"]["balance"];
            // print_r($data);
        } 
        catch(\GuzzleHttp\Exception\ConnectException $e) {
            $error=$e->getMessage();
            Property::setUserLogs('Error Getting Airtime Bal::'.$error);
            $networkerror='cURL error 6:';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                return "Net Error";
            }
        }
    }
    

    public function update(Request $request, $id)
    {
        
        try { 
           $updateData = $request->validate([
                'Fullname' => ['required', 'string', 'max:255'],
                'Phone' => ['required', 'integer', 'min:9'],
                'Idno' => ['required', 'integer', 'min:8',],
            ]);
            User::whereId($id)->update($updateData);
            $username=Property::getUsername($id);
            Property::setUserLogs('Updated User ::'.$username);
            return redirect('/profile')->with('success', 'Profile Information updated!');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            Property::setUserLogs('Error Updating User :'.$username.' '. $ex->getMessage());
            return redirect('/profile')->with('dbError', $ex->getMessage());
        }
    }


    public function destroy($id)
    {
        $users = User::findOrFail($id);
        $users->delete();
        $username=Property::getUsername($id);
        Property::setUserLogs('Deleted User ::'.$username);
        return redirect('/users')->with('success', 'User has been deleted');
    }


    public function getMails(){
        $oClient = Client::account('default');
        Property::setUserLogs('Viewing All Mails');
        $propertyinfo = Property::all();
        $aMessages="";
        try { 
            $oClient->connect();
            //Get all Mailboxes
            /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
            $aFolder = $oClient->getFolders();
            //Loop through every Mailbox
            /** @var \Webklex\IMAP\Folder $oFolder */
            foreach($aFolder as $oFolder){
                //Get all Messages of the current Mailbox $oFolder
                /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */

                $aMessage = $oFolder->messages()->all()->get();

                foreach($aMessage as $oMessage){
                    $uid=$oFolder->name.''.$oMessage->getUid();
                    $status=Property::getMailIDSaved($uid);
                    if ($status==0) {
                        $subject=$oMessage->getSubject();
                        $from=$oMessage->getFrom()[0]->mail;
                        $attachments=$oMessage->getAttachments()->count() > 0 ? 'yes' : 'no';
                        $msg='';
                        if($oMessage->getTextBody()){
                            $msg=$oMessage->getTextBody();
                        }
                        else{
                            $msg=$oMessage->getHTMLBody();
                        }
                        // $message=str_replace("\r\n", '<br>', $msg);
                        // echo $message.'<br><br><br><br>';
                        // elseif($oMessage->hasHTMLBody()){
                        //     $message=$oMessage->getHTMLBody();
                        // }
                        
                        // $thread = $oMessage->thread($sent_folder = null);
                        $saveemail = new Mails;
                        $saveemail->mailid =$uid;
                        $saveemail->subject =$subject;
                        $saveemail->message =$msg;
                        $saveemail->from =$from;
                        $saveemail->attachments =$attachments;
                        $saveemail->thread ='None';
                        $saveemail->folder=$oFolder->name;
                        $saveemail->save();
                    }
                }
                
                
            }
            // dd($oMessage); 
            $aMessages = Mails::where('status','New')->get();
            $msgerror='';
            return view('mail.allmails',compact('propertyinfo','aMessages','msgerror'));
        } catch(\Webklex\PHPIMAP\Exceptions\ConnectionFailedException $ex){ 
              // dd($ex->getMessage()); 
              $msgerror=$ex->getMessage();
              if($msgerror=="connection failed"){
                $aMessages = Mails::where('status','New')->get();
                $msgerror='Cound Not Refresh Mails';
              }
              return view('mail.allmails',compact('propertyinfo','aMessages','msgerror'));
            }
    }
}
