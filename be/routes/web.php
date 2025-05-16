<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\MiscController;

use App\Http\Controllers\API\AdminController;

use App\Http\Controllers\MailController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
use App\Models\Blacklisted;

use App\Models\PaymentsOthers;
use App\Models\WaterOthers;
use App\Models\Message;
use App\Models\WaterMessagesOthers;
use App\Models\PaymentDate;
use App\Models\PaymentMessage;


use AfricasTalking\SDK\AfricasTalking;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use GuzzleHttp\Client;

use Carbon\Carbon;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
// 	// $ip=\Request::ip();
// 	// $useragents=\Request::userAgent();
// 	// $usersa['useragent']=\Request::server('HTTP_USER_AGENT');
// 	// echo $useragents." ".$ip;
// 	// dd($useragents,$ip);
//     return view('index');
// });

// Route::get('/testing', function () {
// 	$house='H2O';
//     $house=explode('-', $house);
//     $count=count($house);
//     if($count==1){
//         $housename=$house[0];
//     }
//     else{
//         $housename=$house[1];
//     }
//     return compact('housename','house');
// });

// Route::get('/testing1',function () {
//     $agreements=Agreement::all();
    
//     $agreementinfo= array();
//     $sno2=0;
//     foreach ($agreements as $agreement) {
//         $monthassigned=date_format(date_create($agreement->DateAssigned),'Y n');
//         $monthvacated=0;
//         if($agreement->DateVacated=='0000-00-00' || $agreement->DateVacated==null || $agreement->DateVacated==''){
//             $monthvacated=0;
//         }
//         else{
//             $monthvacated=date_format(date_create($agreement->DateVacated),'Y n');
//         }
//         // if($month == "2021 11"){
//             $agreementid=Property::decryptText($agreement->id);
//             // $agreementinfo[] = array(
//             //     'id' =>         Property::decryptText($agreement->id),
//             //     'Plot'=>        Property::decryptText($agreement->plot),
//             //     'House'=>        Property::decryptText($agreement->house),
//             //     'Tenant'=>        Property::decryptText($agreement->tenant),
//             //     'Tenantname' => Property::checkCurrentTenantFName(Property::decryptText($agreement->tenant)),
//             //     'Housename'=>   Property::getHouseName(Property::decryptText($agreement->house)),
//             //     'DateAssigned'=>        $agreement->DateAssigned,
//             //     'MonthAssigned'=>   $agreement->MonthAssigned,
//             //     'created_at' => $agreement->created_at,
//             // );

//             $DATEASSIGNEDINFO = Agreement::findOrFail($agreementid);
//             $DATEASSIGNEDINFO->MonthAssigned=$monthassigned;
//             $DATEASSIGNEDINFO->save();

//             $DATEVACATEDINFO = Agreement::findOrFail($agreementid);
//             $DATEVACATEDINFO->Month=$monthvacated;
//             $DATEVACATEDINFO->save();
//         // }
//     }

//     return ($agreementinfo);
// });


// Route::get('/testblacklisteding/blacklisted',function () {
//     $blacklisteds=Blacklisted::where('Cost',0.8)->limit(1000)->get();
//     $blacklisteds=Blacklisted::where('Cost',0.8)->skip(1000)->take(1000)->get();
//     $water_data= array();
//     foreach ($blacklisteds as $blacklisted) {
//         $datesent=$blacklisted->DateSent;
//         $messageid=$blacklisted->MessageID;
//         $from=$blacklisted->From;
//         $to=$blacklisted->To;
//         $message=$blacklisted->Message;
//         $messageparts=$blacklisted->MessageParts;
//         $character=$blacklisted->CharacterCount;
//         $costcurrency=$blacklisted->CostCurrency;
//         $cost=$blacklisted->Cost;
//         $status=$blacklisted->Status;

//         if($messagesStatus=Message::query()->where('MessageId','=',$messageid)->get()->first()){
//             $month=$messagesStatus->Month;
//             $house=$messagesStatus->house;
//             $msgStatus=$messagesStatus->Status;
//             $sent_at=$messagesStatus->created_at;
    
//             $water = Message::findOrFail($messagesStatus->id);
//             $water->Status=$status;
//             $water->save();
    
//             $tenantid=Property::getTenantIdUsingPhone($to);

//             $tenantst=$status;
//             if($status=='Success' || $status=='Sent'){
//                 if($tenantid!=''){
//                     $isblacklisted='No';
//                     $tenant = Tenant::findOrFail($tenantid);
//                     $tenant->isblacklisted =$isblacklisted;
//                     $tenant->save();
//                 }
//             }
//             else{
//                 if($tenantid!=''){
//                     $isblacklisted=$status;
//                     $tenant = Tenant::findOrFail($tenantid);
//                     $tenant->isblacklisted =$status;
//                     $tenant->blacklisted_at =$sent_at;
//                     $tenant->save();

