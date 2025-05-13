<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Property;
use App\Models\Agency;
use App\Models\User;
use App\Models\House;
use App\Models\Agreement;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Water;
use App\Models\PaymentsOthers;
use App\Models\WaterOthers;
use App\Models\WaterMessage;
use App\Models\Message;
use App\Models\WaterMessagesOthers;
use App\Models\PaymentDate;
use App\Models\PaymentMessage;
use App\Models\Report;

use AfricasTalking\SDK\AfricasTalking;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         try {
            $hid=$request->input('hid');
            $tid=$request->input('tid');
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $KPLC=$request->input('KPLC');
            $HseDeposit=$request->input('HseDeposit');
            $Water=$request->input('Water');
            $Lease=$request->input('Lease');
            $DateAssigned=$request->input('DateAssigned');
            $Nature=$request->input('Nature');
            $deposits=$HseDeposit+$Water+$KPLC;
            $month=date_format(date_create($DateAssigned),'Y n');
            $pid=$this->getPropertyId($hid);
            $tenantphone=$this->getTenantPhone($tid);
            $uid=$pid.' '.$hid.' '.$tid;
            $agreement = new Agreement;
            $agreement->Plot=$pid;
            $agreement->House=$hid;
            $agreement->Tenant=$tid;
            $agreement->DateAssigned=$DateAssigned;
            $agreement->DateTo=$DateAssigned;
            $agreement->Deposit=$deposits;
            $agreement->Phone=$tenantphone;
            $agreement->UniqueID=$uid;
            $agreement->save();
            // save bills information
            $payments = new Payment;
            $payments->Plot=$pid;
            $payments->House=$hid;
            $payments->Tenant=$tid;
            $payments->Month=$month;
            $payments->save();
            $paymentid=$payments->id;
            if ($Nature=="New") {
               $this->updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease);
            }
            else{
                $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
            }
            $this->updateTenant($tid,$hid);
                return redirect("/properties/Agreement/Tenant/{$tid}")->with('success', 'Tenant Assigned to House!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                return redirect("/properties/Agreement/Tenant/{$tid}")->with('dbError', $ex->getMessage());
            }
    }


    public function savetenantanotherhse(Request $request)
    {
        
       
         try {
            $hid=$request->input('hid');
            $tid=$request->input('tid');
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $KPLC=$request->input('KPLC');
            $HseDeposit=$request->input('HseDeposit');
            $Water=$request->input('Water');
            $Lease=$request->input('Lease');
            $DateAssigned=$request->input('DateAssigned');
            $Nature=$request->input('Nature');
            $deposits=$HseDeposit+$Water+$KPLC;
            $month=date_format(date_create($DateAssigned),'Y n');
            $pid=$this->getPropertyId($hid);
            $tenantphone=$this->getTenantPhone($tid);
            $uid=$pid.' '.$hid.' '.$tid;
            $agreement = new Agreement;
            $agreement->Plot=$pid;
            $agreement->House=$hid;
            $agreement->Tenant=$tid;
            $agreement->DateAssigned=$DateAssigned;
            $agreement->DateTo=$DateAssigned;
            $agreement->Deposit=$deposits;
            $agreement->Phone=$tenantphone;
            $agreement->UniqueID=$uid;
            $agreement->save();
            // 
            $payments = new Payment;
            $payments->Plot=$pid;
            $payments->House=$hid;
            $payments->Tenant=$tid;
            $payments->Month=$month;
            $payments->save();
            $paymentid=$payments->id;
            if ($Nature=="New") {
               $this->updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease);
            }
            else{
                $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
            }
                return redirect("/properties/Agreement/Tenant/{$tid}")->with('success', 'Tenant Assigned to House!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                return redirect("/properties/Agreement/Tenant/{$tid}")->with('dbError', $ex->getMessage());
            }
    }

        public function savetenanttransfer(Request $request)
    {
        
       
         try {
            $hid=$request->input('hid');
            $tid=$request->input('tid');
            $transferid=$request->input('transferid');
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $KPLC=$request->input('KPLC');
            $HseDeposit=$request->input('HseDeposit');
            $Water=$request->input('Water');
            $Lease=$request->input('Lease');
            $DateAssigned=$request->input('DateAssigned');
            $Nature=$request->input('Nature');
            $deposits=$HseDeposit+$Water+$KPLC;
            $month=date_format(date_create($DateAssigned),'Y n');
            $pid=$this->getPropertyId($hid);
            $tenantphone=$this->getTenantPhone($transferid);
            $uid=$pid.' '.$hid.' '.$tid;
            $newuid=$pid.' '.$hid.' '.$transferid;
            $tenantassignedhse=$this->tenantHousesAssigned($tid);
            $transferedfromagreementid=$this->getAgreementId($uid);
            $agreement = new Agreement;
            $agreement->Plot=$pid;
            $agreement->House=$hid;
            $agreement->Tenant=$transferid;
            $agreement->DateAssigned=$DateAssigned;
            $agreement->DateTo=$DateAssigned;
            $agreement->Deposit=$deposits;
            $agreement->Phone=$tenantphone;
            $agreement->UniqueID=$newuid;
            $agreement->save();
            // 
            $payments = new Payment;
            $payments->Plot=$pid;
            $payments->House=$hid;
            $payments->Tenant=$transferid;
            $payments->Month=$month;
            $payments->save();
            $paymentid=$payments->id;
            $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
            $this->updateTenantAgreement($transferedfromagreementid,$DateAssigned);
            $this->updateTenantTransfer($tid,$transferid,$tenantassignedhse);
                return redirect("/properties/Agreement/Tenant/{$transferid}")->with('success', 'Tenant Transefferd to House!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/Agreement/Tenant/{$transferid}")->with('dbError', $ex->getMessage());
            }
    }

    public function vacatetenants(Request $request)
    {
        
        $hid=$request->input('hid');
        $tid=$request->input('tid');
        $aid=$request->input('aid');
         try {
            $Deposit=$request->input('Deposit');
            $Refund=$request->input('Refund');
            $Arrears=$request->input('Arrears');
            $Damages=$request->input('Damages');
            $DateVacated=$request->input('DateVacated');
            $Transaction=$request->input('Transaction');

            $month=date_format(date_create($DateVacated),'Y n');
            $tenantassignedhse=$this->tenantHousesAssigned($tid);

            $agreement = Agreement::findOrFail($aid);
            $agreement->DateVacated=$DateVacated;
            $agreement->Deposit=$Deposit;
            $agreement->Refund=$Refund;
            $agreement->Month=$month;
            $agreement->Arrears=$Arrears;
            $agreement->DateTo=$DateVacated;
            $agreement->Transaction=$Transaction;
            $agreement->save();
            // 
          
            $this->updateTenantVacate($tid,$hid,$tenantassignedhse);
                return redirect("/properties/Agreement/Tenant/{$tid}")->with('success', 'Tenant Vacated From House!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/Agreement/Tenant/{$tid}")->with('dbError', $ex->getMessage());
            }
    }

    public function reassigntenant(Request $request)
    {
        
            $hid=$request->input('hid');//new
            $tid=$request->input('tid');
            $id=$request->input('id');//old
         try {
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $KPLC=$request->input('KPLC');
            $HseDeposit=$request->input('HseDeposit');
            $Water=$request->input('Water');
            $Lease=$request->input('Lease');
            $DateAssigned=$request->input('DateAssigned');
            $Excess=$request->input('Excess');
            $Arrears=$request->input('Arrears');

            $deposits=$HseDeposit+$Water+$KPLC;
            $month=date_format(date_create($DateAssigned),'Y n');

            $pid=$this->getPropertyId($id);
            $tenantphone=$this->getTenantPhone($tid);
            $uid=$pid.' '.$id.' '.$tid;
            $newuid=$pid.' '.$hid.' '.$tid;
            $aid=$this->getAgreementId($uid);

            $balance=$this->TenantBalance($tid,$id);
            if ($balance>0) {
                $Arrears=$Arrears+$balance;
            }
            else{
                $Excess=$Excess+abs($balance);
            }

            $agreement = new Agreement;
            $agreement->Plot=$pid;
            $agreement->House=$hid;
            $agreement->Tenant=$tid;
            $agreement->DateAssigned=$DateAssigned;
            $agreement->DateTo=$DateAssigned;
            $agreement->Deposit=$deposits;
            $agreement->Phone=$tenantphone;
            $agreement->UniqueID=$newuid;
            $agreement->save();

            $updateagreement = Agreement::findOrFail($aid);
            $updateagreement->DateVacated=$DateAssigned;
            $updateagreement->Month=$month;
            $updateagreement->DateTo=$DateAssigned;
            $updateagreement->save();
            // 
            $payments = new Payment;
            $payments->Plot=$pid;
            $payments->House=$hid;
            $payments->Tenant=$tid;
            $payments->Month=$month;
            $payments->save();
            $paymentid=$payments->id;

            $this->updatePaymentsReassign($paymentid,$Rent,$Garbage,$Arrears,$Excess);
            $this->updateTenantReassign($tid,$hid,$id);
                return redirect("/properties/Agreement/Tenant/{$tid}")->with('success', 'Tenant Reassigned to House!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/Agreement/Tenant/{$tid}")->with('dbError', $ex->getMessage());
            }
    }

    public function sendsinglewater(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";

        $id=$request->input('id');
        $pid=$request->input('pid');
        $resend=$request->input('resend');
        $phone='+254'.$request->input('phone');
        $month=$request->input('month');
        $message=$request->input('message');
        $mode="Single Water";
        $redit="/properties/send/messages/{$pid}/{$mode}/{$month}";
        if ($resend=="Resend") {
                $redit="/properties/view/messages/waterbill/{$pid}/{$month}";
            }
        try {
             // Initialize the SDK
        $AT          = new AfricasTalking($username, $apiKey);
        // Get the application service
        $sms        = $AT->sms();

            $result = $sms->send([
                'to'      => $phone,
                'message' => $message,
                'from'    => $username
            ]);
            
            
            // print_r($result);
            $enjson=json_encode($result);
            $characters = json_decode($enjson,true);
            $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
               if(sizeof($recipients)>0){
                  $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                  $sentmgs= substr($messagesent, 8);  
                  $totalmgs= substr($messagesent, 10);  
                  if ($sentmgs==0) {
                    return redirect($redit)->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
                  }
                  else{
                  foreach ($recipients as $number) {
                    $watermessage = new WaterMessage;
                    $watermessage->House=$id;
                    $watermessage->To=$number["number"];
                    $watermessage->Cost=$number["cost"];
                    $watermessage->MessageId=$number["messageId"];
                    $watermessage->Message=$message;
                    $watermessage->Month=$month;
                    if ($watermessage->save()){
                        return redirect($redit)->with('success', "Message Sent To :".$number['number']." and Message is:".$message);
                    }
                    else{
                        return redirect($redit)->with('error', "Message Not Sent To :".$number['number']." and Message is:".$message);
                    }
                  }
                }
              
             }else{
                return redirect($redit)->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
             }

        } catch(\GuzzleHttp\Exception\ConnectException $e) {
            $error=$e->getMessage();
            $networkerror='cURL error 6:';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                return redirect($redit)->with('dbError','Network Error');
            }   
        }
        catch(\Illuminate\Database\QueryException $ex){ 
          return redirect($redit)->with('dbError', $ex->getMessage());
        }
    }

    public function sendotherssinglewater(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";

        $id=$request->input('id');
        $phone='+254'.$request->input('phone');
        $month=$request->input('month');
        $message=$request->input('message');
        $mode="Other Water";
        $redit="/properties/send/message/Other/{$mode}/{$month}";
        try {
             // Initialize the SDK
        $AT          = new AfricasTalking($username, $apiKey);
        // Get the application service
        $sms        = $AT->sms();

            $result = $sms->send([
                'to'      => $phone,
                'message' => $message,
                'from'    => $username
            ]);
            
            
            // print_r($result);
            $enjson=json_encode($result);
            $characters = json_decode($enjson,true);
            $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
               if(sizeof($recipients)>0){
                  $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                  $sentmgs= substr($messagesent, 8);  
                  $totalmgs= substr($messagesent, 10);  
                  if ($sentmgs==0) {
                    return redirect($redit)->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
                  }
                  else{
                  foreach ($recipients as $number) {
                    $watermessage = new WaterMessagesOthers;
                    $watermessage->Tenant=$id;
                    $watermessage->To=$number["number"];
                    $watermessage->Cost=$number["cost"];
                    $watermessage->MessageId=$number["messageId"];
                    $watermessage->Message=$message;
                    $watermessage->Month=$month;
                    $watermessage->Status='Water';
                    if ($watermessage->save()){
                        return redirect($redit)->with('success', "Message Sent To :".$number['number']." and Message is:".$message);
                    }
                    else{
                        return redirect($redit)->with('error', "Message Not Sent To :".$number['number']." and Message is:".$message);
                    }
                  }
                }
              
             }else{
                return redirect($redit)->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
             }

        } catch(\GuzzleHttp\Exception\ConnectException $e) {
            $error=$e->getMessage();
            $networkerror='cURL error 6:';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                return redirect($redit)->with('dbError','Network Error');
            }   
        }
        catch(\Illuminate\Database\QueryException $ex){ 
          return redirect($redit)->with('dbError', $ex->getMessage());
        }
    }

    public function sendothersnotification(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";

        $id=$request->input('id');
        $phone='+254'.$request->input('phone');
        $month=$request->input('month');
        $wid=$request->input('wid');
        $message=$request->input('message');
        $mode="Other Notification";
        $redit="/properties/send/message/Other/{$mode}/{$month}";
        try {
             // Initialize the SDK
        $AT          = new AfricasTalking($username, $apiKey);
        // Get the application service
        $sms        = $AT->sms();

            $result = $sms->send([
                'to'      => $phone,
                'message' => $message,
                'from'    => $username
            ]);
            
            
            // print_r($result);
            $enjson=json_encode($result);
            $characters = json_decode($enjson,true);
            $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
               if(sizeof($recipients)>0){
                  $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                  $sentmgs= substr($messagesent, 8);  
                  $totalmgs= substr($messagesent, 10);  
                  if ($sentmgs==0) {
                    return redirect($redit)->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
                  }
                  else{
                  foreach ($recipients as $number) {
                    $watermessage = new WaterMessagesOthers;
                    $watermessage->Tenant=$id;
                    $watermessage->To=$number["number"];
                    $watermessage->Cost=$number["cost"];
                    $watermessage->MessageId=$number["messageId"];
                    $watermessage->Message=$message;
                    $watermessage->Month=$month;
                    $watermessage->Status='Notification';
                    if ($watermessage->save()){
                        $payments = PaymentsOthers::findOrFail($wid);
                        $payments->Status='Sent';
                        $payments->save();
                        return redirect($redit)->with('success', "Message Sent To :".$number['number']." and Message is:".$message);
                    }
                    else{
                        return redirect($redit)->with('error', "Message Not Sent To :".$number['number']." and Message is:".$message);
                    }
                  }
                }
              
             }else{
                return redirect($redit)->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
             }

        } catch(\GuzzleHttp\Exception\ConnectException $e) {
            $error=$e->getMessage();
            $networkerror='cURL error 6:';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                return redirect($redit)->with('dbError','Network Error');
            }   
        }
        catch(\Illuminate\Database\QueryException $ex){ 
          return redirect($redit)->with('dbError', $ex->getMessage());
        }
    }

    public function sendallwater(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        $pid=$request->input('pid');
        $month=$request->input('month');
        $waterchoosen=$request->input('waterchoosen');
        $mode="All Water";

        $allmessages=implode(",", $waterchoosen);
        $eachmessage=explode(",", $allmessages);
        $msgerror="";
        foreach ($eachmessage as $eachmsg) {
            $mms=explode("/", $eachmsg);
            $phone='+254'.$mms[0];
            $id=$mms[1];
            $message=$mms[2];
            try {
                // Initialize the SDK
                $AT      = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms     = $AT->sms();

                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $username
                    ]);
                    // print_r($result);
                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                       if(sizeof($recipients)>0){
                          $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                          $sentmgs= substr($messagesent, 8);  
                          $totalmgs= substr($messagesent, 10);  
                          if ($sentmgs==0) {
                            $msgerror="Not Sent";
                          }
                          else{
                          foreach ($recipients as $number) {
                            $watermessage = new WaterMessage;
                            $watermessage->House=$id;
                            $watermessage->To=$number["number"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->Month=$month;
                            if ($watermessage->save()){
                                
                            }
                            else{
                                $msgerror="Not Sent";
                            }
                          }
                        }
                      
                     }else{
                        $msgerror="Not Sent";
                     }

                } catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        $msgerror="Network Error";
                        break;
                    }   
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $msgerror=$ex->getMessage();
                    break;
                }
        }
        if ($msgerror=="") {
            return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('success', 'Messages Sent');
        }
        elseif($msgerror=="Not Sent"){
            return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('error', 'Some Messages Not Sent');
        }
        else{
            return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('dbError',$msgerror);
        }
        // print_r($waterchoosen);
    }

    public function sendchoosetenant(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        $pid=$request->input('pid');
        $month=$request->input('month');
        $phone=$request->input('waterchoosen');
        $message=$request->input('Message');
        $Patchno=date('Y'.'m'.'d'.'H'.'i');
        $mode="Choose Tenant";
        
        $msgerror="";
            try {
                // Initialize the SDK
                $AT      = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms     = $AT->sms();

                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $username
                    ]);
                    // print_r($result);
                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                       if(sizeof($recipients)>0){
                          $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                          $sentmgs= substr($messagesent, 8);  
                          $totalmgs= substr($messagesent, 10);  
                          if ($sentmgs==0) {
                            $msgerror="Not Sent";
                          }
                          else{
                          foreach ($recipients as $number) {
                            echo $number["number"];
                            $watermessage = new Message;
                            $watermessage->To=$number["number"];
                            $watermessage->Status=$characters["status"];
                            $watermessage->Code=$number["statusCode"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->PatchNo=$Patchno;
                            if ($watermessage->save()){
                                
                            }
                            else{
                                $msgerror="Not Sent";
                            }
                          }
                        }
                      
                     }else{
                        $msgerror="Not Sent";
                     }

                } catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        $msgerror="Network Error";
                        
                    }   
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $msgerror=$ex->getMessage();
                    
                }
        if ($msgerror=="") {
            return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('success', 'Messages Sent');
        }
        elseif($msgerror=="Not Sent"){
            return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('error', 'Some Messages Not Sent');
        }
        else{
            return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('dbError',$msgerror);
        }
        // print_r($waterchoosen);
    }

    // completedpayments
    public function sendcompletedpayments(Request $request)
    {
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        $pid=$request->input('savepaymentpid');
        $month=$request->input('savepaymentmonth');
        $waterchoosen=$request->input('waterchoosen');
        $mode="Completed Payment";
        // dd($waterchoosen);
        $allmessages=implode(",", $waterchoosen);
        $eachmessage=explode(",", $allmessages);
        // dd($eachmessage);
        $msgerror="";
        foreach ($eachmessage as $eachmsg) {
            $mms=explode("/", $eachmsg);
            $phone='+254'.$mms[0];
            $tid=$mms[1];
            $hid=$mms[2];
            $message=$mms[3];
            try {
                // Initialize the SDK
                $AT      = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms     = $AT->sms();

                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $username
                    ]);
                    // print_r($result);
                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                       if(sizeof($recipients)>0){
                          $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                          $sentmgs= substr($messagesent, 8);  
                          $totalmgs= substr($messagesent, 10);  
                          if ($sentmgs==0) {
                            $msgerror="Not Sent";
                          }
                          else{
                          foreach ($recipients as $number) {
                            $watermessage = new PaymentMessage;
                            $watermessage->Plot=$pid;
                            $watermessage->Tenant=$tid;
                            $watermessage->House=$hid;
                            $watermessage->To=$number["number"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->Month=$month;
                            $watermessage->msgtype='Completed';
                            if ($watermessage->save()){
                                
                            }
                            else{
                                $msgerror="Not Sent";
                            }
                          }
                        }
                      
                     }else{
                        $msgerror="Not Sent";
                     }

                } catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        $msgerror="Network Error";
                        break;
                    }   
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $msgerror=$ex->getMessage();
                    break;
                }
                catch(\Exception $ex){ 
                    $msgerror=$ex->getMessage();
                    break;
                    // return compact('error');
                }
        }
        if ($msgerror=="") {
            $curmonth=$this->getMonthDate($month);
            $success='<span>Summary Payments Message sent for '.$curmonth.' is Ready.</br>';
            return compact('success');
            // return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('success', 'Messages Sent');
        }
        elseif($msgerror=="Not Sent"){
            $error="One or More Messages Could not be Sent.\n 
            Please Ensure there is enough Credit then select and Send Again";
            return compact('error');
            // return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('error', 'Some Messages Not Sent');
        }
        else{
            $error="Not Sent.\n".$msgerror;
            return compact('error');
            // return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('dbError',$msgerror);
        }

        // if ($msgerror=="") {
        //     return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('success', 'Messages Sent');
        // }
        // elseif($msgerror=="Not Sent"){
        //     return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('error', 'Some Messages Not Sent');
        // }
        // else{
        //     return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('dbError',$msgerror);
        // }
        // print_r($waterchoosen);
    }
    // sendsummarypayments
    public function sendsummarypayments(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $PatchNo    = Property::PatchNo();

        $pid=$request->input('savepaymentpid');
        $month=$request->input('savepaymentmonth');
        $waterchoosen=$request->input('waterchoosen');
        $mode="Summary Paid";
        // dd($waterchoosen);
        $allmessages=implode(",", $waterchoosen);
        $eachmessage=explode(",", $allmessages);
        // dd($eachmessage);
        $msgerror="";
        foreach ($eachmessage as $eachmsg) {
            $mms=explode("/", $eachmsg);
            $phone='+254'.$mms[0];
            $tid=$mms[1];
            $hid=$mms[2];
            $message=$mms[3];
            try {
                // Initialize the SDK
                $AT      = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms     = $AT->sms();

                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $username
                    ]);
                    // print_r($result);
                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                       if(sizeof($recipients)>0){
                          $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                          $sentmgs= substr($messagesent, 8);  
                          $totalmgs= substr($messagesent, 10);  
                          if ($sentmgs==0) {
                            $msgerror="Not Sent";
                          }
                          else{
                            foreach ($recipients as $number) {
                                $watermessage = new PaymentMessage;
                                $watermessage->Plot=$pid;
                                $watermessage->Tenant=$tid;
                                $watermessage->House=$hid;
                                $watermessage->To=$number["number"];
                                $watermessage->Cost=$number["cost"];
                                $watermessage->MessageId=$number["messageId"];
                                $watermessage->Message=$message;
                                $watermessage->Month=$month;
                                $watermessage->PatchNo=$PatchNo;
                                $watermessage->msgtype='Summary';
                                if ($watermessage->save()){
                                    
                                }
                                else{
                                    $msgerror="Not Sent";
                                }
                            }
                            }
                      
                     }else{
                        $msgerror="Not Sent";
                     }

                } catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        $msgerror="Network Error";
                        break;
                    }   
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $msgerror=$ex->getMessage();
                    break;
                }
                catch(\Exception $ex){ 
                    $msgerror=$ex->getMessage();
                    break;
                    // return compact('error');
                }
        }
        if ($msgerror=="") {
            $curmonth=$this->getMonthDate($month);
            $success='<span>Summary Payments Message sent for '.$curmonth.' is Ready.</br>';
            return compact('success');
            // return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('success', 'Messages Sent');
        }
        elseif($msgerror=="Not Sent"){
            $error="One or More Messages Could not be Sent.\n 
            Please Ensure there is enough Credit then select and Send Again";
            return compact('error');
            // return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('error', 'Some Messages Not Sent');
        }
        else{
            $error="Not Sent.\n".$msgerror;
            return compact('error');
            // return redirect("/properties/send/messages/{$pid}/{$mode}/{$month}")->with('dbError',$msgerror);
        }
        // print_r($waterchoosen);
    }

    public function savewaterbillnew(Request $request)
    {
        
            $hid=$request->input('hid');
            $pid=$request->input('pid');
            $Tenant=$request->input('Tenant');
            $month=$request->input('month');
            if ($Tenant=="") {
                return redirect("/properties/add/waterbill/{$pid}/{$month}/{$hid}")->with('error', 'No Tenant Selected!');
            }
         try {
            $Previous=$request->input('Previous');
            $Current=$request->input('Current');
            $Cost=$request->input('Cost');
            $Units=$request->input('Units');
            $Total=$request->input('Total');
            $Total_OS=$request->input('Total_OS');
            $totalwater=$Total+$Total_OS;
            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= $this->getNextMonthdate($month);
            $nextmonth= $this->getNextMonth($month,$monthdate);
            $housename=Property::getHouseName($hid);
            $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
            if(!$paymentid=$this->getPaymentId($hid,$Tenant,$nextmonth)){
                $paymentsnew = new Payment;
                $paymentsnew->Plot=$pid;
                $paymentsnew->House=$hid;
                $paymentsnew->Tenant=$Tenant;
                $paymentsnew->Month=$nextmonth;
                $paymentsnew->Waterbill=$totalwater;
                $paymentsnew->save();
            }
            else{
                $payments = Payment::findOrFail($paymentid);
                $payments->Waterbill=$totalwater;
                $payments->save();
            }

            $water = new Water;
            $water->Plot=$pid;
            $water->House=$hid;
            $water->Tenant=$Tenant;
            $water->DateTrans=$DateTrans;
            $water->Month=$month;
            $water->Cost=$Cost;
            $water->Units=$Units;
            $water->Previous=$Previous;
            $water->Current=$Current;
            $water->Total=$Total;
            $water->Total_OS=$Total_OS;
            $water->Description=$Description;  
            $water->save();
                return redirect("/properties/add/waterbill/{$pid}/{$month}/{$hid}")->with('success', 'Waterbill Recorded!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/add/waterbill/{$pid}/{$month}/{$hid}")->with('dbError', $ex->getMessage());
            }
    }

    public function updatewaterbill(Request $request)
    {
        
            $hid=$request->input('hid');
            $pid=$request->input('pid');
            $Tenant=$request->input('Tenant');
            $month=$request->input('month');
            $waterid=$request->input('waterid');
            if ($Tenant=="") {
                return redirect("/properties/add/waterbill/{$pid}/{$month}/{$hid}")->with('error', 'No Tenant Selected!');
            }
         try {
            $Previous=$request->input('Previous');
            $Current=$request->input('Current');
            $Cost=$request->input('Cost');
            $Units=$request->input('Units');
            $Total=$request->input('Total');
            $Total_OS=$request->input('Total_OS');
            $totalwater=$Total+$Total_OS;
            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= $this->getNextMonthdate($month);
            $nextmonth= $this->getNextMonth($month,$monthdate);
            $housename=Property::getHouseName($hid);
            $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
            if(!$paymentid=$this->getPaymentId($hid,$Tenant,$nextmonth)){
                $paymentsnew = new Payment;
                $paymentsnew->Plot=$pid;
                $paymentsnew->House=$hid;
                $paymentsnew->Tenant=$Tenant;
                $paymentsnew->Month=$nextmonth;
                $paymentsnew->Waterbill=$totalwater;
                $paymentsnew->save();
            }
            else{
                $payments = Payment::findOrFail($paymentid);
                $payments->Waterbill=$totalwater;
                $payments->save();
            }

            $water = Water::findOrFail($waterid);
            $water->Plot=$pid;
            $water->House=$hid;
            $water->Tenant=$Tenant;
            $water->DateTrans=$DateTrans;
            $water->Month=$month;
            $water->Cost=$Cost;
            $water->Units=$Units;
            $water->Previous=$Previous;
            $water->Current=$Current;
            $water->Total=$Total;
            $water->Total_OS=$Total_OS;
            $water->Description=$Description;  
            $water->save();
                return redirect("/properties/add/waterbill/{$pid}/{$month}/{$hid}")->with('success', 'Waterbill Recorded!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/add/waterbill/{$pid}/{$month}/{$hid}")->with('dbError', $ex->getMessage());
            }
    }

    public function uploadwaterbillpreview(Request $request)
    {
        
            $pid=$request->input('pid');
            $month=$request->input('monthss');
            $pcode=$request->input('pcode');
            $propertyinfo = Property::all();
            $thisproperty=Property::findOrFail($pid);
            $thismode='';
            $watermonth=$month;
            $monthinfo= '' ;
            $thishouse='';
            $houseinfo=House::orderBy('id')->where('Plot',$pid)->get();
            $waterbill='';
            try { 
                $fileName = $request->waterbillfile->getClientOriginalName();
                $request->waterbillfile->move(public_path('uploads'), $fileName);
               
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify("uploads/".$fileName);
                /**  Create a new Reader of the type that has been identified  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $objPHPExcel = $reader->load("uploads/".$fileName);
                $water_data= array();
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow=$worksheet->getHighestRow();
                $highestColumn=$worksheet->getHighestColumn();
                    if ($highestColumn=="G") {
                        for($row=1;$row<=$highestRow;$row++){
                            $rowfound='';
                            $hse=$worksheet->getCellByColumnAndRow(1,$row)->getValue();
                            $tent=$worksheet->getCellByColumnAndRow(2,$row)->getValue();
                            $prev=$worksheet->getCellByColumnAndRow(3,$row)->getValue();
                            $curr=$worksheet->getCellByColumnAndRow(4,$row)->getValue();
                            $cost=$worksheet->getCellByColumnAndRow(5,$row)->getValue();
                            $uni=$curr-$prev;
                            $total=$uni*$cost;
                            $housess=explode('-', $hse);
                            $spacecounthouses= count($housess);
                            if ($spacecounthouses==2) {
                                $hse=$housess[1];
                            }
                            $hssss = explode(' ', $hse);
                            $spacecount= count($hssss);
                            if ($spacecount==2) {
                                $hsss1=$hssss[0];
                                $hsss2=$hssss[1];
                                $hse=$hsss1.$hsss2;
                            }
                            $housename=$pcode.'-'.$hse;
                            $id=Property::getHouseCode($housename);
                            $tenant=Property::checkCurrentTenant($id);
                            $waterid=Property::checkCurrentTenantBill($id,$tenant,$month);
                            $tenantname='';
                            if ($tenant=='') {
                                $tenant='Vacated';
                                $tenantname='House Vacant';
                            }
                            else{
                                $tenantname=Property::checkCurrentTenantName($tenant);
                            }

                             $water_data[] = array(
                                    'pid' => $pid,
                                    'id' => $id,
                                    'tid' => $tenant,
                                    'previous' => $prev,
                                    'current' => $curr,
                                    'cost' => $cost,
                                    'units' => $uni,
                                    'total' => $total,
                                    'housename'=>$housename,
                                    'tenantname' => $tenantname,
                                    'waterid' => $waterid,
                                    'month' => $month
                          );
                        }
                    }
                    else{
                        return redirect("/properties/upload/waterbill/{$pid}/{$month}")->with('error', "Water Bill Upload Format. Please Make it 7 Columns As Below:\nA:Housename, B:Tenant Name, C:Previous, D:Current, E:Cost, F:Units, G:Amount/Total");
                    }
                }
                $output=$water_data;
                // dd(json_encode($water_data));
                return view('uploadwaterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','waterbill'));
        
            }
             catch(\Illuminate\Database\QueryException $ex){ 
                   return redirect("/properties/upload/waterbill/{$pid}/{$month}")->with('error', $ex->getMessage());
            }
    }

    // public function updatewaterbillpreview(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect('login');
    //     }
    //         $pid=$request->input('pid');
    //         $month=$request->input('month');
    //         $pcode=$request->input('pcode');
    //         $propertyinfo = Property::all();
    //         $thisproperty=Property::findOrFail($pid);
    //         $thismode='';
    //         $watermonth=$month;
    //         $monthinfo= '' ;
    //         $thishouse='';
    //         $houseinfo=House::orderBy('id')->where('Plot',$pid)->get();

    //         try { 
    //             $file = $request->file('file');
    //             $fileName = $file->getClientOriginalName();
    //             $file->move(public_path('uploads'), $fileName);
               
    //             $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify("uploads/".$fileName);
    //             /**  Create a new Reader of the type that has been identified  **/
    //             $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    //             /**  Load $inputFileName to a Spreadsheet Object  **/
    //             $objPHPExcel = $reader->load("uploads/".$fileName);
    //             $water_data= array();
    //             foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    //             $highestRow=$worksheet->getHighestRow();
    //             $highestColumn=$worksheet->getHighestColumn();
    //                 if ($highestColumn=="G") {
    //                     for($row=1;$row<=$highestRow;$row++){
    //                         $rowfound='';
    //                         $hse=$worksheet->getCellByColumnAndRow(1,$row)->getValue();
    //                         $tent=$worksheet->getCellByColumnAndRow(2,$row)->getValue();
    //                         $prev=$worksheet->getCellByColumnAndRow(3,$row)->getValue();
    //                         $curr=$worksheet->getCellByColumnAndRow(4,$row)->getValue();
    //                         $cost=$worksheet->getCellByColumnAndRow(5,$row)->getValue();
    //                         $uni=$curr-$prev;
    //                         $total=$uni*$cost;
    //                         $housess=explode('-', $hse);
    //                         $spacecounthouses= count($housess);
    //                         if ($spacecounthouses==2) {
    //                             $hse=$housess[1];
    //                         }
    //                         $hssss = explode(' ', $hse);
    //                         $spacecount= count($hssss);
    //                         if ($spacecount==2) {
    //                             $hsss1=$hssss[0];
    //                             $hsss2=$hssss[1];
    //                             $hse=$hsss1.$hsss2;
    //                         }
    //                         $housename=$pcode.'-'.$hse;
    //                         $id=Property::getHouseCode($housename);
    //                         $tenant=Property::checkCurrentTenant($id);
    //                         $waterid=Property::checkCurrentTenantBill($id,$tenant,$month);
    //                         $tenantname='';
    //                         if ($tenant=='') {
    //                             $tenant='Vacated';
    //                             $tenantname='House Vacant';
    //                         }
    //                         else{
    //                             $tenantname=Property::checkCurrentTenantName($tenant);
    //                         }

    //                          $water_data[] = array(
    //                                 'pid' => $pid,
    //                                 'id' => $id,
    //                                 'tid' => $tenant,
    //                                 'previous' => $prev,
    //                                 'current' => $curr,
    //                                 'cost' => $cost,
    //                                 'units' => $uni,
    //                                 'total' => $total,
    //                                 'housename'=>$housename,
    //                                 'tenantname' => $tenantname,
    //                                 'waterid' => $waterid,
    //                                 'month' => $month
    //                       );
    //                     }
    //                 }
    //                 else{
    //                     return redirect("/properties/update/waterbill/{$pid}/{$month}")->with('error', "Water Bill Upload Format. Please Make it 7 Columns As Below:\nA:Housename, B:Tenant Name, C:Previous, D:Current, E:Cost, F:Units, G:Amount/Total");
    //                 }
    //             }
    //             $output=$water_data;
    //             // dd(json_encode($water_data));
    //             return view('updatewaterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output'));
        
    //         }
    //          catch(\Illuminate\Database\QueryException $ex){ 
    //                return redirect("/properties/update/waterbill/{$pid}/{$month}")->with('error', $ex->getMessage());
    //         }
    // }


    public function uploaddocuments(Request $request)
    {
        
        try { 
            $id=Auth::user()->id;
            $path='public/documents';
             
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            // $file->move(public_path('documents'), $fileName);

            $pathname=$file->storeAs($path,$fileName);
            if(Property::checkDocumentIfFound($fileName)){
                $error =  "File Already Saved.\n Please Delete and Upload Again";
                return compact('error');
            }
            else{
                Property::saveReport('Documents',$fileName,$id);
                $reports =Report::orderByDesc('id')->where('Type','Documents')->get();
                $success="File ".$fileName." Uploaded Successfully";
                return compact('success','reports');
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error =  $ex->getMessage();
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error =  $ex->getMessage();
            return compact('error');
        }
    }



    public function updatewaterbillpreview(Request $request)
    {
        
        $pid=$request->input('pid');
        $month=$request->input('month');
        $pcode=$request->input('pcode');
        // dd($pid,$month,$pcode);
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($pid);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse='';
        $houseinfo=House::orderBy('id')->where('Plot',$pid)->get();

        try { 
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify("uploads/".$fileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $objPHPExcel = $reader->load("uploads/".$fileName);
            $water_data= array();
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow=$worksheet->getHighestRow();
                $highestColumn=$worksheet->getHighestColumn();
                // $error=$highestColumn;
                //             return compact('error');
                if ($highestColumn=="G") {
                    for($row=1;$row<=$highestRow;$row++){
                        $rowfound='';
                        $hse=$worksheet->getCellByColumnAndRow(1,$row)->getValue();
                        $tent=$worksheet->getCellByColumnAndRow(2,$row)->getValue();
                        $prev=$worksheet->getCellByColumnAndRow(3,$row)->getValue();
                        $curr=$worksheet->getCellByColumnAndRow(4,$row)->getValue();
                        $cost=$worksheet->getCellByColumnAndRow(5,$row)->getValue();
                        $housename1=$hse;
                        $uni=$curr-$prev;
                        $total=$uni*$cost;
                        $housess=explode('-', $hse);
                        $spacecounthouses= count($housess);
                        if ($spacecounthouses==2) {
                            $hse=$housess[1];
                        }
                        $hssss = explode(' ', $hse);
                        $spacecount= count($hssss);
                        if ($spacecount==2) {
                            $hsss1=$hssss[0];
                            $hsss2=$hssss[1];
                            $hse=$hsss1.$hsss2;
                        }
                        $housename=$pcode.'-'.$hse;

                        $id1=Property::getHouseCode($housename);

                        $id2=Property::getHouseCode($housename1);

                        if($id1){
                            $id=$id1;
                            $housenames=$housename;
                        }
                        elseif($id2){
                            $id=$id2;
                            $housenames=$housename1;
                        }
                        else{
                            $id='';
                            $housenames=$housename1;
                        }

                        $tenant=Property::checkCurrentTenant($id);
                        $waterid=Property::checkCurrentTenantBill($id,$tenant,$month);

                        $monthdate= Property::getLastMonthdate($month);
                        $lastmonth= Property::getLastMonth($month,$monthdate);

                        $saved_previous=Property::checkCurrentTenantPreviousBill($id,$tenant,$month);
                        $last_current=Property::checkCurrentTenantCurrentBill($id,$tenant,$lastmonth);
                        $saved_current=Property::checkCurrentTenantCurrentBill($id,$tenant,$month);
                        $loading_bill=$prev.':'.$curr;
                        $saved_bill=$saved_previous.':'.$saved_current;
                        $saved=($loading_bill==$saved_bill)?'Saved':'No';
                        $prevmatches=($prev==$last_current)?'Ok':'No';
                        $tenantname='';
                        if ($tenant=='') {
                            $tenant='Vacated';
                            $tenantname='House Vacant';
                        }
                        else{
                            $tenantname=Property::checkCurrentTenantName($tenant);
                        }
                        $uploadedPlot=Property::getHousePlotUploaded($housenames);
                        if($uploadedPlot!=$pid && $row==1){
                            $error="The Uploaded Data is not for The Selected Property.\n Please re Upload the Valid data:\n";
                            return compact('error');
                        }
                        // if($id==''){
                        //     $error="The Uploaded Data is not for The Selected Property.\n Please re Upload the Valid data:\n";
                        //     return compact('error');
                        // }
                        if($id==''){

                        }
                        else{
                            $water_data[] = array(
                                'pid' => $pid,
                                'id' => $id,
                                'tid' => $tenant,
                                'previous' => ($prev!='')?$prev:'',
                                'current' => ($curr!='')?$curr:'',
                                'saved_previous' => ($saved_previous!='')?$saved_previous:'',
                                'saved_current' => ($saved_current!='')?$saved_current:'',
                                'saved' =>$saved,
                                'prevmatches' =>$prevmatches,
                                'cost' => ($cost!='')?$cost:'',
                                'units' => $uni,
                                'total' => $total,
                                'housename'=>$housenames,
                                'tenantname' => $tenantname,
                                'waterid' => $waterid,
                                'month' => $month
                            );
                        }
                    }
                }
                else{
                    $error="Error in Water Bill Upload Format.\n Please Make it 7 Columns As Below:\nA:Housename, B:Tenant Name, C:Previous, D:Current, E:Cost, F:Units, G:Amount/Total";
                    return compact('error');
                    // return redirect("/properties/update/waterbill/{$pid}/{$month}")->with('error', "Water Bill Upload Format. Please Make it 7 Columns As Below:\nA:Housename, B:Tenant Name, C:Previous, D:Current, E:Cost, F:Units, G:Amount/Total");
                }
            }
            $curmonth=$this->getMonthDate($month);
            $success='<span>Preview for '.$curmonth.' is Ready.</br>Please Select Houses to Update or Save</span></br>
                    <span class="" title="Waterbill per House Uploaded">
                        Waterbill Saved: <b >'.$this->getTotalBillsHse($pid,$month).'/'.$this->getTotalHousesHse($pid).'</b> </span></br>
                    <span class="" title="Waterbill Messages Sent">
                        Messages Sent: <b >'.$this->getTotalBillsMsgHse($pid,$month).'/'.$this->getTotalHousesHse($pid).'</b> </span>';
            $output=$water_data;
            // dd(json_encode($water_data));
            return compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','success');
            // return view('updatewaterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output'));
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return compact('error');
            // return redirect("/properties/update/waterbill/{$pid}/{$month}")->with('error', $ex->getMessage());
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return compact('error');
            // return redirect("/properties/update/waterbill/{$pid}/{$month}")->with('error', $ex->getMessage());
        }
    }

    // updatebillspreview
    public function updatebillspreview(Request $request)
    {
        
        $pid=$request->input('pid');
        $month=$request->input('month');
        $pcode=$request->input('pcode');
        // dd($pid,$month,$pcode);
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($pid);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse='';
        $houseinfo=House::orderBy('id')->where('Plot',$pid)->get();
        
        try { 
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify("uploads/".$fileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $objPHPExcel = $reader->load("uploads/".$fileName);
            $water_data= array();
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow=$worksheet->getHighestRow();
                $highestColumn=$worksheet->getHighestColumn();
                if ($highestColumn=="L") {
                        // $error=$highestRow.' '.$highestColumn;
                        // return compact('error');
                    for($row=3;$row<=$highestRow;$row++){
                        $rowfound='';
                        $hse=$worksheet->getCellByColumnAndRow(1,$row)->getValue();
                        $tent=$worksheet->getCellByColumnAndRow(2,$row)->getValue();
                        $excess=$worksheet->getCellByColumnAndRow(3,$row)->getValue();
                        $arrears=$worksheet->getCellByColumnAndRow(4,$row)->getValue();
                        $rent=$worksheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();
                        $water=$worksheet->getCellByColumnAndRow(6,$row)->getValue();
                        $garbage=$worksheet->getCellByColumnAndRow(7,$row)->getValue();
                        $others=$worksheet->getCellByColumnAndRow(8,$row)->getValue();
                        $total=$worksheet->getCellByColumnAndRow(9,$row)->getCalculatedValue();
                        $payment=$worksheet->getCellByColumnAndRow(10,$row)->getCalculatedValue();
                        $balance=$worksheet->getCellByColumnAndRow(11,$row)->getValue();
                        $due=$worksheet->getCellByColumnAndRow(12,$row)->getValue();

                        $excess=($excess)?$excess:0.00;
                        $arrears=($arrears)?$arrears:0.00;
                        $rent=($rent)?$rent:0.00;
                        $water=($water)?$water:0.00;
                        $garbage=($garbage)?$garbage:0.00;
                        $others=($others)?$others:0.00;
                        $total=($total)?$total:0.00;
                        $payment=($payment)?$payment:0.00;
                        $balance=($balance)?$balance:0.00;

                        
                        $housename=$hse;
                        $id=Property::getHouseCode($housename);
                        $tenant=Property::checkCurrentTenant($id);

                        // $error=$id.'I '.$tenant.'TT '.$hse.'H '.$tent.'T '.$excess.'E '.$arrears.'A '.$rent.'R '.$water.'W '.$garbage.'G '.$others.'O '.$total.'T '.$payment.'P '.$balance.'B '.$due.'D ';
                        // return compact('error');

                        $monthdate= Property::getLastMonthdate($month);
                        $lastmonth= Property::getLastMonth($month,$monthdate);
                        $uploadedPlot=Property::getHousePlotUploaded($housename);
                        if($uploadedPlot!=$pid && $row==3){
                            $error="The Uploaded Data is not for The Selected Property.\n Please re Upload the Valid data:\n";
                            return compact('error');
                        }

                        if($id==''){
                            $TotalUsed=($rent+$garbage+$others+$water+$arrears)-$excess;
                            $TotalPaid=$payment;
                            $Balance=$TotalUsed-$TotalPaid;
                            $water_data[] = array(
                                'pid' => $pid,
                                'hid' => $housename,
                                'tid'=>'Vacant',
                                'Tenantname'=>$tent,
                                'Phone'=>'',
                                'Housename'=>$housename,
                                'Rent' => $rent,
                                'Garbage' => $garbage,
                                'Month' => $watermonth,
                                'Waterbill' => $water,
                                'Others' => $others,
                                'Excess' => $excess,
                                'Arrears' => $arrears,
                                'PaidUploaded' => $payment,
                                'TotalUsed' => $TotalUsed,
                                'TotalPaid' => $TotalPaid,
                                'MessageStatus'=> 'N/A',
                                'MessageStatusSent'=> 'N/A',
                                'Balance' => $Balance,
                                'Due' => $due,
                                'paymentstatus'=>false,
                                'paymentid'=>null
                            );
                        }
                        else{
                            // $error=$id.'I '.$tenant.'TT '.$hse.'H '.$tent.'T '.$excess.'E '.$arrears.'A '.$rent.'R '.$water.'W '.$garbage.'G '.$others.'O '.$total.'T '.$payment.'P '.$balance.'B '.$due.'D ';
                            
                            if ($tenant!="") {
                                $TenantNames=Property::TenantNames($tenant);
                                $tenantphone='+254'.substr($this->getTenantPhone($tenant), 0);
                            }
                            else{
                                $TenantNames=$tent;
                                $tenantphone="";
                                $tenant="Vacant";
                            }
                            $paymentid=Property::checkCurrentTenantBillPayment($pid,$id,$tenant,$month);
                            $paymentstatus=Property::checkTenantBillPaymentStatus($pid,$id,$tenant,$month,$excess,$arrears,$rent,$garbage,$water,$others,$payment);
                            $SentDatePaymentCompleted=(TenantController::getSentDatePaymentCompleted($pid,$tenant,$watermonth))?TenantController::getSentDatePaymentCompleted($pid,$tenant,$watermonth):'';
                            $SentDatePaymentSummary=(TenantController::getSentDatePaymentSummary($pid,$tenant,$watermonth))?TenantController::getSentDatePaymentSummary($pid,$tenant,$watermonth):'';
                            if($SentDatePaymentCompleted){
                                $MessageStatusSent='Completed';
                                $MessageStatus='
                                    <div class="bg-info m-1 mt-0 p-1" style="font-size: 9px;border-radius:5px;" title="Notified as Paid">
                                        <i class="fa fa-envelope text-white"> '.$SentDatePaymentCompleted.'</i>
                                    </div>';
                            }
                            elseif($SentDatePaymentSummary){
                                $MessageStatusSent='Summary';
                                $MessageStatus='
                                    <div class="bg-success m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" title="Message with Payment Details like Rent, Paid and Balance">
                                        <i class="fa fa-envelope text-white"> '.$SentDatePaymentSummary.'</i>
                                    </div>';
                            }
                            else{
                                $MessageStatusSent='Not Yet';
                                $MessageStatus='
                                <div class="m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" >
                                    <i class="text-black">Not Yet</i>
                                </div>';
                            }
                            

                            $TotalUsed=($rent+$garbage+$others+$water+$arrears)-$excess;
                            $TotalPaid=$payment;
                            $Balance=$TotalUsed-$TotalPaid;
                            $water_data[] = array(
                                'pid' => $pid,
                                'hid' => $id,
                                'tid'=>$tenant,
                                'Tenantname'=>$TenantNames,
                                'Phone'=>$tenantphone,
                                'Housename'=>$housename,
                                'Rent' => $rent,
                                'Garbage' => $garbage,
                                'Month' => $watermonth,
                                'Waterbill' => $water,
                                'Others' => $others,
                                'Excess' => $excess,
                                'Arrears' => $arrears,
                                'PaidUploaded' => $payment,
                                'TotalUsed' => $TotalUsed,
                                'TotalPaid' => $TotalPaid,
                                'MessageStatus'=> $MessageStatus,
                                'MessageStatusSent'=> $MessageStatusSent,
                                'Balance' => $Balance,
                                'Due' => $due,
                                'paymentstatus'=>$paymentstatus,
                                'paymentid'=>$paymentid
                            );
                        }
                        
                    }
                }
                else{
                    $error="Error in Water Bill Upload Format.\n 
                        Please Make it 12 Columns As Below:\n
                        A:Housename, B:Tenant Name, C:Excess, D:Arrears, E:Rent, F:Water, G:Garbage, H:Others, I:Total, J:Payment, K:Balance, L:Due";
                    return compact('error');
                }
            }
            $curmonth=$this->getMonthDate($month);
            $success='<span>Preview for '.$curmonth.' is Ready.</br>Please Select Houses to Update or Save</span>';
            $output=$water_data;
            // dd(json_encode($water_data));
            return compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','success');
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return compact('error');
        }
    }


    public function savewaterbillupload(Request $request)
    {
        
            $pid=$request->input('pid');
            $month=$request->input('month');
            $waterbillvalues=$request->input('waterbillvalues');
            $allwaterbill=implode(",", $waterbillvalues);
            $eachwaterbill=explode(",", $allwaterbill);
            $monthdate= $this->getNextMonthdate($month);
            $nextmonth= $this->getNextMonth($month,$monthdate);

            // dd($pid,$month);

            $msgerror="";
            foreach ($eachwaterbill as $eachwater) {
                $mms=explode("?", $eachwater);
                $hid=$mms[0];
                $housename=$mms[1];
                $Tenant=$mms[2];
                $tenantname=$mms[3];
                $Previous=$mms[4];
                $Current=$mms[5];
                $Cost=$mms[6];
                $Units=$mms[7];
                $Total=$mms[8];
                $Total_OS=0.00;
                $totalwater=$Total+$Total_OS;

                try {
                    $totalwater=$Total+$Total_OS;
                    $DateTrans=date('Y-m-d');
                    $explomonth=explode(' ', $month);
                    $years=$explomonth[0];
                    $months=$explomonth[1];
                    $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
                    if(!$paymentid=$this->getPaymentId($hid,$Tenant,$nextmonth)){
                        $paymentsnew = new Payment;
                        $paymentsnew->Plot=$pid;
                        $paymentsnew->House=$hid;
                        $paymentsnew->Tenant=$Tenant;
                        $paymentsnew->Month=$nextmonth;
                        $paymentsnew->Waterbill=$totalwater;
                        if($paymentsnew->save()){
                            
                        }
                        else{
                            $msgerror="Not Saved";
                        }
                    }
                    else{
                        $payments = Payment::findOrFail($paymentid);
                        $payments->Waterbill=$totalwater;
                        if($payments->save()){
                            
                        }
                        else{
                            $msgerror="Not Saved";             
                        }
                    }

                    $water = new Water;
                    $water->Plot=$pid;
                    $water->House=$hid;
                    $water->Tenant=$Tenant;
                    $water->DateTrans=$DateTrans;
                    $water->Month=$month;
                    $water->Cost=$Cost;
                    $water->Units=$Units;
                    $water->Previous=$Previous;
                    $water->Current=$Current;
                    $water->Total=$Total;
                    $water->Total_OS=$Total_OS;
                    $water->Description=$Description; 
                    if($water->save()){
                        
                    }
                    else{
                        $msgerror="Not Saved";
                    }

                    } catch(\Illuminate\Database\QueryException $ex){ 
                            $msgerror="Fatal Error";
                            break;
                    }
            }
            if($msgerror=="Not Saved"){
                return redirect("/properties/upload/waterbill/{$pid}/{$month}")->with('error', 'Some Waterbill Not Uploaded!');
            }
            elseif($msgerror=="Fatal Error"){
                return redirect("/properties/upload/waterbill/{$pid}/{$month}")->with('dbError', 'An Error Occured in Uploading One of the Waterbills!');
            }
            else{
                return redirect("/properties/upload/waterbill/{$pid}/{$month}")->with('success', 'Waterbill Uploaded Successfully!');
            }
         
    }

    public function saveupdatewaterbillupload(Request $request)
    {
        
            $pid=$request->input('savepid');
            $month=$request->input('savemonth');
            $waterbillvalues=$request->input('waterbillvalues');
            $waterbillvaluesupdate=$request->input('waterbillvaluesupdate');
            $msgerror="";
            try {
                if($waterbillvalues){
                    $allwaterbill=implode(",", $waterbillvalues);
                    $eachwaterbill=explode(",", $allwaterbill);
                    $monthdate= $this->getNextMonthdate($month);
                    $nextmonth= $this->getNextMonth($month,$monthdate);

                    foreach ($eachwaterbill as $eachwater) {
                        $mms=explode("?", $eachwater);
                        $hid=$mms[0];
                        $housename=$mms[1];
                        $Tenant=$mms[2];
                        $tenantname=$mms[3];
                        $Previous=$mms[4];
                        $Current=$mms[5];
                        $Cost=$mms[6];
                        $Units=$mms[7];
                        $Total=$mms[8];
                        $Total_OS=0.00;
                        $totalwater=$Total+$Total_OS;

                        
                        $totalwater=$Total+$Total_OS;
                        $DateTrans=date('Y-m-d');
                        $explomonth=explode(' ', $month);
                        $years=$explomonth[0];
                        $months=$explomonth[1];
                        $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
                        if(!$paymentid=$this->getPaymentId($hid,$Tenant,$nextmonth)){
                            $paymentsnew = new Payment;
                            $paymentsnew->Plot=$pid;
                            $paymentsnew->House=$hid;
                            $paymentsnew->Tenant=$Tenant;
                            $paymentsnew->Month=$nextmonth;
                            $paymentsnew->Waterbill=$totalwater;
                            if($paymentsnew->save()){
                                
                            }
                            else{
                                $msgerror="Not Saved";
                            }
                        }
                        else{
                            $payments = Payment::findOrFail($paymentid);
                            $payments->Waterbill=$totalwater;
                            if($payments->save()){
                                
                            }
                            else{
                                $msgerror="Not Saved";             
                            }
                        }

                        $water = new Water;
                        $water->Plot=$pid;
                        $water->House=$hid;
                        $water->Tenant=$Tenant;
                        $water->DateTrans=$DateTrans;
                        $water->Month=$month;
                        $water->Cost=$Cost;
                        $water->Units=$Units;
                        $water->Previous=$Previous;
                        $water->Current=$Current;
                        $water->Total=$Total;
                        $water->Total_OS=$Total_OS;
                        $water->Description=$Description; 
                        $water->save();
                    }
                }
                //update existing waterbill
                
                if($waterbillvaluesupdate){
                    $allwaterbill=implode(",", $waterbillvaluesupdate);
                    $eachwaterbill=explode(",", $allwaterbill);
                    $monthdate= $this->getNextMonthdate($month);
                    $nextmonth= $this->getNextMonth($month,$monthdate);

                    foreach ($eachwaterbill as $eachwater) {
                        $mms=explode("?", $eachwater);
                        $hid=$mms[0];
                        $housename=$mms[1];
                        $Tenant=$mms[2];
                        $tenantname=$mms[3];
                        $Previous=$mms[4];
                        $Current=$mms[5];
                        $Cost=$mms[6];
                        $Units=$mms[7];
                        $Total=$mms[8];
                        $Total_OS=0.00;
                        $waterid=$mms[9];
                        $totalwater=$Total+$Total_OS;

                        if ($Tenant=="") {
                            $error='<span class="">No Tenant Information Found! </span></br>';
                            return compact('error');
                        }

                        $DateTrans=date('Y-m-d');
                        $explomonth=explode(' ', $month);
                        $years=$explomonth[0];
                        $months=$explomonth[1];
                        $housename=Property::getHouseName($hid);
                        $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
                        if(!$paymentid=$this->getPaymentId($hid,$Tenant,$nextmonth)){
                            $paymentsnew = new Payment;
                            $paymentsnew->Plot=$pid;
                            $paymentsnew->House=$hid;
                            $paymentsnew->Tenant=$Tenant;
                            $paymentsnew->Month=$nextmonth;
                            $paymentsnew->Waterbill=$totalwater;
                            $paymentsnew->save();
                        }
                        else{
                            $payments = Payment::findOrFail($paymentid);
                            $payments->Waterbill=$totalwater;
                            $payments->save();
                        }

                        $water = Water::findOrFail($waterid);
                        $water->Plot=$pid;
                        $water->House=$hid;
                        $water->Tenant=$Tenant;
                        $water->DateTrans=$DateTrans;
                        $water->Month=$month;
                        $water->Cost=$Cost;
                        $water->Units=$Units;
                        $water->Previous=$Previous;
                        $water->Current=$Current;
                        $water->Total=$Total;
                        $water->Total_OS=$Total_OS;
                        $water->Description=$Description; 
                        $water->save();
                    }
                    //end of update
                }
            } catch(\Illuminate\Database\QueryException $ex){ 
                    $error=$ex->getMessage();
                    return compact('error');
            }

            if($msgerror=="Not Saved"){
                $error='<span class="">Some Waterbill Not Uploaded! </span></br>';
                return compact('error');
            }
            
            else{
                $success='<span class="">Waterbill Uploaded Successfully! </span></br>
                <span class="">To Update A single House , Please choose Per(Hse) from Dashboard</span></br>';
                return compact('success');
            }
         
    }

    // saveupdatepaymentsupload
    public function saveupdatepaymentsupload(Request $request)
    {
        $pid=$request->input('savepid');
        $month=$request->input('savemonth');
        // $waterbillvalues=$request->input('waterbillvalues');
        $paymentvaluesupdate=$request->input('paymentvaluesupdate');
        $msgerror="";
        try {
            if($paymentvaluesupdate){
                $allpayments=implode(",", $paymentvaluesupdate);
                $eachpayments=explode(",", $allpayments);
                $monthdate= $this->getNextMonthdate($month);
                $nextmonth= $this->getNextMonth($month,$monthdate);

                foreach ($eachpayments as $eachpayment) {
                    $mms=explode("?", $eachpayment);
                    $hid=$mms[0];
                    $housename=$mms[1];
                    $Tenant=$mms[2];
                    $tenantname=$mms[3];
                    $Excess=$mms[4];
                    $Arrears=$mms[5];
                    $Rent=$mms[6];
                    $Garbage=$mms[7];
                    $Waterbill=$mms[8];
                    $Others=$mms[9];
                    $paymentids=$mms[10];
                    $TotalUsed=$mms[11];
                    $TotalPaid=$mms[12];
                    $Due=$mms[13];
                    if ($Tenant=="") {
                        $error='<span class="">No Tenant Information Found! </span></br>';
                        return compact('error');
                    }

                    if(!$paymentid=$this->getPaymentId($hid,$Tenant,$month)){
                        $paymentsnew = new Payment;
                        $paymentsnew->Plot=$pid;
                        $paymentsnew->House=$hid;
                        $paymentsnew->Tenant=$Tenant;
                        $paymentsnew->Month=$month;
                        $paymentsnew->Excess=$Excess;
                        $paymentsnew->Arrears=$Arrears;
                        $paymentsnew->Rent=$Rent;
                        $paymentsnew->Garbage=$Garbage;
                        $paymentsnew->Waterbill=$Waterbill;
                        $paymentsnew->Others=$Others;
                        $paymentsnew->PaidUploaded=$TotalPaid;
                        $paymentsnew->save();
                    }
                    else{
                        $payments = Payment::findOrFail($paymentid);
                        $payments->Excess=$Excess;
                        $payments->Arrears=$Arrears;
                        $payments->Rent=$Rent;
                        $payments->Garbage=$Garbage;
                        $payments->Waterbill=$Waterbill;
                        $payments->Others=$Others;
                        $payments->PaidUploaded=$TotalPaid;
                        $payments->save();
                    }
                }
                //end of update
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return compact('error');
        }

        if($msgerror=="Not Saved"){
            $error='<span class="">Some Payments Not Uploaded! </span></br>';
            return compact('error');
        }
        
        else{
            $success='<span class="">Payments Uploaded Successfully! </span></br>';
            return compact('success');
        }
         
    }
    

    public function saveupdatebillssubmitupdate(Request $request)
    {
        try {
            $pid=$request->input('paymentpid');
            $month=$request->input('paymentmonth');
            $hid=$request->input('paymenthid');
            $tid=$request->input('paymenttid');
            $UpdateType=$request->input('UpdateType');
            $paymentpaid=$request->input('paymentpaid');
            $paymentdate=$request->input('paymentdate');

            if($UpdateType==""){
                $error='<div class="text-danger">No Type of Payment Amount Intended for is Specifed! </div></br>';
                return compact('error');
            }
            
            if($paymentpaid==""){
                $error='<div class="text-danger">No Payment Amount Intended for is Specifed! </div></br>';
                return compact('error');
            }

            if($UpdateType=="Excess"){
                $update=Payment::where('Plot',$pid)
                ->where('House',$hid)
                ->where('Tenant',$tid)
                ->where('Month',$month)
                ->update(['Excess'=>$paymentpaid]);
                if($update){
                    $success='<div class="text-success">Excess Amount Updated.! </div></br>
                    <div class="text-success">Excess Amount Information was Updated to '.$paymentpaid.'</div></br>';
                    return compact('success');
                }
                else{
                    $error='<div class="text-danger">Could not Update Excess Amount Information! </div></br>';
                    return compact('error');
                }
            }

            if($UpdateType=="Arrears"){
                $update=Payment::where('Plot',$pid)
                ->where('House',$hid)
                ->where('Tenant',$tid)
                ->where('Month',$month)
                ->update(['Arrears'=>$paymentpaid]);
                if($update){
                    $success='<div class="text-success">Arrears Amount Updated.! </div></br>
                    <div class="text-success">Arrears Amount Information was Updated to '.$paymentpaid.'</div></br>';
                    return compact('success');
                }
                else{
                    $error='<div class="text-danger">Could not Update Arrears Amount Information! </div></br>';
                    return compact('error');
                }
            }

            if($UpdateType=="Penalty"){
                $update=Payment::where('Plot',$pid)
                ->where('House',$hid)
                ->where('Tenant',$tid)
                ->where('Month',$month)
                ->update(['Penalty'=>$paymentpaid]);
                if($update){
                    $success='<div class="text-success">Penalty Amount Updated.! </div></br>
                    <div class="text-success">Penalty Amount Information was Updated to '.$paymentpaid.'</div></br>';
                    return compact('success');
                }
                else{
                    $error='<div class="text-danger">Could not Update Penalty Amount Information! </div></br>';
                    return compact('error');
                }
            }

            if($UpdateType=="Paid"){
                $currentpaid=0;
                $paymentid='';
                if($paid=Payment::where('Plot',$pid)->where('House',$hid)->where('Tenant',$tid)->where('Month',$month) ->get()->first()){
                    $currentpaid=$paid->PaidUploaded;
                    $paymentid=$paid->id;
                }

                $update=Payment::where('Plot',$pid)
                ->where('House',$hid)
                ->where('Tenant',$tid)
                ->where('Month',$month)
                ->update(['PaidUploaded'=>($paymentpaid + $currentpaid)]);
                if($update){
                    // update payment dated
                    if($paymentdate==''){
                        $error='<div class="text-danger">No Date Information selected! </div></br>';
                        return compact('error');
                    }
                    $newPaymentdate=new PaymentDate;
                    $newPaymentdate->Plot=$pid;
                    $newPaymentdate->House=$hid;
                    $newPaymentdate->Tenant=$tid;
                    $newPaymentdate->Amount=$paymentpaid;
                    $newPaymentdate->Month=$month;
                    $newPaymentdate->Date_Transacted=$paymentdate;
                    $newPaymentdate->PId=$paymentid;
                    $newPaymentdate->save();
                    $success='<div class="text-success">Paid Amount Updated.! </div>
                    <div class="text-success">Paid Amount Information was Updated to '.$paymentpaid.'</div>';
                    return compact('success');
                }
                else{
                    $error='<div class="text-danger">Could not Update Paid Amount Information! </div></br>';
                    return compact('error');
                }
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return compact('error');
        }
    }
    
    public function savewaterbillothernew(Request $request)
    {
        
            $Tenant=$request->input('Tenant');
            $month=$request->input('month');
         try {
            $Previous=$request->input('Previous');
            $Current=$request->input('Current');
            $Cost=$request->input('Cost');
            $Units=$request->input('Units');
            $Total=$request->input('Total');
            $Total_OS=$request->input('Total_OS');
            $totalwater=$Total+$Total_OS;
            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= $this->getNextMonthdate($month);
            $nextmonth= $this->getNextMonth($month,$monthdate);
            $Description=$years.' Month '.$months.' Other'.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
            if(!$paymentid=$this->getPaymentOtherId($Tenant,$nextmonth)){
                $paymentsnew = new PaymentsOthers;
                $paymentsnew->Tenant=$Tenant;
                $paymentsnew->Month=$nextmonth;
                $paymentsnew->Waterbill=$totalwater;
                $paymentsnew->save();
            }
            else{
                $payments = PaymentsOthers::findOrFail($paymentid);
                $payments->Waterbill=$totalwater;
                $payments->save();
            }

            $water = new WaterOthers;
            $water->Tenant=$Tenant;
            $water->DateTrans=$DateTrans;
            $water->Month=$month;
            $water->Cost=$Cost;
            $water->Units=$Units;
            $water->Previous=$Previous;
            $water->Current=$Current;
            $water->Total=$Total;
            $water->Total_OS=$Total_OS;
            $water->Description=$Description;  
            $water->save();
                return redirect("/properties/add/waterbill/Others/{$month}/{$Tenant}")->with('success', 'Waterbill for Others Recorded!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/add/waterbill/Others/{$month}/{$Tenant}")->with('dbError', $ex->getMessage());
            }
    }

        public function otherupdatewaterbill(Request $request)
    {
        
            $Tenant=$request->input('Tenant');
            $month=$request->input('month');
            $waterid=$request->input('waterid');
         try {
            $Previous=$request->input('Previous');
            $Current=$request->input('Current');
            $Cost=$request->input('Cost');
            $Units=$request->input('Units');
            $Total=$request->input('Total');
            $Total_OS=$request->input('Total_OS');
            $totalwater=$Total+$Total_OS;
            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= $this->getNextMonthdate($month);
            $nextmonth= $this->getNextMonth($month,$monthdate);
            $Description=$years.' Month '.$months.' Other '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
            if(!$paymentid=$this->getPaymentOtherId($Tenant,$nextmonth)){
                $paymentsnew = new PaymentsOthers;
                $paymentsnew->Tenant=$Tenant;
                $paymentsnew->Month=$nextmonth;
                $paymentsnew->Waterbill=$totalwater;
                $paymentsnew->save();
            }
            else{
                $payments = PaymentsOthers::findOrFail($paymentid);
                $payments->Waterbill=$totalwater;
                $payments->save();
            }

            $water = WaterOthers::findOrFail($waterid);
            $water->Tenant=$Tenant;
            $water->DateTrans=$DateTrans;
            $water->Month=$month;
            $water->Cost=$Cost;
            $water->Units=$Units;
            $water->Previous=$Previous;
            $water->Current=$Current;
            $water->Total=$Total;
            $water->Total_OS=$Total_OS;
            $water->Description=$Description;  
            $water->save();
                return redirect("/properties/add/waterbill/Others/{$month}/{$Tenant}")->with('success', 'Waterbill for Others Recorded!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/add/waterbill/Others/{$month}/{$Tenant}")->with('dbError', $ex->getMessage());
            }
    }

        public function saveotherpayment(Request $request)
    {
        
            $id=$request->input('id');
            $month=$request->input('month');
            $wid=$request->input('wid');
            $Arrears=$request->input('Arrears');
            $Excess=$request->input('Excess');
            $PaidUploaded=$request->input('PaidUploaded');
            $mode="Other Notification";
            $redit="/properties/send/message/Other/{$mode}/{$month}";
         try {
            
            $payments = PaymentsOthers::findOrFail($wid);
            $payments->Arrears=$Arrears;
            $payments->Excess=$Excess;
            $payments->PaidUploaded=$PaidUploaded;
            $payments->save();
            
                return redirect($redit)->with('success', 'Waterbill Payment for Others Recorded!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect($redit)->with('dbError', $ex->getMessage());
            }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->middleware('auth');
        try { 
           $updateData = $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $changepass=User::findOrFail($id);
            $changepass->password =Hash::make($request->input('password'));
            $changepass->save();
            $username=Property::getUsername($id);
            Property::setUserLogs('Change Password for ::'.$username);
            return redirect('/profile/change-password')->with('success', 'Password Changed!');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            Property::setUserLogs('Error Changing Password :'.$username.' '. $ex->getMessage());
            return redirect('/profile/change-password')->with('dbError', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function getNextMonth($month,$monthdate){
        $watermonthlast= date("Y n",strtotime("+1 months",strtotime($monthdate)));
        return $watermonthlast;
    }

    public static function getNextMonthdate($thismonth){
        $explomonth=explode(' ', $thismonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y-m-01');
        return $month;
    }
    public function updateTenant($tid,$hid){
        try { 
            $updatetenant = Tenant::findOrFail($tid);
            $updatetenant->Status ='Assigned';
            $updatetenant->save();
            $updatehouse = House::findOrFail($hid);
            $updatehouse->Status ='Occupied';
            $updatehouse->save();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public function updateTenantVacate($tid,$hid,$tenantassignedhse){
        try { 
            $updatehoousevacate = House::findOrFail($hid);
            $updatehoousevacate->Status ='Vacant';
            $updatehoousevacate->save();

            if ($tenantassignedhse<2) {
                $updatetenantvacates = Tenant::findOrFail($tid);
                $updatetenantvacates->Status ='Vacated';
                $updatetenantvacates->save();
            }
            
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }
    public function updateTenantTransfer($tid,$transferid,$assignedhses){
        try { 
            $updatetenant = Tenant::findOrFail($transferid);
            $updatetenant->Status ='Transferred';
            $updatetenant->save();

            if ($assignedhses<2) {
                $updatetenantfrom = Tenant::findOrFail($tid);
                $updatetenantfrom->Status ='Vacated';
                $updatetenantfrom->save();
            }
            
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public function updateTenantReassign($tid,$hid,$id){
        try { 
            $updatetenant = Tenant::findOrFail($tid);
            $updatetenant->Status ='Reassigned';
            $updatetenant->save();

            $updatefrom = House::findOrFail($id);
            $updatefrom->Status ='Vacant';
            $updatefrom->save();

            $updateto = House::findOrFail($hid);
            $updateto->Status ='Occupied';
            $updateto->save();
            
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }


    public function updateTenantAgreement($id,$dateto){
        $month=date_format(date_create($dateto),'Y n');
        try { 
            $updatetenant = Agreement::findOrFail($id);
            $updatetenant->DateTo =$dateto;
            $updatetenant->DateVacated =$dateto;
            $updatetenant->Month =$month;
            $updatetenant->save();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }
    public function updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease){
        try { 
            $updatepayments = Payment::findOrFail($paymentid);
            $updatepayments->Rent =$Rent;
            $updatepayments->Garbage =$Garbage;
            $updatepayments->KPLC =$KPLC;
            $updatepayments->HseDeposit =$HseDeposit;
            $updatepayments->Water =$Water;
            $updatepayments->Lease =$Lease;
            $updatepayments->save();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public function updatePaymentsExisting($paymentid,$Rent,$Garbage){
        try { 
            $updatepayments = Payment::findOrFail($paymentid);
            $updatepayments->Rent =$Rent;
            $updatepayments->Garbage =$Garbage;
            $updatepayments->save();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public function updatePaymentsReassign($paymentid,$Rent,$Garbage,$Arrears,$Excess){
        try { 
            $updatepayments = Payment::findOrFail($paymentid);
            $updatepayments->Rent =$Rent;
            $updatepayments->Garbage =$Garbage;
            $updatepayments->Arrears =$Arrears;
            $updatepayments->Excess =$Excess;
            $updatepayments->save();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public static function getPropertyId($id){
        $results = House::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Plot'];
            }
        return $resultname;
    }

    public static function getTenantPhone($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Phone'];
            }
        return $resultname;
    }
    public static function getAgreementId($uid){
        $results = Agreement::where('UniqueID',$uid)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['id'];
            }
        return $resultname;
    }

    public static function getPaymentId($hid,$tenant,$month){
        $Payments=DB::table('payments')->where([
            'Tenant'=>$tenant,
            'House'=>$hid,
            'Month'=>$month
        ])->max('id');
        return $Payments;
    }

    public static function getPaymentOtherId($tenant,$month){
        $Payments=DB::table('payments_others')->where([
            'Tenant'=>$tenant,
            'Month'=>$month
        ])->max('id');
        return $Payments;
    }

    public static function getPaymentUpdateId($id,$month){
        $Payments=DB::table('payments')->where([
            'House'=>$id,
            'Month'=>$month
        ])->max('id');
        return $Payments;
    }

    public static function tenantHousesAssigned($id){
        $houseshere= Agreement::where('Tenant',$id)->get();
        $housesassignedcount=0;
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                $housesassignedcount++;
            }
            
        }
        return $housesassignedcount;
    }

    public function TenantBalance($id,$hid){
        $Arrears=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Arrears');

        $Excess=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Excess');

        $Rent=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Rent');

        $Garbage=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Garbage');

        $KPLC=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('KPLC');

        $HseDeposit=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('HseDeposit');

        $Water=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Water');

        $Lease=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Lease');

        $Waterbill=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Waterbill');

        $Equity=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Equity');

        $Cooperative=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Cooperative');

        $Others=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('Others');

        $PaidUploaded=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->sum('PaidUploaded');
        $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
        $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
        $Balance=$TotalUsed-$TotalPaid;
        return $Balance;
    }

    public function saveupdatebills(Request $request)
    {
        
            $id=$request->input('id');
            $month=$request->input('month');
         try {
                $houseinfo=House::where('Plot',$id)->get();
                foreach($houseinfo as $result){
                    $hid= $result['id'];
                    $Rent= $result['Rent'];
                    $Garbage= $result['Garbage'];
                    $tid=Property::checkCurrentTenant($hid);
                    $monthdate= Property::getLastMonthdate($month);
                    $lastmonth= Property::getLastMonth($month,$monthdate);
                    $bal=Property::PaymentBal($tid,$hid,$lastmonth);
                    $Arrears=0.00;
                    $Excess=0.00;
                    if ($bal>0) {
                        $Arrears=$bal;
                    }
                    elseif ($bal<0) {
                        $Excess=abs($bal);
                    }
                    if ($tid!='') {
                        if(!$paymentid=$this->getPaymentUpdateId($hid,$month)){
                            $paymentsnew = new Payment;
                            $paymentsnew->Plot=$id;
                            $paymentsnew->Tenant=$tid;
                            $paymentsnew->House=$hid;
                            $paymentsnew->Month=$month;
                            $paymentsnew->Excess=$Excess;
                            $paymentsnew->Arrears=$Arrears;
                            $paymentsnew->Rent=$Rent;
                            $paymentsnew->Garbage=$Garbage;
                            $paymentsnew->save();
                        }
                        else{
                            $payments = Payment::findOrFail($paymentid);
                            $payments->Excess=$Excess;
                            $payments->Arrears=$Arrears;
                            $payments->Rent=$Rent;
                            $payments->Garbage=$Garbage;
                            $payments->save();
                        }
                    }
                    
                }

            
                return redirect("/properties/update/bills/{$id}/{$month}")->with('success', 'Bills for '.$month.' Updated!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                  return redirect("/properties/update/bills/{$id}/{$month}")->with('dbError', $ex->getMessage());
            }
    }

    public function sendmessage(Request $request)
    {
        
         // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";

        $phone=$request->input('phone');
        $message=$request->input('Message');
        try {
             // Initialize the SDK
            $AT          = new AfricasTalking($username, $apiKey);
            // Get the application service
            $sms        = $AT->sms();

            $result = $sms->send([
                'to'      => $phone,
                'message' => $message,
                'from'    => $username
            ]);
            
            
            // print_r($result);
            $enjson=json_encode($result);
            $characters = json_decode($enjson,true);
            $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
               if(sizeof($recipients)>0){
                  $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                  $sentmgs= substr($messagesent, 8);  
                  $totalmgs= substr($messagesent, 10);  
                  if ($sentmgs==0) {
                    return redirect("/properties/messages")->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
                  }
                  else{
                  foreach ($recipients as $number) {
                    $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
                    $watermessage = new Message;
                    $watermessage->To=$number["number"];
                    $watermessage->Cost=$number["cost"];
                    $watermessage->MessageId=$number["messageId"];
                    $watermessage->Message=$message;
                    $watermessage->Code=$number["statusCode"];
                    $watermessage->Status=$characters["status"];
                    $watermessage->PatchNo=$Patchno;
                    if ($watermessage->save()){
                        return redirect("/properties/messages")->with('success', "Message Sent To :".$number['number']." and Message is:".$message);
                    }
                    else{
                        return redirect("/properties/messages")->with('error', "Message Not Sent To :".$number['number']." and Message is:".$message);
                    }
                  }
                }
              
             }else{
                return redirect("/properties/messages")->with('success', 'Message:'.$message.'<br> <b>Was not Sent to:<b> '.$phone);
             }

        } 
        catch(\GuzzleHttp\Exception\ConnectException $e) {
            $error=$e->getMessage();
            $networkerror='cURL error 6:';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                return redirect("/properties/messages")->with('dbError','Network Error');
            }   
        }
        catch(\Illuminate\Database\QueryException $ex){ 
          return redirect("/properties/messages")->with('dbError', $ex->getMessage());
        }
    }

    public function updatehouse(Request $request)
    {
         try {
            $houseno=$request->input('houseno');
            $updatefield=$request->input('updatefield');
            $updatevalue=$request->input('updatevalue');

            $allhousesid=implode(",", $houseno);
            $eachhouseid=explode(",", $allhousesid);
            foreach ($eachhouseid as $eachhid) {
                $hid=$eachhid;
                $payments = House::findOrFail($hid);
                $payments->$updatefield=$updatevalue;
                $payments->save();
            }
                return redirect("/properties/houses")->with('success', $updatefield.' for House(s) Update!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                return redirect("/properties/houses")->with('dbError', $ex->getMessage());
            }
    }

    public function smsDeliveryReports(Request $request)
    {
        dd($requests);
    }

    

    public static function getTotalHousesHse($id){
        $Totals=DB::table('houses')->where([
            'Plot'=>$id
        ])->count();
        return $Totals;
    }

    public static function getTotalBillsHse($id,$month){
        $Totals=DB::table('waters')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->count();
        return $Totals;
    }

    public static function getTotalBillsMsgHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=$house->id;
            $total=DB::table('water_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            $Totals=$Totals+$total;
        }
        return $Totals;
    }

    public static function getMonthDate($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'M, Y');
        return $month;
    }
}