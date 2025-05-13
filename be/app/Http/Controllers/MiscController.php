<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Property;
use App\Models\Agency;

use App\Models\UserLogs;
use App\Models\Message;
use App\Model\WaterMessage;
use App\Model\PaymentMessage;

class MiscController extends Controller
{
    //
    public function smsDeliveryReports(Request $request){
        
        $id=$request->input('id');
        $status=$request->input('status');
        $phoneNumber =$request->input('phoneNumber');
        $networkCode =$request->input('networkCode');
        $failureReason= $request->input('failureReason');
        $retryCount =$request->input('retryCount');

        
        Property::setLogs("Found:".$id." ".$status." ".$phoneNumber." ".$failureReason);

        $update=Message::where('MessageId',$id)
                ->update(['Status'=>$status]);

        $updatewatermessage=WaterMessage::where('MessageId',$id)
                ->update(['Status'=>$status]);

        $updatepaymentmessage=PaymentMessage::where('MessageId',$id)
                ->update(['Status'=>$status]);

       
    }

    public function getSiteData(Request $request){
        try{
            $agencydetail = Agency::first();
            $agency=[
                'id' => $agencydetail->id,
                'Names' => $agencydetail->Names,
                'Address' => $agencydetail->Address,
                'Town' => $agencydetail->Town,
                'Phone' => $agencydetail->Phone,
                'Email' => $agencydetail->Email,
                'islive' => $agencydetail->islive,
            ];


        return response()->json([
            'status'=>200,
            'agencydetail'=>$agency,
        ]);
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>'Error',
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>'Error',
            ]);
        }
    }
}