//                     $water_data[] = array(
//                         'datesent' => $datesent,
//                         'messageid' => $messageid,
//                         'from' => $from,
//                         'to' => $to,
//                         'message' => $message,
//                         'character' => $character,
//                         'costcurrency' => $costcurrency,
//                         'cost' =>$cost,
//                         'status' =>$status,
//                         'month' =>$month,
//                         'house' =>$house,
//                         'msgStatus' =>$msgStatus,
//                         'tenantid' =>$tenantid,
//                         'tenantst' =>$tenantst,
//                     );   
//                 }
//             }

//         }


//         // if($messagesStatus=Message::query()->where('MessageId','=',$messageid)->get()->first()){
            
//             // $tenantst=$status;
//             // if($status=='Success' || $status=='Sent'){
//             //     if($tenantid!=''){
//             //         $isblacklisted='No';
//             //         $tenant = Tenant::findOrFail($tenantid);
//             //         $tenant->isblacklisted =$isblacklisted;
//             //         $tenant->save();
//             //     }
//             // }
//             // else{
//             //     if($tenantid!=''){
//             //         $isblacklisted=$status;
//             //         $tenant = Tenant::findOrFail($tenantid);
//             //         $tenant->isblacklisted =$status;
//             //         $tenant->blacklisted_at =$sent_at;
//             //         $tenant->save();

//             //         // $water_data[] = array(
//             //         //     'datesent' => $datesent,
//             //         //     'messageid' => $messageid,
//             //         //     'from' => $from,
//             //         //     'to' => $to,
//             //         //     'message' => $message,
//             //         //     'character' => $character,
//             //         //     'costcurrency' => $costcurrency,
//             //         //     'cost' =>$cost,
//             //         //     'status' =>$status,
//             //         //     'month' =>$month,
//             //         //     'house' =>$house,
//             //         //     'msgStatus' =>$msgStatus,
//             //         //     'tenantid' =>$tenantid,
//             //         //     'tenantst' =>$tenantst,
//             //         // );   
//             //     }
//             // }
//         // }
    
    
//         // update tenant details using Water Message
//         if($messagesStatus1=WaterMessage::query()->where('MessageId','=',$messageid)->get()->first()){
//             $sent_at=$messagesStatus1->created_at;
           
//             $month=$messagesStatus1->Month;
//             $house=$messagesStatus1->house;
//             $msgStatus=$messagesStatus1->Status;

//             $water1 = WaterMessage::findOrFail($messagesStatus1->id);
//             $water1->Status=$status;
//             $water1->save();


//             $tenantid=Property::getTenantIdUsingPhone($to);
//             if($status=='Success' || $status=='Sent'){
//                 if($tenantid!=''){
//                     $isblacklisted='No';
//                     $tenant = Tenant::findOrFail($tenantid);
//                     $tenant->isblacklisted =$isblacklisted;
//                     $tenant->save();
//                 }
//             }
//             else{
//                 if($tenantid!=''){
//                     $isblacklisted=$status;
//                     $tenant = Tenant::findOrFail($tenantid);
//                     $tenant->isblacklisted =$isblacklisted;
//                     $tenant->blacklisted_at =$sent_at;
//                     $tenant->save();

//                     $water_data[] = array(
//                         'datesent' => $datesent,
//                         'messageid' => $messageid,
//                         'from' => $from,
//                         'to' => $to,
//                         'message' => $message,
//                         'character' => $character,
//                         'costcurrency' => $costcurrency,
//                         'cost' =>$cost,
//                         'status' =>$status,
//                         'month' =>$month,
//                         'house' =>$house,
//                         'msgStatus' =>$msgStatus,
//                         'tenantid' =>$tenantid,
//                     );  
                    
//                 }
//             }
//         }
    
             


//     }

//     return ($water_data);
// });

// Route::get('/test/possible/states/abc',function () {

    

//     // $allStates = Property::generateStates();

// // Output the generated states,  implode states with comma and new line.
//     // echo implode(",\n", $allStates);

//     // $maxLevel = 6;
//     // $allStates = Property::visualizeGraph($maxLevel);

//     // // Print the states per level
//     // foreach ($allStates as $level => $levelStates) {
//     //     echo "Level " . $level . ": ";
//     //     echo implode(", ", $levelStates) . "<br/>";
//     // }


    
// });




// // Route::post('/properties/mesages/deliveryreports',[MailController::class, 'deliveryreports']);
// // Route::post('/mesages/smsdelivery',[MiscController::class, 'smsDeliveryReports']);

// Route::get('/newtenants', function () {
	
