<?php

namespace App\Http\Controllers\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Agency;
use App\Models\User;
use App\Models\House;
use App\Models\Agreement;
use App\Models\Tenant;
use App\Models\Water;
use App\Models\Report;
use App\Models\WaterMessage;
use App\Models\Payment;

use App\Models\PaymentsOthers;
use App\Models\WaterOthers;
use App\Models\Message;
use App\Models\WaterMessagesOthers;
use App\Models\PaymentDate;
use App\Models\PaymentMessage;
use App\Models\Mails;
use App\Models\Propertyhousetype;
use App\Models\UserLogs;

use AfricasTalking\SDK\AfricasTalking;
use Webklex\IMAP\Facades\Client;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
class MPesaController extends Controller
{
    public static function getAccessToken(){
        $curl = curl_init(env('MPESA_URL_ENDPOINT_ACCESS_TOKEN'));

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_HTTPHEADER=>['cONTENT-tYPE:APPLICATION/JSON;CHARSET=UTF8'],
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_HEADER=>false,
                CURLOPT_USERPWD=>env('MPESA_CONSUMER_KEY').':'.env('MPESA_CONSUMER_SECRET')
            )
        );

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        if($response){
            return $response->access_token;
        }
        else{
            return '';
        }

        // response.data.access_token



        // $ch = curl_init(env('MPESA_URL_ENDPOINT_ACCESS_TOKEN'));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic SnltV1M3VzAxRkRUR0JqcjBzU29jWjJWSWFaV3o3dVB3NTFIT0hweWlHMDZic0tLOjdhM0pFb1NQaTgyODR3QkRXWXdQN1NGS0ZIOTEzN2hzc25oSEVXcVZ2Zk1XMk9FRTdwaFFJN0dhcTBzc1ZvcTQ=']);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $response = curl_exec($ch);
        // curl_close($ch);
        // echo $response;
        
    }


    public function registerURLs(){

        $body=array(
            'ShortCode' => env('MPESA_SHORTCODE'),
            'ResponseType' =>'Completed',
            'ConfirmationURL' =>env('MPESA_TEST_URL').'/v2/confirmation',
            'ValidationURL' =>env('MPESA_TEST_URL').'/v2/validation',
        );

        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $response = $this->makeHTTPPost($url,$body);

        return $response;

        // $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Authorization: Bearer 2GqjvOs8nL7HHgxM6WkaoNlq5tu6',
        //     'Content-Type: application/json'
        // ]);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // // curl_setopt($ch, CURLOPT_POSTFIELDS,{
        // //     "ShortCode": 600997,
        // //     "ResponseType": "Completed",
        // //     "ConfirmationURL": "https://mydomain.com/confirmation",
        // //     "ValidationURL": "https://mydomain.com/validation",
        // // });
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $response     = curl_exec($ch);
        // curl_close($ch);
        // echo $response;
    }

    public function simulateTransaction(Request $request){

        $body=array(
            "ShortCode"=> 600987,
            "CommandID"=> "CustomerPayBillOnline",
            "Amount"=>  $request->amount,
            "Msisdn"=> "254708374149",
            "BillRefNumber"=> $request->account,
        );

        $url='https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

        $response = $this->makeHTTPPost($url,$body);

        return $response;

        // $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Authorization: Bearer lkR6CKwFKaKBvqp7vGviWPY0EAoI',
        //     'Content-Type: application/json'
        // ]);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, {
        //     "ShortCode": 600987,
        //     "CommandID": "CustomerPayBillOnline",
        //     "amount": "1",
        //     "MSISDN": "254705912645",
        //     "BillRefNumber": "2312",
        // });
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $response     = curl_exec($ch);
        // curl_close($ch);
        // echo $response;

    }

    public function setConfirmationURL(){
        $response=[    
            "TransactionType"=>"Pay Bill",
            "TransID"=>"RKTQDM7W6S",
            "TransTime"=>"20191122063845",
            "TransAmount"=>"10",
            "BusinessShortCode"=>"600638",
            "BillRefNumber"=>"invoice008",
            "InvoiceNumber"=>"",
            "OrgAccountBalance"=>"",
            "ThirdPartyTransID"=>"",
            "MSISDN"=>"25470****149",
            "FirstName"=>"John",
            "MiddleName"=>"",
            "LastName"=>"Doe"
        ];
    }



    public static function makeHTTPPost($url,$body ){
      
        
        $curl=curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL =>$url,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json','Authorization:Bearer '.Property::getAccessToken()),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => json_encode($body),
            )
        );


        $curl_response=curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }

    // ngrok config add-authtoken 1jYK8CX76eKr4lnetQ1ojVaaeMa_2vpk2HokS3AqVvpKeo97v
    
    
}
