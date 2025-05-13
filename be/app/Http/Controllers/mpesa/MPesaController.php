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

    
}