//     $id=15;
//     $properties = Property::all();
//         $thisproperty='';
//         // $houseinfo='';
//         $sno=0;
//         $housescount=0;
//         if($id==''){
//             $thisproperty='';
//             // $houseinfo='';
//         }
//         // else{
//         //     $thisproperty=Property::findOrFail($id);
//         //     // $houseinfo=House::where('Plot',$id)->get();
//         // }

       
//         else if($id=='vacant'){
//             $housesinfos = House::orderByDesc('id')->where('Status','Vacant')->get();
//             $housescount = House::where('Status','Vacant')->count();
//             $payments= array();
//             foreach ($housesinfos as $house) {
//                 $houseid=$house->id;
//                 $tenant=Property::checkCurrentTenant($houseid);
//                 $plotcode=Property::getPropertyCode($house->Plot);
//                 $plotname=Property::getPropertyName($house->Plot);
//                 $tenantname='';
//                 if ($tenant=='') {
//                     $tenant='Vacated';
//                     $tenantname='Vacant';
//                 }
//                 else{
//                     $tenantname=Property::checkCurrentTenantFName($tenant);
//                 }
//                 $payments[] = array(
//                     'id' =>         $house->id,
//                     'Plot'=>        $house->Plot,
//                     'Housename'=>   $house->Housename,
//                     'tenant'=>      $tenant,
//                     'tenantname'=>  ucwords(strtolower($tenantname)),
//                     'plotcode'=>    $plotcode,
//                     'plotname'=>    $plotname,
//                     'Rent'=>        $house->Rent,
//                     'Deposit'=>     $house->Deposit,
//                     'Water' =>      $house->Water,
//                     'Lease' =>      $house->Lease,
//                     'Garbage' =>    $house->Garbage,
//                     'DueDay' =>     $house->DueDay,
//                     'Status' =>     $house->Status,
//                     'Kplc' =>       $house->Kplc,
//                     'created_at' => $house->created_at,
//                 );
//             }
//             $housesinfo=$payments;
//             // return compact('housesinfo','housescount');
//         }
//         else if($id=='Vacated' || $id=='New' || $id=='Assigned' || $id=='Reassigned' || $id=='Other' || $id=='Transferred'){
//             $tenants = Tenant::orderByDesc('id')->where('Status',$id)->get();
            
//             $tenantinfo= array();
//             $sno=0;
//             foreach ($tenants as $property) { 
//                 $tenantinfo[] = array(
//                     'sno'=>         $sno,
//                     'id' =>         $property->id,
//                     'Fname' =>      $property->Fname,
//                     'Oname' =>      ucwords(strtolower($property->Oname)),
//                     'Gender' =>     $property->Gender,
//                     'IDno' =>       $property->IDno,
//                     'Phone' =>       $property->Phone,
//                     'PhoneMasked'=> Property::getTenantPhoneMask($property->Phone),
//                     'Email' =>      $property->Email,
//                     'Status' =>     $property->Status,
//                     'Houses'=>      Property::tenantHousesAssigned($property->id),
//                     'housesdata'=>  Property::tenantHousesOccupiedDataOnly($property->id),
//                     'Housenames'=>  Property::tenantHousesOccupiedOnly($property->id),
//                     'created_at' => $property->created_at
//                 );
//                 $sno++;
//             }

//             $housesinfo=$tenantinfo;
//         }
//         else{
//             $thisproperty=Property::findOrFail($id);
//             $agreements=Agreement::orderByDesc('id')->where('Plot',$id)->where('Month',0)->get();
//             $agreementinfo= array();
//             $sno2=0;
//             foreach ($agreements as $agreement) {
//                 $plotcode=Property::getPropertyCode($agreement->Plot);
//                 $plotname=Property::getPropertyName($agreement->Plot);

//                 $tenantfname=Property::TenantFNames($agreement->Tenant);
//                 $Fname=Property::getTenantFname($agreement->Tenant);
//                 $Oname=Property::getTenantOname($agreement->Tenant);

//                 $tenantname=Property::TenantNames($agreement->Tenant);

//                 $houseid=Property::checkCurrentTenantHouse($agreement->Tenant);

//                 $agreementinfo[] = array(
//                     'sno'=>             $sno2,
//                     'id' =>             $agreement->Tenant,
//                     'aid' =>            $agreement->id,
//                     'Plot'=>            $agreement->Plot,
//                     'House'=>           $agreement->House,
//                     'Housename'=>       Property::getHouseName($houseid),
//                     'Tenant'=>          $agreement->Tenant,
//                     'Fname' =>          Property::getTenantFname($agreement->Tenant),
//                     'Oname' =>          ucwords(strtolower(Property::getTenantOname($agreement->Tenant))),
//                     'Phone'=>           Property::getTenantPhone($agreement->Tenant),
//                     'PhoneMasked'=>     Property::getTenantPhoneMask(Property::getTenantPhone($agreement->Tenant)),
//                     'Email'=>           Property::getTenantEmail($agreement->Tenant),
//                     'Gender'=>          Property::getTenantGender($agreement->Tenant),
//                     'IDno'=>            Property::getTenantIDno($agreement->Tenant),
//                     'Status'=>          Property::tenantStatus($agreement->Tenant),
//                     'tenantname'=>      ucwords(strtolower($tenantname)),
//                     'tenantfname'=>     ucwords(strtolower($tenantfname)),
//                     'Houses'=>  Property::tenantHousesAssigned($agreement->Tenant),
//                     'housesdata'=>  Property::tenantHousesOccupiedDataOnly($agreement->Tenant),
//                     'Housenames'=>  Property::tenantHousesOccupiedOnly($agreement->Tenant),
//                     'housesoccupied'=>  Property::tenantHousesOccupied($agreement->Tenant,$agreement->House),
//                     'plotcode'=>        $plotcode,
//                     'plotname'=>        $plotname,
//                     'Transaction'=>     $agreement->Transaction,
//                     'Refund'=>          $agreement->Refund,
//                     'Deposit'=>         $agreement->Deposit,
//                     'Arrears' =>        $agreement->Arrears,
//                     'Damages' =>        $agreement->Damages,
//                     'Month' =>          $agreement->Month,
//                     'DateVacated' =>    $agreement->DateVacated,
//                     'DateTo' =>         $agreement->DateTo,
//                     'DateAssigned' =>   Property::checkCurrentTenantHouseDateAssigned($agreement->id),
//                     'created_at' =>     Property::getTenantCReatedAt($agreement->Tenant),
//                 );
//                 $sno2++;
//             }


            
//             $housesinfo=$agreementinfo;
//             // return compact('housesinfo','housescount');
//         }
//         $totalproperties=0;
//         $propertyinfo= array();
//         $sno1=0;
//         $alltotalproperties=($properties->count());

//         foreach ($properties as $property) { 
//             $propertyinfo[] = array(
//                 'sno1'=>$sno1,
//                 'id' => $property->id,
//                 'Plotcode' => $property->Plotcode,
//                 'Plotname' => $property->Plotname,
//                 'Plotarea' => $property->Plotarea,
//                 'Plotaddr' => $property->Plotaddr,
//                 'Plotdesc' => $property->Plotdesc,
//                 'Waterbill' => $property->Waterbill,
//                 'Deposit' => $property->Deposit,
//                 'Waterdeposit' => $property->Waterdeposit,
//                 'Outsourced' => $property->Outsourced,
//                 'Garbage' => $property->Garbage,
//                 'Kplcdeposit' => $property->Kplcdeposit,
//                 'totalhouses' =>Property::getTotalHousesHse($property->id),
//                 // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
//                 'totaloccupied' =>Property::getTotalHousesOccupied($property->id),
//                 'created_at' => $property->created_at
//             );
//             $sno1++;
//         }

//         $propertiesone= array();
//         $noo=0;
//         $propertyid='';
//         foreach ($properties as $property) { 
//             $propertiesone[] = array(
//                 'noo'=>$noo,
//                 'id' => $property->id,
//                 'code' => $property->Plotcode,
//                 'name' => $property->Plotname
//             );
//             $propertyid=$property->id;
//             $noo++;
//         }

//         $propertiesone[] = array(
//             'noo'=>$noo+1,
//             'id' => 'Vacated',
//             'code' => 'Vacate',
//             'name' => 'Vacated'
//         );

//         $propertiesone[] = array(
//             'noo'=>$noo+2,
//             'id' => 'Assigned',
//             'code' => 'Assign',
//             'name' => 'Assigned'
//         );

//         $propertiesone[] = array(
//             'noo'=>$noo+3,
//             'id' => 'Reassigned',
//             'code' => 'Reassign',
//             'name' => 'Reassigned'
//         );

//         $propertiesone[] = array(
//             'noo'=>$noo+4,
//             'id' => 'Transferred',
//             'code' => 'Transfer',
//             'name' => 'Transferred'
//         );

//         $propertiesone[] = array(
//             'noo'=>$noo+5,
//             'id' => 'New',
//             'code' => 'New',
//             'name' => 'New'
//         );


//         $propertiesone[] = array(
//             'noo'=>$noo+6,
//             'id' => 'Other',
//             'code' => 'Others',
//             'name' => 'Other'
//         );

//         $propertyslides= array();
//         $alltotalproperties=count($propertiesone);
//         $slides=ceil((count($propertiesone))/3);
     
//         $propertyslidesCOM= array();
//         $snooo=0;
//         $io=0;
//         $numbs=[];
//         for ($i=1; $i < $slides; $i++) { 
//             // $totalproperties=$snooo+3;
//             for ($j=0; $j < 3; $j++) { 
//                 if(in_array($io,$numbs)){
//                     echo 'none </br>';
//                     continue;
//                 }
//                 else{
                    
//                     // if($totalproperties < $alltotalproperties){
//                         array_push($numbs,$io);
//                         $propertyslides[] = array(
//                             'data'=>$propertiesone[$io],
//                             'prop'=>$totalproperties
//                         );
//                         // $propertyslidesCOM[$i] = array(
//                         //     'slide'=>$propertyslides,
//                         //     'snoo'=>$snooo
//                         // );
//                         // echo "Slide: ".$i." No: ".$totalproperties." Nos: ".$snooo." Ios: ".$io .'</br>';
//                         // print_r($numbs);
//                         $totalproperties=$snooo+3;
//                         $io++;
//                     // }
//                     // else{
//                     //     continue;
//                     // }
//                 }
                
//                 // $totalproperties++;
//             }
//             // echo '</br>';
//             // $propertyslidesCOM[] = array(
//             //     'slide'=>$propertyslides,
//             //     'snoo'=>$snooo
//             // );
//             $snooo++;
//         }
        
//         return response()->json([
//             'status'=>200,
//             'properties'=>$propertyslidesCOM,
//             'propertyinfo'=>$propertyinfo,
//             'tenantinfo'=>$housesinfo,
//             'thisproperty'=>$thisproperty,
//             'message'=>'Action Loaded Succesfully',
//         ]);


//     // return compact('housename','house');
// });

// Route::get('/homepage', function () {
//     return view('index');
// });


// Route::get('/aboutus', function () {
//     return view('aboutus');
// });

// Route::get('/contactus', function () {
//     return view('contactus');
// });

// Route::get('/gallery', function () {
//     return view('gallery');
// });

// Route::get('/loginhere', function () {
//     return view('loginhere');
// });

// Route::get('/allproperties', function () {
//     return view('properties');
// });


// Route::get('/newuser', function () {
//     return view('newuser');
// });



// Route::get('/dashboard',[HomeController::class, 'dashboard'])->middleware("verified");

// Route::get('/getappdata',[HomeController::class, 'getappdata'])->middleware("verified");

// Route::get('/profile',[HomeController::class, 'profile'])->middleware("verified");

// Route::get('/profile/change-password',[HomeController::class, 'changepassword'])->middleware("password.confirm");



// Route::get('/users',[HomeController::class, 'users'])->middleware("verified");

// Route::get('/properties',[PlotController::class, 'index'])->middleware("verified");
// Route::get('/properties/manage',[PlotController::class, 'plotsmgr'])->middleware("verified");
// Route::get('/properties/mgr/tenants',[PlotController::class, 'tenantsmgr'])->middleware("verified");

// Route::post('/properties/manage/info',[PlotController::class, 'plotsmgrinfo'])->middleware("verified");
// Route::get('/properties/manage/houses/{id}',[PlotController::class, 'plotsmgrhouses'])->middleware("verified");
// Route::get('/properties/manage/houses/tenant/{id}',[PlotController::class, 'plotsmgrhousestenant'])->middleware("verified");
// Route::get('/properties/manage/houses/vacate/tenant/{id}',[PlotController::class, 'plotsmgrhousesvacatetenant'])->middleware("verified");
// Route::get('/properties/manage/tenants/{status}',[PlotController::class, 'plotsmgrtenants'])->middleware("verified");

// Route::get('/properties/frequentlyasked',[PlotController::class, 'frequentlyasked']);

// Route::get('/properties/tenants',[PlotController::class, 'alltenants'])->middleware("verified");
// Route::get('/properties/tenants/property/{id}',[PlotController::class, 'propertytenants'])->middleware("verified");
// Route::get('/properties/tenants/Actions/{status}',[PlotController::class, 'statustenants'])->middleware("verified");
// Route::get('/properties/tenants/houses/{id}',[PlotController::class, 'housestenants'])->middleware("verified");

// Route::get('/properties/houses',[PlotController::class, 'allhouses'])->middleware("verified");

// Route::get('/properties/messages',[PlotController::class, 'messages'])->middleware("verified");

// Route::get('/properties/download',[PlotController::class, 'download'])->middleware("verified");


// Route::get('/properties/users/profile/{id}',[HomeController::class, 'userprofile'])->middleware("password.confirm");


// Route::get('/mail/getmail',[HomeController::class, 'getMails'])->middleware("verified");




// Route::get('/properties/update/bills',[PlotController::class, 'updatebills'])->middleware("verified");
// // Route::get('/properties/update/bills/{id}/{month}',[PlotController::class, 'updatebillsproperty'])->middleware("verified");


// Route::get('/properties/update/bills/{id}/{month}',[PlotController::class, 'updatebillspropertydata'])->middleware("verified");

// Route::get('/properties/update/loadbills/{id}/{month}',[PlotController::class, 'updatebillspropertydataload'])->middleware("verified");

// Route::get('/properties/update/bills/{id}/{month}/{hid}',[PlotController::class, 'updatebillspropertydatahse'])->middleware("verified");



// Route::get('/properties/upload/waterbill',[PlotController::class, 'updatewaterbill'])->middleware("verified");
// // Route::get('/properties/upload/waterbill',[PlotController::class, 'uploadwaterbill'])->middleware("verified");
// // Route::get('/properties/upload/waterbill/{id}/{month}',[PlotController::class, 'uploadwaterbillproperty'])->middleware("verified");

// Route::get('/properties/upload/waterbill/{id}/{month}',[PlotController::class, 'updatewaterbillproperty'])->middleware("verified");

// Route::get('/properties/update/waterbill',[PlotController::class, 'updatewaterbill'])->middleware("verified");
// Route::get('/properties/update/waterbill/{id}/{month}',[PlotController::class, 'updatewaterbillproperty'])->middleware("verified");
// Route::get('/properties/updateload/waterbill/{id}/{month}',[PlotController::class, 'updatewaterbillpropertyload'])->middleware("verified");



// Route::get('/properties/Download/Acknowledgement/{tid}/{hid}/{month}',[PlotController::class, 'downloadacknowledgement'])->middleware("verified");
// Route::get('/properties/Download/Acknowledgement/{pid}/{month}',[PlotController::class, 'downloadacknowledgementforAllPropertySelected'])->middleware("verified");

// Route::get('/properties/download/Reports/TenantsInfo/{id}',[PlotController::class, 'downloadtenantsinfoexcel'])->middleware("verified");
// Route::get('/properties/download/Reports/TenantsInfo',[PlotController::class, 'downloadalltenantsinfoexcel'])->middleware("verified");
// Route::get('/properties/download/Reports/Waterbill/{id}/{month}',[PlotController::class, 'downloadwaterbillexcel'])->middleware("verified");

// Route::get('/properties/downloads/Reports/Waterbill/{id}/{month}',[MailController::class, 'downloadwaterbillexcel']);
// Route::get('/properties/downloads/Reports/Waterbill/{id}/{Year}/{month}',[MailController::class, 'downloadwaterbillperyearexcel']);

// Route::get('/properties/download/Reports/Payments/{id}/{month}',[PlotController::class, 'downloadpaymentsexcel'])->middleware("verified");
// // /properties/download/Reports/Payments/{{$thisproperty->id}}
// // /properties/download/Reports/Payments
// // /properties/Download/Acknowledgement/{{$watermonth}}

// Route::get('/properties/downloads/Acknowledgement/Payments/{filename}',[PlotController::class, 'downloadtenantsacknowledgement'])->middleware("verified");


// Route::get('/properties/View/Reports',[PlotController::class, 'viewreports'])->middleware("verified");
// Route::get('/properties/View/Reports/{type}/{month}',[PlotController::class, 'viewreportstype'])->middleware("verified");
// Route::get('/properties/View/Reports/{type}/{id}/{month}',[PlotController::class, 'viewreportstypeproperty'])->middleware("verified");

// Route::get('/properties/View/Documents',[PlotController::class, 'viewdocuments'])->middleware("verified");
// Route::get('/properties/load/documents',[PlotController::class, 'loaddocuments'])->middleware("verified");

// Route::get('/properties/documents/delete/{id}',[PlotController::class, 'deletedocument'])->middleware("verified");


// Route::get('/properties/view/messages/waterbill',[PlotController::class, 'viewwaterbillmessages'])->middleware("verified");
// Route::get('/properties/view/messages/waterbill/{id}/{month}',[PlotController::class, 'propertyviewwaterbillmessages'])->middleware("verified");
// Route::get('/properties/view/messages/waterbill/{id}/{month}/{hid}',[PlotController::class, 'propertyviewwaterbillmessageshouse'])->middleware("verified");


// Route::get('/properties/view/messages/others',[PlotController::class, 'viewothersmessages'])->middleware("verified");
// Route::get('/properties/view/messages/others/{id}',[PlotController::class, 'propertyviewothersmessages'])->middleware("verified");
// Route::get('/properties/view/messages/others/{id}/{hid}',[PlotController::class, 'propertyviewothersmessageshouse'])->middleware("verified");

// Route::get('/properties/add/waterbill/Others/{month}',[PlotController::class, 'addwaterbillothers'])->middleware("verified");
// Route::get('/properties/add/waterbill/Others/{month}/{tenant}',[PlotController::class, 'addwaterbillotherstenant'])->middleware("verified");

// Route::get('/properties/add/waterbill',[PlotController::class, 'addwaterbill'])->middleware("verified");
// Route::get('/properties/add/waterbill/{id}/{month}',[PlotController::class, 'addwaterbillproperty'])->middleware("verified");
// Route::get('/properties/add/waterbill/{id}/{month}/{house}',[PlotController::class, 'addwaterbillpropertyhouse'])->middleware("verified");


// // Route::get('/properties/send/messagesproperty/{id}/{mode}/{month}',[PlotController::class, 'propertymessage'])->middleware("verified");
// Route::get('/properties/send/messages/{id}/{mode}/{month}',[PlotController::class, 'propertymessagemodemonth'])->middleware("verified");
// Route::get('/properties/send/messages/load/{id}/{mode}/{month}',[PlotController::class, 'propertymessagemodemonthload'])->middleware("verified");

// Route::get('/properties/send/message/Other/{mode}/{month}',[PlotController::class, 'othersmessagemodemonth'])->middleware("verified");



// Route::get('/property/{id}',[PlotController::class, 'index'])->middleware("verified");

// Route::get('/properties/houses/{id}',[PlotController::class, 'houses'])->middleware("verified");

// Route::get('/properties/tenants/{pid}/{hid}',[PlotController::class, 'tenants'])->middleware("verified");

// Route::get('/newproperties',[PlotController::class, 'create'])->middleware("verified");

// Route::get('/properties/newhouse/{id}',[PlotController::class, 'newhouse'])->middleware("verified");

// Route::get('/properties/newtenant',[PlotController::class, 'newtenant'])->middleware("verified");

// Route::get('/properties/updateerrors',[PlotController::class, 'updateerrors'])->middleware("verified");

// Route::get('/properties/Houses/Tenant/{pid}/{hid}/{aid}',[TenantController::class, 'tenanthousesinfo'])->middleware("verified");

// Route::get('/properties/search/tenants/{id}',[TenantController::class, 'searchtenant'])->middleware("verified");
// Route::get('/properties/Assign/Tenant/{id}',[TenantController::class, 'Assigntenant'])->middleware("verified");
// Route::get('/properties/Assign/Tenant/{id}/{tid}',[TenantController::class, 'AssigntenantHse'])->middleware("verified");

// Route::get('/properties/Add/House/Tenant/{id}',[TenantController::class, 'addtenanthouse'])->middleware("verified");
// Route::get('/properties/Add/House/Tenant/{id}/{tid}',[TenantController::class, 'addtenanthousehse'])->middleware("verified");

// Route::get('/properties/Transfer/Tenant/{id}/{tid}',[TenantController::class, 'transfertenanthere'])->middleware("verified");
// Route::get('/properties/Transfer/Tenant/{tid}/{id}/{transfer}',[TenantController::class, 'transfertenanthereid'])->middleware("verified");

// Route::get('/properties/Vacate/Tenant/{tid}/{hid}/{aid}',[TenantController::class, 'vacatetenant'])->middleware("verified");

// Route::get('/properties/Agreement/Tenant/{id}',[TenantController::class, 'viewagreement'])->middleware("verified");

// Route::get('/properties/Reassign/Tenant/{id}/{tid}',[TenantController::class, 'reassigntenant'])->middleware("verified");

// Route::get('/properties/Reassign/Tenant/{tid}/{id}/{hid}',[TenantController::class, 'reassigntenanthse'])->middleware("verified");


// Route::post('/properties/update/bills/save',[AgreementController::class, 'saveupdatebills'])->middleware("verified");

// Route::post('/properties/save/update/bills/submitupdate',[AgreementController::class, 'saveupdatebillssubmitupdate'])->middleware("verified");


// // Route::post('/properties/upload/waterbill/preview',[AgreementController::class, 'uploadwaterbillpreview'])->middleware("verified");

// Route::post('/properties/upload/waterbill/preview',[AgreementController::class, 'updatewaterbillpreview'])->middleware("verified");

// Route::post('/properties/update/waterbill/preview',[AgreementController::class, 'updatewaterbillpreview'])->middleware("verified");


// Route::post('/properties/documents/upload',[AgreementController::class, 'uploaddocuments'])->middleware("verified");



// Route::post('/properties/update/bills/preview',[AgreementController::class, 'updatebillspreview'])->middleware("verified");


// Route::post('/properties/save/waterbill/new',[AgreementController::class, 'savewaterbillnew'])->middleware("verified");

// Route::post('/properties/save/waterbill/upload',[AgreementController::class, 'savewaterbillupload'])->middleware("verified");

// Route::post('/properties/save/waterbill/uploadupdate',[AgreementController::class, 'saveupdatewaterbillupload'])->middleware("verified");

// Route::post('/properties/save/waterbill/update',[AgreementController::class, 'updatewaterbill'])->middleware("verified");


// Route::post('/properties/save/waterbill/othernew',[AgreementController::class, 'savewaterbillothernew'])->middleware("verified");

// Route::post('/properties/save/waterbill/otherupdate',[AgreementController::class, 'otherupdatewaterbill'])->middleware("verified");

// Route::post('/properties/save/waterbill/otherpayment',[AgreementController::class, 'saveotherpayment'])->middleware("verified");

// Route::post('/properties/save/payments/uploadupdate',[AgreementController::class, 'saveupdatepaymentsupload'])->middleware("verified");


// Route::post('/properties/send/messages/others/singlewater',[AgreementController::class, 'sendotherssinglewater'])->middleware("verified");
// Route::post('/properties/send/messages/others/notification',[AgreementController::class, 'sendothersnotification'])->middleware("verified");


// Route::post('/properties/send/messages/singlewater',[AgreementController::class, 'sendsinglewater'])->middleware("verified");
// Route::post('/properties/send/messages/allwater',[AgreementController::class, 'sendallwater'])->middleware("verified");
// Route::post('/properties/send/messages/choosetenant',[AgreementController::class, 'sendchoosetenant'])->middleware("verified");
// Route::post('/properties/send/messages/completedpayments',[AgreementController::class, 'sendcompletedpayments'])->middleware("verified");
// Route::post('/properties/send/messages/summarypayments',[AgreementController::class, 'sendsummarypayments'])->middleware("verified");


// Route::post('/properties/send/message',[AgreementController::class, 'sendmessage'])->middleware("verified");



// Route::post('/properties/tenant/reassign',[AgreementController::class, 'reassigntenant'])->middleware("verified");

// Route::post('/properties/tenant/vacate',[AgreementController::class, 'vacatetenants'])->middleware("verified");



// Route::post('/properties/savetenant/add/house',[AgreementController::class, 'savetenantanotherhse'])->middleware("verified");

// Route::post('/properties/savetenant/transfer/tenant',[AgreementController::class, 'savetenanttransfer'])->middleware("verified");

// Route::post('/properties/updatehouse',[AgreementController::class, 'updatehouse'])->middleware("verified");

// Route::post('/properties/dash/water',[TenantController::class, 'getMonthlyBills'])->middleware("verified");
// Route::post('/properties/dash/payments',[TenantController::class, 'getMonthlyPaymentBills'])->middleware("verified");
// Route::post('/properties/dash/water/prev',[TenantController::class, 'getMonthsPrev'])->middleware("verified");
// Route::post('/properties/dash/payment/prev',[TenantController::class, 'getMonthsPaymentPrev'])->middleware("verified");


// Route::get('/properties/get-details/tenants',[TenantController::class, 'getTenantsDetails'])->middleware("verified");
// Route::get('/properties/get-details/houses',[TenantController::class, 'getHousesDetails'])->middleware("verified");
// Route::get('/properties/get-details/properties',[TenantController::class, 'getPropertyDetails'])->middleware("verified");

// Route::get('/properties/get-blacklisted',[PlotController::class, 'getBlacklistedDetails'])->middleware("verified");

// // Route::post('/properties/mesages/deliveryreports',[AgreementController::class, 'smsDeliveryReports'])->middleware("verified");

// Route::post('/properties/manage/property/delete',[PlotController::class, 'deleteProperty'])->middleware("verified");
// Route::post('/properties/manage/property/save',[PlotController::class, 'saveProperty'])->middleware("verified");
// Route::post('/properties/manage/property/update',[PlotController::class, 'updateProperty'])->middleware("verified");

// Route::post('/properties/manage/house/save',[HouseController::class, 'saveHouse'])->middleware("verified");
// Route::post('/properties/manage/house/delete',[PlotController::class, 'deleteHouse'])->middleware("verified");
// Route::post('/properties/manage/house/update',[PlotController::class, 'updateHouse'])->middleware("verified");

// Route::post('/properties/manage/tenant/save',[TenantController::class, 'saveTenant'])->middleware("verified");
// Route::post('/properties/manage/tenant/delete',[TenantController::class, 'deleteTenant'])->middleware("verified");
// Route::post('/properties/manage/tenant/update',[TenantController::class, 'updateTenant'])->middleware("verified");
// Route::post('/properties/manage/tenant/assign',[TenantController::class, 'assignTenantHouse'])->middleware("verified");
// Route::post('/properties/manage/tenant/addhouse',[TenantController::class, 'assignTenantHouseAdd'])->middleware("verified");
// Route::post('/properties/manage/tenant/reassignhouse',[TenantController::class, 'reassignTenantHouse'])->middleware("verified");
// Route::post('/properties/manage/tenant/vacate',[TenantController::class, 'vacateTenantHouse'])->middleware("verified");



// Route::resource('agency', AgencyController::class)->middleware("password.confirm");

// Route::get('/agencyinfo',[AgencyController::class, 'create'])->middleware("password.confirm");

// Route::resource('plot', PlotController::class)->middleware("password.confirm");

// Route::resource('homeusers', HomeController::class)->middleware("password.confirm");

// Route::resource('house', HouseController::class)->middleware("password.confirm");

// Route::resource('tenant', TenantController::class)->middleware("password.confirm");

// Route::resource('agreement', AgreementController::class)->middleware("password.confirm");

// Route::resource('mails', MailController::class)->middleware("password.confirm");


// https://wagitongaagencies.co.ke/properties/mesages/deliveryreports

// Route::get('/jengatest',[AdminController::class, 'jengaTEST']);

Auth::routes([
	'register'=>false,
	'verify'=>true
]);

// af52ee3f693110e89a296c5e798bd0da19d48cedf39a02621924f6e6c53728e6
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware("verified");

