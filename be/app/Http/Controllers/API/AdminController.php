<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

// use GuzzleHttp\Client;

use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    //previous months
    public static function getPreviousMonths($currentym){
        // currentdate
        $currentdate= date('Y n');
        if ($currentym==0) {
            $currentdate= date('Y n');
        }

        $thisyearly=Property::getYearDateDash($currentdate);
        
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $startyearnow=$endyear-1;
        // $currentdate= date('Y n');
        // $currentdatename= date('M, Y');
        $endmonth=12;
        $paymentmonths='';
        $previousmonths= array();
        $selectedmonth=0;
        $sno=0;
        for ($i=$endyear; $i >= $startyear; $i--) { 
            if ($i==2019) {
                $startmonth=7;
            }
            else{
                $startmonth=1;
            }

            if ($i==$endyear) {
                $endmonth=date('n');
            }
            else{
                $endmonth=12;
            }
            for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                $month= $i.' '.$m;
                if($month == $currentdate){
                    $selectedmonth=$sno;
                }
                $monthname=Property::getMonthDateMonthPrevious($month);
                $monthly=Property::getMonthDateDash($month);
                $yearly=Property::getYearDateDash($month);
                $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                $previousmonths[] = array(
                    'sno'=>$sno,
                    'month' => $month,
                    'monthname' => $monthname,
                    'monthly' => $monthly,
                    'yearly' => $yearly,
                    'currentdate' => $currentdate
                );
                $sno++;
            }
        }

        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonth' =>$selectedmonth,
            'totals' => $sno,
            'yearly' =>$thisyearly,
            'message'=>'Found '.($sno).' Months',
        ]);
    }
    

    //get waterbill
    public static function getWaterbill($month){
        $propertyinfo = Property::where('Waterbill','Monthly')->get();
        // $month=$request->input('month');
        $curmonth='';
        if ($month==0) {
            $month=date('Y n');
            $curmonth=date('M, Y');
        }
        else{
            $curmonth=Property::getMonthDate($month);
        }

        $yearly=Property::getYearDateDash($month);

        $yearlyup='';
        $yearlydown='';

        if($yearly==date('Y')){
            $yearlyup='';
            $yearlydown=$yearly-1;
        }
        // else if($yearly==(date('Y') - 1)){
        //     $yearlyup=$yearly+1;
        //     $yearlydown=$yearly-1;
        // }
        else{
            $yearlyup=$yearly+1;
            $yearlydown=$yearly-1;
        }

        if($yearly > date('Y')){
            $yearlyup='';
            $yearlydown='';
        }

        if($yearly <= 2019){
            $yearlyup=$yearly+1;
            $yearlydown='';
        }

        
        $waterbill= array();
        $sno=0;
        
        // return $month;
        foreach ($propertyinfo as $properties) { 
            $totals=(Property::getTotalTotal(Property::decryptText($properties->id),$month))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$month));
            $monthdate= Property::getLastMonthdate($month);
            $lastmonth= Property::getLastMonth($month,$monthdate);
            $thisbilltotals=(Property::getTotalTotal(Property::decryptText($properties->id),$lastmonth))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$lastmonth));
            
            
            $thismonthname=Property::dateToMonthNameMonth($month);

            $nextmonthdate=Property::getNextMonthdate($month);
            $nextmonth=Property::getNextMonths($nextmonthdate);
            $nextmonthname=Property::dateToMonthNameMonth($nextmonth);
                $waterbill[] = array(
                    'sno'=>$sno,
                    'id' => $properties->id,
                    'plotcode' => $properties->Plotcode,
                    'plotname' => $properties->Plotname,
                    'curmonth' => $curmonth,
                    'totalbillshse' => Property::getTotalWaterBillsHse(Property::decryptText($properties->id),$month),
                    'totalhouseshse' =>Property::getTotalHousesHse(Property::decryptText($properties->id)),
                    'totals' =>$totals,
                    'thismonthname' =>$thismonthname,
                    'nextmonthname' =>$nextmonthname,
                    'thisbilltotals' => $thisbilltotals,
                    'totalunits' => Property::getTotalUnits(Property::decryptText($properties->id),$month),
                    'thisbilltotalunits' => Property::getTotalUnits(Property::decryptText($properties->id),$lastmonth),
                    'totalbillsmsghseonce' =>Property::getTotalWaterMsgSentOnceHse(Property::decryptText($properties->id),$month),
                    'totalbillsmsghsetwice' =>Property::getTotalWaterMsgSentTwiceHse(Property::decryptText($properties->id),$month),
                    'totalbillsmsghsethrice' =>Property::getTotalWaterMsgSentThriceHse(Property::decryptText($properties->id),$month),
                    'totalbillsmsghse' => Property::getTotalWaterMsgHse(Property::decryptText($properties->id),$month),
                    'month' => $month
                );
                $sno++;
            // }
        }


        return response()->json([
            'status'=>200,
            'waterbill'=>$waterbill,
            'totals' => $sno,
            'currentdate' => $month,
            'currentmonthname'=> $curmonth,
            'yearly' =>$yearly,
            'yearlyup' =>$yearlyup,
            'yearlydown' =>$yearlydown,
            'message'=>'Found '.($sno).' Properties',
        ]);
    }

    //get payments
    public static function getPayments($month){
        $propertyinfo = Property::all();
        // $month=$request->input('month');
        $curmonth='';
        if ($month==0) {
            $month=date('Y n');
            $curmonth=date('M, Y');
        }
        else{
            $curmonth=Property::getMonthDate($month);
        }

        $yearly=Property::getYearDateDash($month);

        $yearlyup='';
        $yearlydown='';

        if($yearly==date('Y')){
            $yearlyup='';
            $yearlydown=$yearly-1;
        }
        // else if($yearly==(date('Y') - 1)){
        //     $yearlyup=$yearly+1;
        //     $yearlydown=$yearly-1;
        // }
        else{
            $yearlyup=$yearly+1;
            $yearlydown=$yearly-1;
        }

        if($yearly > date('Y')){
            $yearlyup='';
            $yearlydown='';
        }

        if($yearly <= 2019){
            $yearlyup=$yearly+1;
            $yearlydown='';
        }

        
        $waterbill= array();
        $sno=0;
        
        foreach ($propertyinfo as $properties) { 
            $totals=(Property::getTotalTotal(Property::decryptText($properties->id),$month))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$month));
            $rent=Property::MonthlyRent(Property::decryptText($properties->id),$month);
            $waterbillb=Property::MonthlyWaterbill(Property::decryptText($properties->id),$month);
            $garbage=Property::MonthlyGarbage(Property::decryptText($properties->id),$month);
            $lease=Property::MonthlyLease(Property::decryptText($properties->id),$month);
            $kplc=Property::MonthlyKPLC(Property::decryptText($properties->id),$month);
            $hsedeposit=Property::MonthlyHseDeposit(Property::decryptText($properties->id),$month);
            $water=Property::MonthlyWater(Property::decryptText($properties->id),$month);

            $MonthlyArrears=Property::MonthlyArrears(Property::decryptText($properties->id),$month);
            $MonthlyExcess=Property::MonthlyExcess(Property::decryptText($properties->id),$month);
            $MonthlyRefund=Property::MonthlyRefund(Property::decryptText($properties->id),$month);
            $MonthlyBilled=Property::MonthlyBilled(Property::decryptText($properties->id),$month);
            $MonthlyPaid=Property::MonthlyPaid(Property::decryptText($properties->id),$month);
            $MonthlyBalance=Property::MonthlyBalance(Property::decryptText($properties->id),$month);

            $MonthlyPaidEquity = Property::MonthlyPaidEquity(Property::decryptText($properties->id),$month);
            $MonthlyPaidCoop = Property::MonthlyPaidCoop(Property::decryptText($properties->id),$month);
            $MonthlyPaidOthers = Property::MonthlyPaidOthers(Property::decryptText($properties->id),$month);
            $MonthlyPaidUploaded = Property::MonthlyPaidUploaded(Property::decryptText($properties->id),$month);
            $MontlyOthersPaid= ($MonthlyPaidOthers+$MonthlyPaidUploaded);
            $waterbill[] = array(
                'sno'=>$sno,
                'id' => $properties->id,
                'plotcode' => $properties->Plotcode,
                'plotname' => $properties->Plotname,
                'curmonth' => $curmonth,
                'rent' => $rent,
                'waterbill' => $waterbillb,
                'garbage' => $garbage,
                'lease' => $lease,
                'kplc' => $kplc,
                'hsedeposit' => $hsedeposit,
                'water' => $water,
                'MonthlyArrears' =>$MonthlyArrears,
                'MonthlyExcess' =>$MonthlyExcess,
                'MonthlyRefund' =>$MonthlyRefund,
                'MonthlyBilled' =>$MonthlyBilled,
                'MonthlyPaid' =>$MonthlyPaid,
                'MonthlyBalance' =>$MonthlyBalance,      
                'MonthlyPaidEquity' =>$MonthlyPaidEquity,
                'MonthlyPaidCoop' =>$MonthlyPaidCoop,
                'MontlyOthersPaid'  =>$MontlyOthersPaid,
                'totalbillshse' => Property::getTotalBillsHse(Property::decryptText($properties->id),$month),
                'totalhouseshse' =>Property::getTotalHousesHse(Property::decryptText($properties->id)),
                'totals' =>$totals,
                'totalunits' => Property::getTotalUnits(Property::decryptText($properties->id),$month),
                'totalbillsmsghse' => Property::getTotalBillsMsgHse(Property::decryptText($properties->id),$month),
                'totalbillsmsghseonce' =>Property::getTotalBillsMsgSentOnceHse(Property::decryptText($properties->id),$month),
                'totalbillsmsghsetwice' =>Property::getTotalBillsMsgSentTwiceHse(Property::decryptText($properties->id),$month),
                'totalbillsmsghsethrice' =>Property::getTotalBillsMsgSentThriceHse(Property::decryptText($properties->id),$month),
                'month' => $month
            );
            $sno++;
        }


        return response()->json([
            'status'=>200,
            'payments'=>$waterbill,
            'totals' => $sno,
            'currentdate' => $month,
            'currentmonthname'=> $curmonth,
            'yearly' =>$yearly,
            'yearlyup' =>$yearlyup,
            'yearlydown' =>$yearlydown,
            'message'=>'Found '.($sno).' Properties',
        ]);
    }


    public static function getDashStats($month){
        // Properties : 0 Houses : 0 Tenants : 0
        // Rent : 0 Waterbill : 0 Garbage : 0
        // Paid : 0 Billed : 0 Balance : 0
        // Occupied : 0 Vacant : 0 Doubles : 
        // 'Assigned','New','Vacated','Reassigned','Transferred','Other'

        $tenants=0;
        $assignedtenants = Tenant::where('Status','Assigned')->get()->count();
        $reassignedtenants = Tenant::where('Status','Reassigned')->get()->count();
        $transferredtenants = Tenant::where('Status','Transferred')->get()->count();
        $othertenants = Tenant::where('Status','Other')->get()->count();
        $tenants=($assignedtenants)+($reassignedtenants)+($transferredtenants)+($othertenants);

        $properties = Property::all()->count();

        $houses = House::all()->count();
        // 'Occupied','Vacant','Maintainance','Caretaker'
        $vacanthouses = House::where('Status','Vacant')->get()->count();
        $occupiedhouses = House::where('Status','Occupied')->get()->count();
        $doublehouses = 0;
        $alltenantinfo = Tenant::orderByDesc('id')->get();

        $doubless= array();
        foreach ($alltenantinfo as $tenant) {
            $tenantid=$tenant->id;
            $totalhouses=Property::tenantHousesAssigned($tenantid);
            if($totalhouses>1){
                $doublehouses++;
                $doubless[] = array(
                    'tenantid'=>$tenantid,
                    'totalhouses' => $totalhouses,
                );
            }
        }
        
        $stats= array();
        $stats[] = array(
            'houses'=>$houses,
            'properties' => $properties,
            'tenants' => $tenants,
            'vacanthouses' => $vacanthouses,
            'occupiedhouses' => $occupiedhouses,
            'doublehouses' => $doublehouses,
        );

        return response()->json([
            'status'=>200,
            'stats'=>$stats,
            'doubless' =>$doubless,
            'message'=>'Found Stats',
        ]);
    }

    public static function getDashboardStats($type,$month){
        // Properties : 0 Houses : 0 Tenants : 0
        // Rent : 0 Waterbill : 0 Garbage : 0
        // Paid : 0 Billed : 0 Balance : 0
        // Occupied : 0 Vacant : 0 Doubles : 
        // 'Assigned','New','Vacated','Reassigned','Transferred','Other'
        if ($month==0) {
            $month=date('Y n');
        }
        // if($type=="property"){
        //     $properties = Property::all()->count();
        //     $houses = House::all()->count();
        //     $tenants=Tenant::all()->count();
        //     $vacatedornewtenants = Tenant::where('Status','Vacated')->orWhere('Status','New')->get()->count();
        //     $vacanthouses = DB::table('houses_vacant')->get()->count();
        //     $occupiedhouses = DB::table('houses_occupied_totals')->sum('Occupied');
        //     $doublehouses = 0;
        //     $alltenantinfo = DB::table('tenants_with_double_houses')->get();
        //     $doubless= array();
        //     foreach ($alltenantinfo as $tenant) {
        //         $tenantid=$tenant->id;
        //         $totalhouses=Property::tenantHousesAssigned(Property::decryptText($tenantid));
        //         if($totalhouses>1){
        //             $doublehouses++;
        //             $housesdata = Property::tenantHousesOccupiedDataOnly(Property::decryptText($tenantid));
        //             $doubless[] = array(
        //                 'tenantid'=>$tenantid,
        //                 'totalhouses' => $totalhouses,
        //                 'housesdata'=>$housesdata,
        //             );
        //         }
        //     }
                

        //     return response()->json([
        //         'status'=>200,
        //         'totalproperties' =>$properties,
        //         'totalhouses' =>$houses,
        //         'totaltenants' =>($tenants-$vacatedornewtenants),
        //         'totalvacanthouses' =>$vacanthouses,
        //         'totaloccupiedhouses' =>$occupiedhouses,
        //         'totaldoublehouses' =>$doublehouses,
        //         'doublehouses' =>$doubless,
        //         'message'=>'Found Property Stats',
        //     ]);
        // }
        if($type=="propertymonthly"){
            //Get Geneneraly Details
            $properties = Property::all()->count();
            $houses = House::all()->count();
            $tenants=Tenant::all()->count();
            $vacatedornewtenants = Tenant::where('Status','Vacated')->orWhere('Status','New')->get()->count();
            $vacanthouses = DB::table('houses_vacant')->get()->count();
            $occupiedhouses = DB::table('houses_occupied_totals')->sum('Occupied');
            $doublehouses = 0;
            $alltenantinfo = DB::table('tenants_with_double_houses')->get();
            $doubless= array();
            foreach ($alltenantinfo as $tenant) {
                $tenantid=$tenant->id;
                $totalhouses=Property::tenantHousesAssigned(Property::decryptText($tenantid));
                if($totalhouses>1){
                    $doublehouses++;
                    $housesdata = Property::tenantHousesOccupiedDataOnly(Property::decryptText($tenantid));
                    $doubless[] = array(
                        'tenantid'=>$tenantid,
                        'totalhouses' => $totalhouses,
                        'housesdata'=>$housesdata,
                    );
                }
            }

            //get Selected Month Details
            $propertiesmonthly =   Property::getTotalPropertyMonthly($month);
            $housesmonthly =       Property::getTotalHousesMonthly($month);
            $tenantsmonthly=       Property::getTotalTenantsMonthly($month);
            $vacatedornewtenantsmonthly = Property::getTotalTenantsVacatedOrNewMonthly($month);
            $vacanthousesmonthly = Property::getTotalVacantHousesMonthly($month);
            $occupiedhousesmonthly = Property::getTotalHousesOccupiedMonthly($month);
            $doublehousesmonthly = 0;
            $alltenantinfomonthly = DB::table('tenants_with_double_houses')->get();
            $doublessmonthly= array();
            foreach ($alltenantinfomonthly as $tenantmonthly) {
                $tenantid=$tenantmonthly->id;
                $totalhousesmonthly=Property::tenantHousesAssigned(Property::decryptText($tenantid));
                if($totalhousesmonthly>1){
                    // $doublehouses++;
                    $housesdatamonthly = Property::tenantHousesOccupiedDataOnly(Property::decryptText($tenantid));
                    foreach($housesdatamonthly as $house){
                        $dhid=$house['house'];
                        $dpid=$house['pid'];

                        if($vacated=Agreement::query()
                                ->where('tenant','=',Property::decryptText($tenantid))
                                ->where('plot','=',Property::decryptText($dpid))
                                ->where('house','=',$dhid)
                                ->where('MonthAssigned','=',$month)->get()->first()){
                            $doublehousesmonthly++;
                        }
                    }
                    $doublessmonthly[] = array(
                        'tenantid'=>$tenantid,
                        'totalhouses' => $totalhousesmonthly,
                        'housesdata'=>$housesdatamonthly,
                    );
                }
            }
                

            return response()->json([
                'status'=>200,
                'totalproperties' =>$properties,
                'totalhouses' =>$houses,
                'totaltenants' =>($tenants-$vacatedornewtenants),
                'totalvacanthouses' =>$vacanthouses,
                'totaloccupiedhouses' =>$occupiedhouses,
                'totaldoublehouses' =>$doublehouses,
                'doublehouses' =>$doubless,
                'totalpropertiesmonthly' =>$propertiesmonthly,
                'totalhousesmonthly' =>$housesmonthly,
                'totaltenantsmonthly' =>($tenantsmonthly),
                'totalvacatedtenantsmonthly' =>($vacatedornewtenantsmonthly),
                'totalvacanthousesmonthly' =>$vacanthousesmonthly,
                'totaloccupiedhousesmonthly' =>$occupiedhousesmonthly,
                'totaldoublehousesmonthly' =>$doublehousesmonthly,
                'doublehousesmonthly' =>$doublessmonthly,
                'message'=>'Found Property Stats',
            ]);
        }

        if($type=="bills"){
            $rent=Property::MonthlyRent('all',$month);
            $garbage=Property::MonthlyGarbage('all',$month);
            $waterbill=Property::MonthlyWaterbill('all',$month);
            $waterd=Property::MonthlyWater('all',$month);
            $kplcd=Property::MonthlyKPLC('all',$month);
            $rentd=Property::MonthlyHseDeposit('all',$month);
            $refund=Property::MonthlyRefund('all',$month);
            $lease=Property::MonthlyLease('all',$month);
            $arrears=Property::MonthlyArrears('all',$month);
            $excess=Property::MonthlyExcess('all',$month);
            $billed=Property::MonthlyBilled('all',$month);
            $paid=Property::MonthlyPaid('all',$month);
            $balance=Property::MonthlyBalance('all',$month);
            $forwarded=($arrears-$excess);
            
            

            return response()->json([
                'status'=>200,
                'totalrent' =>$rent,
                'totalgarbage' =>$garbage,
                'totalwaterbill' =>$waterbill,
                'totalwaterd' =>$waterd,
                'totalkplcd' =>$kplcd,
                'totalrentd' =>$rentd,
                'totalrefund' =>$refund,
                'totallease' =>$lease,
                'totalarrears' =>$arrears,
                'totalexcess' =>$excess,
                'totalforwaded' =>$forwarded,
                'totalbilled' =>$billed,
                'totalpaid' =>$paid,
                'totalbalance' =>$balance,
                'message'=>'Found billed Stats',
            ]);
        }

        // else if($type=="properties"){
        //     $properties = Property::all()->count();
        //     return response()->json([
        //         'status'=>200,
        //         'totalproperties' =>$properties,
        //         'message'=>'Found Properties Stats',
        //     ]);
        // }

        // else if($type=="houses"){
        //     $houses = House::all()->count();
        //     return response()->json([
        //         'status'=>200,
        //         'totalhouses' =>$houses,
        //         'message'=>'Found Houses Stats',
        //     ]);
        // }

        // else if($type=="tenants"){
        //     // $tenants=0;
        //     $tenants=Tenant::all()->count();
        //     $vacatedornewtenants = Tenant::where('Status','Vacated')->orWhere('Status','New')->get()->count();
        //     // $alltenants=Tenant::where('Status','Assigned')->
        //     //     orWhere('Status','Reassigned')->
        //     //     orWhere('Status','Transferred')->
        //     //     orWhere('Status','Other')->get()->count();
        //     return response()->json([
        //         'status'=>200,
        //         'totaltenants' =>($tenants-$vacatedornewtenants),
        //         'message'=>'Found Tenants Stats',
        //     ]);
        // }

        // else if($type=="vacanthouses"){
        //     $vacanthouses = DB::table('houses_vacant')->get()->count();
        //     // $vacanthouses = House::where('Status','Vacant')->get()->count();
        //     return response()->json([
        //         'status'=>200,
        //         'totalvacanthouses' =>$vacanthouses,
        //         'message'=>'Found Vacant Houses Stats',
        //     ]);
        // }

        // else if($type=="occupiedhouses"){
        //     $occupiedhouses = DB::table('houses_occupied_totals')->sum('Occupied');
        //     // $occupiedhouses = House::where('Status','Occupied')->get()->count();
        //     return response()->json([
        //         'status'=>200,
        //         'totaloccupiedhouses' =>$occupiedhouses,
        //         'message'=>'Found Occupied Houses Stats',
        //     ]);
        // }

        // else if($type=="doublehouses"){
        //     $doublehouses = 0;
        //     $alltenantinfo = DB::table('tenants_with_double_houses')->get();
        //     // $alltenantinfo = Tenant::orderByDesc('id')->get();

        //     $doubless= array();
        //     foreach ($alltenantinfo as $tenant) {
        //         $tenantid=$tenant->id;
        //         $totalhouses=Property::tenantHousesAssigned(Property::decryptText($tenantid));
        //         if($totalhouses>1){
        //             $doublehouses++;
        //             $housesdata = Property::tenantHousesOccupiedDataOnly(Property::decryptText($tenantid));
        //             $doubless[] = array(
        //                 'tenantid'=>$tenantid,
        //                 'totalhouses' => $totalhouses,
        //                 'housesdata'=>$housesdata,
        //             );
        //         }
        //     }

        //     return response()->json([
        //         'status'=>200,
        //         'totaldoublehouses' =>$doublehouses,
        //         'doublehouses' =>$doubless,
        //         'message'=>'Found Double Houses Stats',
        //     ]);
        // }

        // else if($type=="rent"){
        //     $rent=Property::MonthlyRent('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalrent' =>$rent,
        //         'message'=>'Found Total Rent Stats',
        //     ]);
        // }

        // else if($type=="garbage"){
        //     $garbage=Property::MonthlyGarbage('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalgarbage' =>$garbage,
        //         'message'=>'Found Total Garbage Stats',
        //     ]);
        // }

        // else if($type=="waterbill"){
        //     $waterbill=Property::MonthlyWaterbill('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalwaterbill' =>$waterbill,
        //         'message'=>'Found Total Waterbill Stats',
        //     ]);
        // }

        // else if($type=="waterd"){
        //     $waterd=Property::MonthlyWater('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalwaterd' =>$waterd,
        //         'message'=>'Found Total Water Deposit Stats',
        //     ]);
        // }
       
        // else if($type=="kplcd"){
        //     $kplcd=Property::MonthlyKPLC('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalkplcd' =>$kplcd,
        //         'message'=>'Found Total KPLC Deposit Stats',
        //     ]);
        // }

        // else if($type=="rentd"){
        //     $rentd=Property::MonthlyHseDeposit('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalrentd' =>$rentd,
        //         'message'=>'Found Total HseDeposit Stats',
        //     ]);
        // }

        // else if($type=="refund"){
        //     $refund=Property::MonthlyRefund('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalrefund' =>$refund,
        //         'message'=>'Found Total Refund  Stats',
        //     ]);
        // }
        // else if($type=="arrears"){
        //     $arrears=Property::MonthlyArrears('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalarrears' =>$arrears,
        //         'message'=>'Found Total Arrears  Stats',
        //     ]);
        // }
        
        // else if($type=="excess"){
        //     $excess=Property::MonthlyExcess('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalexcess' =>$excess,
        //         'message'=>'Found Total Excess  Stats',
        //     ]);
        // }

        // else if($type=="billed"){
        //     $billed=Property::MonthlyBilled('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalbilled' =>$billed,
        //         'message'=>'Found Total Billed  Stats',
        //     ]);
        // }

        // else if($type=="paid"){
        //     $paid=Property::MonthlyPaid('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalpaid' =>$paid,
        //         'message'=>'Found Total Paid  Stats',
        //     ]);
        // }

        // else if($type=="balance"){
        //     $balance=Property::MonthlyBalance('all',$month);
        //     return response()->json([
        //         'status'=>200,
        //         'totalbalance' =>$balance,
        //         'message'=>'Found Total Balance  Stats',
        //     ]);
        // }
    }

    
    public static function getDashboardInsights($month){
        $propertyinfo = Property::all();
        $propertyinfow = Property::where('Waterbill','Monthly')->get();
        $sno=0;
        
        // foreach ($propertyinfo as $properties) { 
        //     $rent=Property::MonthlyRent($properties->id,$month);
        //     $waterbill[] = array(
        //         'sno'=>$sno,
        //         'id' => $properties->id,
        //         'plotcode' => $properties->Plotcode,
        //         'plotname' => $properties->Plotname,
        //         'rent'     =>$rent,
        //         'totalhouseshse' =>Property::getTotalHousesHse($properties->id)
        //     );
        //     $sno++;
        // }

        $rents= array();
        $properts= array();
        $propertsw= array();
        $propertsr= array();
        $propertsd= array();
        $garbages= array();
        $waterbills= array();
        $waterbillsw= array();
        $leases= array();
        $kplcs= array();
        $hsedeposits= array();
        $waters= array();
        $rentbins= array();
        $rentbinsw= array();
    
        $snn=0;
        if ($month==0) {
            $month=date('Y n');
        }
        foreach ($propertyinfo as $properties) { 
            $rent=Property::MonthlyRent(Property::decryptText($properties->id),$month);
            $waterbill=Property::MonthlyWaterbill(Property::decryptText($properties->id),$month);
            $garbage=Property::MonthlyGarbage(Property::decryptText($properties->id),$month);
            $lease=Property::MonthlyLease(Property::decryptText($properties->id),$month);
            $kplc=Property::MonthlyKPLC(Property::decryptText($properties->id),$month);
            $hsedeposit=Property::MonthlyHseDeposit(Property::decryptText($properties->id),$month);
            $water=Property::MonthlyWater(Property::decryptText($properties->id),$month);

            $rent=round($rent,2);
            $waterbill=round($waterbill,2);
            $garbage=round($garbage,2);
            $lease=round($lease,2);
            $kplc=round($kplc,2);
            $hsedeposit=round($hsedeposit,2);
            $water=round($water,2);
            
            $properts[$snn]=$properties->Plotcode;

            // $rents[$snn] = $rent;
            

            // $garbages[$snn] = $garbage;
            // $waterbills[$snn] = $waterbill;
            
            $rentbins[$snn] = $rent + $garbage;
            $rentw= $rent + $garbage;

            $totalbills=$rent + $garbage + $waterbill;
            if($totalbills > '0'){
                $propertsr[]=$properties->Plotcode;
                $rents[] = $rent;
                $garbages[] = $garbage;
                $waterbills[] = $waterbill;
            }

            // $leases[$snn] = $lease;
            // $kplcs[$snn] = $kplc;
            // $hsedeposits[$snn] = $hsedeposit;
            // $waters[$snn] = $water;

            $totaldeposits=$kplc + $hsedeposit + $water + $lease;
            if($totaldeposits > '0'){
                $propertsd[]=$properties->Plotcode;
                $kplcs[] = $kplc;
                $hsedeposits[] = $hsedeposit;
                $waters[] = $water;
                $leases[] = $lease;
            }
            
            $snn++;
        }


        foreach ($propertyinfow as $propertiesw) {
            $waterbillw=Property::MonthlyWaterbill(Property::decryptText($propertiesw->id),$month);
            if($waterbillw > 0){
                $waterbillw=round($waterbillw,2);
                $propertsw[]=$propertiesw->Plotcode;
                $waterbillsw[] = $waterbillw;
            }
        }

        $insights= array();
        $monthname=Property::getMonthDateMonthPrevious($month);
        $insights[] = array(
            'rent'=>$rents,
            'rentbins'=>$rentbins,
            'rentbinsw'=>$rentbinsw,
            'waterbill'=>$waterbills,
            'waterbillw'=>$waterbillsw,
            'lease'=>$leases,
            'hsedeposit'=>$hsedeposits,
            'water'=>$waters,
            'kplc'=>$kplcs,
            'properts'=>$properts,
            'propertsw'=>$propertsw,
            'propertsr'=>$propertsr,
            'propertsd'=>$propertsd,
            'garbages'=>$garbages,
            'monthname' =>$monthname
        );

        return response()->json([
            'status'=>200,
            'insights'=>$insights,
        ]);
    }

    public static function getDashboardInsightsRents($month){
        $propertyinfo = Property::all();
        $rents= array();
        $properts= array();
        $propertsr= array();
        $rentbins= array();
        $rentbinsw= array();
    
        $snn=0;
        if ($month==0) {
            $month=date('Y n');
        }
        foreach ($propertyinfo as $properties) { 
            $rent=Property::MonthlyRent(Property::decryptText($properties->id),$month);
            $garbage=Property::MonthlyGarbage(Property::decryptText($properties->id),$month);
            
            $rent=round($rent,2);
            $garbage=round($garbage,2);
            
            $properts[$snn]=$properties->Plotcode;

            $rents[$snn] = $rent;
            

            $garbages[$snn] = $garbage;
            
            $rentbins[$snn] = $rent + $garbage;
            $rentw= $rent + $garbage;
            if($rentw > '0'){
                $propertsr[]=$properties->Plotcode;
                $rentbinsw[] = $rent + $garbage;
            }

            $snn++;
        }

        // foreach ($propertyinfo as $properties) { 


        // }


        $insights= array();
        $monthname=Property::getMonthDateMonthPrevious($month);
        $insights[] = array(
            'rent'=>$rents,
            'rentbins'=>$rentbins,
            'rentbinsw'=>$rentbinsw,
            'properts'=>$properts,
            'propertsr'=>$propertsr,
            'garbages'=>$garbages,
            'monthname' =>$monthname
        );
        return response()->json([
            'status'=>200,
            'insights'=>$insights,
        ]);
    }
    
    public static function setNewTenantBillsPageInitial($month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            $currentdate= date('Y n');
            if($month=='all'){
                $month=$currentdate;
            }

            $houseinfo=Agreement::where('Month',0)->where('MonthAssigned',$month)->get();
            // $houseinfo=House::all();

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= '';
                // if($id=='All'){
                    $hid= $result['house'];
                // }
                // else{
                //     $hid= $result['id'];
                // }
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tid)), 0);
                // $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),Property::decryptText($tid),$month);
                $housename=Property::getHouseName(Property::decryptText($hid));
                
                $tenantname='';
                $tenantfname='';
                if (Property::decryptText($tid)=='') {
                    $tenant='Vacated';
                    $tenantname='House Vacant';
                    $tenantfname='House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
                    $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
                }

                if($agreements=Agreement::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',0)->where('MonthAssigned',$month)->get()->first()){
                    if($waterbills=Payment::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
                        $tenantid=$waterbills->tenant;
                        $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                        $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));

                        
                        $rent1= $waterbills->Rent;
                        $garbage1= $waterbills->Garbage;
                        $waterbill1= $waterbills->Waterbill;
                        $kplc1= $waterbills->KPLC;
                        $hsedeposit1= $waterbills->HseDeposit;
                        $water1= $waterbills->Water;
                        $lease1= $waterbills->Lease;
                        
                        $arrears1= $waterbills->Arrears;
                        $excess1= $waterbills->Excess;
                        $total=($rent1+$garbage1+$waterbill1+$kplc1+$hsedeposit1+$water1+$lease1+$arrears1)-$excess1;

                        $watermessage_data[] = array(
                            'paymentid' => $waterbills->id,
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tenantid,
                            'Arrears' => $waterbills->Arrears,
                            'Excess' => $waterbills->Excess,
                            'Rent' => $waterbills->Rent,
                            'Garbage' => $waterbills->Garbage,
                            'Waterbill' => $waterbills->Waterbill,
                            'KPLC' => $waterbills->KPLC,
                            'HseDeposit' => $waterbills->HseDeposit,
                            'Water' => $waterbills->Water,
                            'Lease' => $waterbills->Lease,
                            'saved' => 'Yes',
                            'total' => $total,
                            'housename'=>$housename,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'month' => $month,
                            'monthname'=>$monthnames,
                            'created_at' => $waterbills->created_at
                        );

                    }
                    else{
                        $watermessage_data[] = array(
                            'paymentid' => '',
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tid,
                            'Arrears' => 0.00,
                            'Excess' => 0.00,
                            'Rent' => 0.00,
                            'Garbage' => 0.00,
                            'Waterbill' => 0.00,
                            'KPLC' => 0.00,
                            'HseDeposit' => 0.00,
                            'Water' => 0.00,
                            'Lease' => 0.00,
                            'saved' =>'No',
                            'total' => 0.00,
                            'housename'=>$housename,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'month' => $month,
                            'monthname'=>$monthnames,
                            'created_at' => ''
                        );
                    }

                    $sno1++;
                }
                

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }

        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);


    }

    public static function setWaterbillPageInitial(){
        
        $properties = Property::all();

        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }

        $thisproperty='';
        

        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $currentdate= date('Y n');
        $endmonth=12;
        $previousmonths= array();
        $sno=0;
        for ($i=$endyear; $i >= $startyear; $i--) { 
            if ($i==2019) {
                $startmonth=7;
            }
            else{
                $startmonth=1;
            }

            if ($i==$endyear) {
                $endmonth=date('n');
            }
            else{
                $endmonth=12;
            }
            for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                $month= $i.' '.$m;
                $monthname=Property::getMonthDateMonthPrevious($month);
                $monthly=Property::getMonthDateDash($month);
                $yearly=Property::getYearDateDash($month);
                $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                $previousmonths[] = array(
                    'sno'=>$sno,
                    'month' => $month,
                    'monthname' => $monthname,
                    'monthly' => $monthly,
                    'yearly' => $yearly,
                    'currentdate' => $currentdate
                );
                $sno++;
            }
        }
        
        
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'totals' => 0,
            'message'=>'Found Properties & Months',
        ]);
    }
    

    public static function setWaterbillPage($id, $month){
        
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else if($id==0){
                $PinitialId=Property::where('Waterbill','Monthly')->get()->first();
                $thisproperty=Property::findOrFail(Property::decryptText($PinitialId->id));
                $houseinfo=House::where('Plot',Property::decryptText($PinitialId->id))->get();
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();

            foreach($houseinfo as $result){
                $hid= $result['id'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $housename=$result['Housename'];
                $monthassigned=Property::checkCurrentTenantMonthAssigned(Property::decryptText($hid));
                $checkcurrenttenantstatus= $month >= $monthassigned;
                // $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),$tid,$month);
                $waterid=Property::checkCurrentHouseBill(Property::decryptText($hid),$month);
                
                $waterbills=Water::where('id',$waterid)->get()->first();
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                if($waterbills=Water::where('id',$waterid)->get()->first()){
                    //display the current waterbill with house and month
                    if($checkcurrenttenantstatus){
                        $tenantid=Property::checkCurrentTenantMonthAssignedTenant(Property::decryptText($hid));
                        // $tenantid=$waterbills->tenant;
                        $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                        $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                    }
                    else{
                        $tenantid=$waterbills->tenant;
                        $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                        $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                        // $tenantid='';
                        // $tenantname='House Vacant';
                        // $tenantfname='House Vacant';
                    }

                    $prevbill=($waterbills->Previous!='')?$waterbills->Previous:'';
                    $curbill=($waterbills->Current!='')?$waterbills->Current:'';
                    $saved_bill=$prevbill.':'.$curbill;
                    $loading_bill=$prevbill.':'.$curbill;
                    $watermessage_data[] = array(
                        'pid' => $id,
                        'id' => $hid,
                        'tid' => $tenantid,
                        'checkcurrenttenantstatus'=>$checkcurrenttenantstatus,
                        'previous' => ($waterbills->Previous!='')?$waterbills->Previous:'',
                        'current' => ($waterbills->Current!='')?$waterbills->Current:'',
                        'saved_previous' => '',
                        'saved_current' => '',
                        'saved' =>'No',
                        'present' =>'Yes',
                        'saved_bill' =>$saved_bill,
                        'loading_bill' =>$loading_bill,
                        'prevmatches' =>'Yes',
                        'cost' => $waterbills->Cost,
                        'units' => $waterbills->Units,
                        'total' => $waterbills->Total,
                        'total_os' => $waterbills->Total_OS,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'waterid' => $waterid,
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'created_at' => $waterbills->created_at
                    );
                }
                else{
                    $monthdate= Property::getLastMonthdate($month);
                    $previousmonth= Property::getLastMonth($month,$monthdate);

                    // $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),$tid,$month);
                
                    
                    if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Month',$previousmonth)->get()->first()){
                        
                        if($checkcurrenttenantstatus){
                            $tenantid=Property::checkCurrentTenantMonthAssignedTenant(Property::decryptText($hid));
                            $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                            $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                        }
                        else{
                            // $tenantid='';
                            $tenantid=$prevwaterbills->tenant;
                            $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                            $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                            // $tenantname='House Vacant';
                            // $tenantfname='House Vacant';
                        }

                        $prevbill=($prevwaterbills->Current!='')?$prevwaterbills->Current:'';
                        $curbill='';
                        $saved_bill=$prevbill.':'.$curbill;
                        $loading_bill=$prevbill.':'.$curbill;
                        $watermessage_data[] = array(
                            'pid' => $id,
                            'id' => $hid,
                            'tid' => $tenantid,
                            'checkcurrenttenantstatus'=>$checkcurrenttenantstatus,
                            'previous' => ($prevwaterbills->Current!='')?$prevwaterbills->Current:'',
                            'current' => 0,
                            'saved_previous' => '',
                            'saved_current' => '',
                            'saved' =>'',
                            'present' =>'No',
                            'saved_bill' =>'',
                            'loading_bill' =>'',
                            'prevmatches' =>'',
                            'cost' => $prevwaterbills->Cost,
                            'units' => 0.00,
                            'total' => 0.00,
                            'total_os' => 0.00,
                            'housename'=>$housename,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'waterid' => $waterid,
                            'month' => $month,
                            'monthname'=>$monthnames,
                            'created_at' => $prevwaterbills->created_at
                        );

                        // $waterid=$prevwaterbills->id;
                        // $prevbill=($prevwaterbills->Previous!='')?$prevwaterbills->Previous:'';
                        // $curbill=($prevwaterbills->Current!='')?$prevwaterbills->Current:'';
                        // $saved_bill=$prevbill.':'.$curbill;
                        // $loading_bill=$prevbill.':'.$curbill;
                        // $watermessage_data[] = array(
                        //     'pid' => $id,
                        //     'id' => $hid,
                        //     'tid' => $tenantid,
                        //     'checkcurrenttenantstatus'=>$checkcurrenttenantstatus,
                        //     'previous' => ($prevwaterbills->Previous!='')?$prevwaterbills->Previous:'',
                        //     'current' => ($prevwaterbills->Current!='')?$prevwaterbills->Current:'',
                        //     'saved_previous' => '',
                        //     'saved_current' => '',
                        //     'saved' =>'No',
                        //     'present' =>'Yes',
                        //     'saved_bill' =>$saved_bill,
                        //     'loading_bill' =>$loading_bill,
                        //     'prevmatches' =>'Yes',
                        //     'cost' => $prevwaterbills->Cost,
                        //     'units' => $prevwaterbills->Units,
                        //     'total' => $prevwaterbills->Total,
                        //     'total_os' => $prevwaterbills->Total_OS,
                        //     'housename'=>$housename,
                        //     'tenantname' => ucwords(strtolower($tenantname)),
                        //     'tenantfname' => ucwords(strtolower($tenantfname)),
                        //     'waterid' => $waterid,
                        //     'month' => $month,
                        //     'monthname'=>$monthnames,
                        //     'created_at' => $prevwaterbills->created_at
                        // );

                    }
                    else{

                        // $tenantid='';
                        // $tenantid=$prevwaterbills->tenant;
                        // $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                        // $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                        // $tenantname='House Vacant';
                        // $tenantfname='House Vacant';

                        if($tenantid=Property::checkCurrentTenant(Property::decryptText($hid))){
                            $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                            $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                        }
                        else{
                            $tenantid='';
                            $tenantname='House Vacant';
                            $tenantfname='House Vacant';
                        }
                        // if($checkcurrenttenantstatus){
                        //     $tenantid=Property::checkCurrentTenantMonthAssignedTenant(Property::decryptText($hid));
                        //     // $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                        //     // $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                        //     $tenantname='House Vacant';
                        //     $tenantfname='House Vacant';
                        // }
                        // else{
                            
                        // }
                        
                        $prevbill=0;
                        $curbill='';
                        $saved_bill=$prevbill.':'.$curbill;
                        $loading_bill=$prevbill.':'.$curbill;

                        $watermessage_data[] = array(
                            'pid' => $id,
                            'id' => $hid,
                            'tid' => $tenantid,
                            'previous' => 0,
                            'current' => 0,
                            'saved_previous' => '',
                            'saved_current' => '',
                            'saved' =>'',
                            'present' =>'No',
                            'saved_bill' =>'',
                            'loading_bill' =>'',
                            'prevmatches' =>'',
                            'cost' => 0,
                            'units' => 0.00,
                            'total' => 0.00,
                            'total_os' => 0.00,
                            'housename'=>$housename,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'waterid' => $waterid,
                            'month' => $month,
                            'monthname'=>$monthnames,
                            'created_at' => ''
                        );

                        // $prevbill=($prevwaterbills->Current!='')?$prevwaterbills->Current:'';
                        // $curbill='';
                        // $saved_bill=$prevbill.':'.$curbill;
                        // $loading_bill=$prevbill.':'.$curbill;
                        // $watermessage_data[] = array(
                        //     'pid' => $id,
                        //     'id' => $hid,
                        //     'tid' => $tenantid,
                        //     'checkcurrenttenantstatus'=>$checkcurrenttenantstatus,
                        //     'previous' => ($prevwaterbills->Current!='')?$prevwaterbills->Current:'',
                        //     'current' => 0,
                        //     'saved_previous' => '',
                        //     'saved_current' => '',
                        //     'saved' =>'',
                        //     'present' =>'No',
                        //     'saved_bill' =>'',
                        //     'loading_bill' =>'',
                        //     'prevmatches' =>'',
                        //     'cost' => $prevwaterbills->Cost,
                        //     'units' => 0.00,
                        //     'total' => 0.00,
                        //     'total_os' => 0.00,
                        //     'housename'=>$housename,
                        //     'tenantname' => ucwords(strtolower($tenantname)),
                        //     'tenantfname' => ucwords(strtolower($tenantfname)),
                        //     'waterid' => $waterid,
                        //     'month' => $month,
                        //     'monthname'=>$monthnames,
                        //     'created_at' => $prevwaterbills->created_at
                        // );
                    }
                }

                // return response()->json([
                //     'status'=>500,
                //     'message'=>$tid,
                //     'message1'=>$tid,
                //     'message2'=>$hid,
                //     'month'=>$month,
                //     'waterid'=>$waterid,
                //     'waterbills'=>$waterbills,
                //     'monthassigned'=>$monthassigned,
                //     'watermessage_data'=>$watermessage_data,
                //     'status1'=>$checkcurrenttenantstatus,
                // ]);

            }

            return response()->json([
                'status'=>200,
                'previousmonths'=>$previousmonths,
                'propertyinfo'=>$propertyinfo,
                'thisproperty'=>$thisproperty,
                'waterbilldata' =>$watermessage_data,
                'currentdate' => $currentdate,
                'currentmonthname' => $currentmonthname,
                'selectedmonthname' => $selectedmonthname,
                'selectedmonth' => $month,
                'totals' => $sno,
                'preview' =>false,
                'message'=>'Found '.($sno).' Months',
            ]);

            // foreach($houseinfo as $result){
            //     $hid= $result['id'];

            //     $rent= $result['Rent'];
            //     $tid=Property::checkCurrentTenant(Property::decryptText($hid));
            //     // $tid1=Property::checkCurrentTenantWithMonth(Property::decryptText($hid),$month);
            //     $monthassigned=Property::checkCurrentTenantMonthAssigned(Property::decryptText($hid));
            //     $checkcurrenttenantstatus=$month >= $monthassigned;
            //     // return response()->json([
            //     //     'status'=>500,
            //     //     'message'=>$tid,
            //     //     'message1'=>$tid1,
            //     //     'monthassigned'=>$monthassigned,
            //     //     'status1'=>$checkcurrenttenantstatus,
            //     // ]);

            //     $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tid)), 0);
            //     $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),Property::decryptText($tid),$month);
            //     $housename=$result['Housename'];
            //     $tenantname='';
            //     $tenantfname='';
            //     if ($tid=='') {
            //         $tenant='Vacated';
            //         $tenantname='House Vacant';
            //         $tenantfname='House Vacant';
            //     }
            //     else{
            //         if($checkcurrenttenantstatus){
            //             $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
            //             $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
            //         }
            //         else{
            //             $tid='';
            //             $tenant='Vacated';
            //             $tenantname='House Vacant';
            //             $tenantfname='House Vacant';
            //         }
            //     }

            //     //tenant and house match for this month
            //     if($waterbills=Water::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
            //         if($checkcurrenttenantstatus){
            //             $tenantid=$waterbills->tenant;
            //             $tenantname=Property::TenantNames(Property::decryptText($tenantid));
            //             $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
            //         }
            //         else{
            //             $tenantid='';
            //             $tenantname='House Vacant';
            //             $tenantfname='House Vacant';
            //         }
                        
            //         $prevbill=($waterbills->Previous!='')?$waterbills->Previous:'';
            //         $curbill=($waterbills->Current!='')?$waterbills->Current:'';
            //         $saved_bill=$prevbill.':'.$curbill;
            //         $loading_bill=$prevbill.':'.$curbill;
            //         $watermessage_data[] = array(
            //             'pid' => $id,
            //             'id' => $hid,
            //             'tid' => $tenantid,
            //             'previous' => ($waterbills->Previous!='')?$waterbills->Previous:'',
            //             'current' => ($waterbills->Current!='')?$waterbills->Current:'',
            //             'saved_previous' => '',
            //             'saved_current' => '',
            //             'saved' =>'No',
            //             'present' =>'Yes',
            //             'saved_bill' =>$saved_bill,
            //             'loading_bill' =>$loading_bill,
            //             'prevmatches' =>'Yes',
            //             'cost' => $waterbills->Cost,
            //             'units' => $waterbills->Units,
            //             'total' => $waterbills->Total,
            //             'total_os' => $waterbills->Total_OS,
            //             'housename'=>$housename,
            //             'tenantname' => ucwords(strtolower($tenantname)),
            //             'tenantfname' => ucwords(strtolower($tenantfname)),
            //             'waterid' => $waterid,
            //             'month' => $month,
            //             'monthname'=>$monthnames,
            //             'created_at' => $waterbills->created_at
            //         );

            //     }
            //     else{
            //         if(date('Y n')==$month){
            //             $monthdate= Property::getLastMonthdate($month);
            //             $previousmonth= Property::getLastMonth($month,$monthdate);
                        
            //             if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$previousmonth)->get()->first()){
                            
            //                 if($checkcurrenttenantstatus){
            //                     $tenantid=$prevwaterbills->tenant;
            //                     $tenantname=Property::TenantNames(Property::decryptText($tenantid));
            //                     $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
            //                 }
            //                 else{
            //                     $tenantid='';
            //                     $tenantname='House Vacant';
            //                     $tenantfname='House Vacant';
            //                 }
                            
            //                 $prevbill=($prevwaterbills->Current!='')?$prevwaterbills->Current:'';
            //                 $curbill='';
            //                 $saved_bill=$prevbill.':'.$curbill;
            //                 $loading_bill=$prevbill.':'.$curbill;
            //                 $watermessage_data[] = array(
            //                     'pid' => $id,
            //                     'id' => $hid,
            //                     'tid' => $tenantid,
            //                     'previous' => ($prevwaterbills->Current!='')?$prevwaterbills->Current:'',
            //                     'current' => 0,
            //                     'saved_previous' => '',
            //                     'saved_current' => '',
            //                     'saved' =>'',
            //                     'present' =>'No',
            //                     'saved_bill' =>'',
            //                     'loading_bill' =>'',
            //                     'prevmatches' =>'',
            //                     'cost' => $prevwaterbills->Cost,
            //                     'units' => 0.00,
            //                     'total' => 0.00,
            //                     'total_os' => 0.00,
            //                     'housename'=>$housename,
            //                     'tenantname' => ucwords(strtolower($tenantname)),
            //                     'tenantfname' => ucwords(strtolower($tenantfname)),
            //                     'waterid' => $waterid,
            //                     'month' => $month,
            //                     'monthname'=>$monthnames,
            //                     'created_at' => $prevwaterbills->created_at
            //                 );

            //             }
            //             else{
            //                 $prevbill=0;
            //                 $curbill='';
            //                 $saved_bill=$prevbill.':'.$curbill;
            //                 $loading_bill=$prevbill.':'.$curbill;

            //                 $watermessage_data[] = array(
            //                     'pid' => $id,
            //                     'id' => $hid,
            //                     'tid' => $tid,
            //                     'previous' => 0,
            //                     'current' => 0,
            //                     'saved_previous' => '',
            //                     'saved_current' => '',
            //                     'saved' =>'',
            //                     'present' =>'No',
            //                     'saved_bill' =>'',
            //                     'loading_bill' =>'',
            //                     'prevmatches' =>'',
            //                     'cost' => 0,
            //                     'units' => 0.00,
            //                     'total' => 0.00,
            //                     'total_os' => 0.00,
            //                     'housename'=>$housename,
            //                     'tenantname' => ucwords(strtolower($tenantname)),
            //                     'tenantfname' => ucwords(strtolower($tenantfname)),
            //                     'waterid' => $waterid,
            //                     'month' => $month,
            //                     'monthname'=>$monthnames,
            //                     'created_at' => ''
            //                 );
            //             }
            //         }
            //         else{
            //             $monthdate= Property::getLastMonthdate($month);
            //             $previousmonth= Property::getLastMonth($month,$monthdate);
                        
            //             if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Month',$previousmonth)->get()->first()){
                           
            //                 if($checkcurrenttenantstatus){
            //                     $tenantid=$prevwaterbills->tenant;
            //                     $tenantname=Property::TenantNames(Property::decryptText($tenantid));
            //                     $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
            //                 }
            //                 else{
            //                     $tenantid='';
            //                     $tenantname='House Vacant';
            //                     $tenantfname='House Vacant';
            //                 }
            //                 $prevbill=($prevwaterbills->Current!='')?$prevwaterbills->Current:'';
            //                 $curbill='';
            //                 $saved_bill=$prevbill.':'.$curbill;
            //                 $loading_bill=$prevbill.':'.$curbill;
            //                 $watermessage_data[] = array(
            //                     'pid' => $id,
            //                     'id' => $hid,
            //                     'tid' => $tenantid,
            //                     'previous' => ($prevwaterbills->Current!='')?$prevwaterbills->Current:'',
            //                     'current' => 0,
            //                     'saved_previous' => '',
            //                     'saved_current' => '',
            //                     'saved' =>'',
            //                     'present' =>'No',
            //                     'saved_bill' =>'',
            //                     'loading_bill' =>'',
            //                     'prevmatches' =>'',
            //                     'cost' => $prevwaterbills->Cost,
            //                     'units' => 0.00,
            //                     'total' => 0.00,
            //                     'total_os' => 0.00,
            //                     'housename'=>$housename,
            //                     'tenantname' => ucwords(strtolower($tenantname)),
            //                     'tenantfname' => ucwords(strtolower($tenantfname)),
            //                     'waterid' => $waterid,
            //                     'month' => $month,
            //                     'monthname'=>$monthnames,
            //                     'created_at' => $prevwaterbills->created_at
            //                 );

            //             }
            //             else{
            //                 $prevbill=0;
            //                 $curbill='';
            //                 $saved_bill=$prevbill.':'.$curbill;
            //                 $loading_bill=$prevbill.':'.$curbill;

            //                 $watermessage_data[] = array(
            //                     'pid' => $id,
            //                     'id' => $hid,
            //                     'tid' => $tid,
            //                     'previous' => 0,
            //                     'current' => 0,
            //                     'saved_previous' => '',
            //                     'saved_current' => '',
            //                     'saved' =>'',
            //                     'present' =>'No',
            //                     'saved_bill' =>'',
            //                     'loading_bill' =>'',
            //                     'prevmatches' =>'',
            //                     'cost' => 0,
            //                     'units' => 0.00,
            //                     'total' => 0.00,
            //                     'total_os' => 0.00,
            //                     'housename'=>$housename,
            //                     'tenantname' => ucwords(strtolower($tenantname)),
            //                     'tenantfname' => ucwords(strtolower($tenantfname)),
            //                     'waterid' => $waterid,
            //                     'month' => $month,
            //                     'monthname'=>$monthnames,
            //                     'created_at' => ''
            //                 );
            //             }
            //         }
            //     }

                
            // }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno,
            'preview' =>false,
            'message'=>'Found '.($sno).' Months',
        ]);
    }


    public static function setRentPage($id, $month){
        
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= $result['id'];
                $rent= $result['Rent'];
                $garbage= $result['Garbage'];
                $total=$rent+$garbage;
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tid)), 0);
                // $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),Property::decryptText($tid),$month);
                $housename=$result['Housename'];
                $tenantname='';
                $tenantfname='';
                if (Property::decryptText($tid)=='') {
                    $tenant='Vacated';
                    $tenantname=($rent==0)?'Caretaker':'House Vacant';
                    $tenantfname=($rent==0)?'Caretaker':'House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
                    $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
                }

                
                if($waterbills=Payment::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
                    $tenantid=$waterbills->tenant;
                    $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                    $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));

                    
                    $rent1= $waterbills->Rent;
                    $garbage1= $waterbills->Garbage;
                    $total=$rent1+$garbage1;

                    $watermessage_data[] = array(
                        'paymentid' => $waterbills->id,
                        'pid' => $id,
                        'id' => $hid,
                        'tid' => $tenantid,
                        'Rent' => $waterbills->Rent,
                        'Garbage' => $waterbills->Garbage,
                        'Waterbill' => $waterbills->Waterbill,
                        'KPLC' => $waterbills->KPLC,
                        'HseDeposit' => $waterbills->HseDeposit,
                        'Water' => $waterbills->Water,
                        'Lease' => $waterbills->Lease,
                        'saved' => ($total > 0)?'Yes':'No',
                        'total' => $total,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'created_at' => $waterbills->created_at
                    );

                }
                else{
                    $watermessage_data[] = array(
                        'paymentid' => '',
                        'pid' => $id,
                        'id' => $hid,
                        'tid' => $tid,
                        'Rent' => 0.00,
                        'Garbage' => 0.00,
                        'Waterbill' => 0.00,
                        'KPLC' => 0.00,
                        'HseDeposit' => 0.00,
                        'Water' => 0.00,
                        'Lease' => 0.00,
                        'saved' =>'No',
                        'total' => 0.00,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'created_at' => ''
                    );
                }

                $sno1++;
                

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);
    }

    public static function setMonthlyBillsPage($id, $month){
        
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= $result['id'];
                $rent= $result['Rent'];
                $garbage= $result['Garbage'];
                $total=$rent+$garbage;
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tid)), 0);
                // $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),Property::decryptText($tid),$month);
                $housename=$result['Housename'];
                $tenantname='';
                $tenantfname='';
                if (Property::decryptText($tid)=='') {
                    $tenant='Vacated';
                    $tenantname=($rent==0)?'Caretaker':'House Vacant';
                    $tenantfname=($rent==0)?'Caretaker':'House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
                    $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
                }

                
                if($waterbills=Payment::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
                    $tenantid=$waterbills->tenant;
                    $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                    $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));

                    
                    $rent1= $waterbills->Rent;
                    $garbage1= $waterbills->Garbage;
                    $waterbill1= $waterbills->Waterbill;
                    $kplc1= $waterbills->KPLC;
                    $hsedeposit1= $waterbills->HseDeposit;
                    $water1= $waterbills->Water;
                    $lease1= $waterbills->Lease;
                    
                    $arrears1= $waterbills->Arrears;
                    $excess1= $waterbills->Excess;
                    $total=($rent1+$garbage1+$waterbill1+$kplc1+$hsedeposit1+$water1+$lease1+$arrears1)-$excess1;

                    $watermessage_data[] = array(
                        'paymentid' => $waterbills->id,
                        'pid' => $id,
                        'id' => $hid,
                        'tid' => $tenantid,
                        'Arrears' => $waterbills->Arrears,
                        'Excess' => $waterbills->Excess,
                        'Rent' => $waterbills->Rent,
                        'Garbage' => $waterbills->Garbage,
                        'Waterbill' => $waterbills->Waterbill,
                        'KPLC' => $waterbills->KPLC,
                        'HseDeposit' => $waterbills->HseDeposit,
                        'Water' => $waterbills->Water,
                        'Lease' => $waterbills->Lease,
                        'saved' => 'Yes',
                        'total' => $total,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'created_at' => $waterbills->created_at
                    );

                }
                else{
                    $watermessage_data[] = array(
                        'paymentid' => '',
                        'pid' => $id,
                        'id' => $hid,
                        'tid' => $tid,
                        'Arrears' => 0.00,
                        'Excess' => 0.00,
                        'Rent' => 0.00,
                        'Garbage' => 0.00,
                        'Waterbill' => 0.00,
                        'KPLC' => 0.00,
                        'HseDeposit' => 0.00,
                        'Water' => 0.00,
                        'Lease' => 0.00,
                        'saved' =>'No',
                        'total' => 0.00,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'created_at' => ''
                    );
                }

                $sno1++;
                

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);
    }


    public static function setNewTenantBillsPage($id, $month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            elseif($id=='All'){
                $thisproperty='';
                $houseinfo=Agreement::where('Month',0)->where('MonthAssigned',$month)->get();
                // $houseinfo=House::all();
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= '';
                if($id=='All'){
                    $hid= $result['house'];
                }
                else{
                    $hid= $result['id'];
                }
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tid)), 0);
                // $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),Property::decryptText($tid),$month);
                $housename=Property::getHouseName(Property::decryptText($hid));
                $tenantname='';
                $tenantfname='';
                if (Property::decryptText($tid)=='') {
                    $tenant='Vacated';
                    $tenantname='House Vacant';
                    $tenantfname='House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
                    $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
                }

                if($agreements=Agreement::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',0)->where('MonthAssigned',$month)->get()->first()){
                    if($waterbills=Payment::where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
                        $tenantid=$waterbills->tenant;
                        $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                        $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));

                        
                        $rent1= $waterbills->Rent;
                        $garbage1= $waterbills->Garbage;
                        $waterbill1= $waterbills->Waterbill;
                        $kplc1= $waterbills->KPLC;
                        $hsedeposit1= $waterbills->HseDeposit;
                        $water1= $waterbills->Water;
                        $lease1= $waterbills->Lease;
                        
                        $arrears1= $waterbills->Arrears;
                        $excess1= $waterbills->Excess;
                        $total=($rent1+$garbage1+$waterbill1+$kplc1+$hsedeposit1+$water1+$lease1+$arrears1)-$excess1;

                        $watermessage_data[] = array(
                            'paymentid' => $waterbills->id,
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tenantid,
                            'Arrears' => $waterbills->Arrears,
                            'Excess' => $waterbills->Excess,
                            'Rent' => $waterbills->Rent,
                            'Garbage' => $waterbills->Garbage,
                            'Waterbill' => $waterbills->Waterbill,
                            'KPLC' => $waterbills->KPLC,
                            'HseDeposit' => $waterbills->HseDeposit,
                            'Water' => $waterbills->Water,
                            'Lease' => $waterbills->Lease,
                            'saved' => 'Yes',
                            'total' => $total,
                            'housename'=>$housename,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'month' => $month,
                            'monthname'=>$monthnames,
                            'created_at' => $waterbills->created_at
                        );

                    }
                    else{
                        $watermessage_data[] = array(
                            'paymentid' => '',
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tid,
                            'Arrears' => 0.00,
                            'Excess' => 0.00,
                            'Rent' => 0.00,
                            'Garbage' => 0.00,
                            'Waterbill' => 0.00,
                            'KPLC' => 0.00,
                            'HseDeposit' => 0.00,
                            'Water' => 0.00,
                            'Lease' => 0.00,
                            'saved' =>'No',
                            'total' => 0.00,
                            'housename'=>$housename,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'month' => $month,
                            'monthname'=>$monthnames,
                            'created_at' => ''
                        );
                    }

                    $sno1++;
                }
                

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);
    }

    public static function setRefundPage($id, $month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            
            $previousmonths= array();
            $monthnames='';
            $currentdate= '';
            $currentmonthname='';
            $selectedmonthname='';

            if($month!='all'){
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                $currentdate= date('Y n');
                $selectedmonthname=Property::getMonthDateMonthPrevious($month);
                $endmonth=12;
                $sno=0;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames=Property::getMonthDateMonthPrevious($month);
            }
            else{
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                $currentdate= 'all';
                $selectedmonthname='All Months';
                $endmonth=12;
                $sno=0;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname='All Months';
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames='All Months';
                $currentdate= 'all';
            }
            

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            elseif($id=='All'){
                $thisproperty='';
                // $houseinfo=Agreement::where('Month',$month)->get();
                // $houseinfo=House::all();
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                // $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $watermessage_data= array();
            
            $agreementsdata=[];

            if($month=='all'){
                $agreementsdata=Agreement::query()->where('Month','>',0)->orderByDesc('DateVacated')->get();
            }
            else{
                $agreementsdata=Agreement::orderByDesc('DateVacated')->where('Month',$month)->get();
            }

            foreach($agreementsdata as $agreements){
                $housename=Property::getHouseName(Property::decryptText($agreements->house));
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                $tenantid=$agreements->tenant;
                $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                $hid= $agreements->house;
                $tid= $agreements->tenant;
                $plot= $agreements->plot;

                $tidcheck=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantnamecheck='';
                if (Property::decryptText($tidcheck)=='') {
                    $tenantnamecheck='';
                }
                else{
                    $tenantnamecheck=Property::checkCurrentTenantName(Property::decryptText($tidcheck));
                }

                $hidcheck=Property::checkCurrentTenantHouse(Property::decryptText($tid));
                $housenamecheck='';
                if (Property::decryptText($hidcheck)=='') {
                    $housenamecheck='';
                }
                else{
                    
                    $housenamecheck=Property::getHouseName(Property::decryptText($tid));
                }
                
                
                $vacated_day=date_format(date_create($agreements->DateVacated),'Y n');
                $monthnamesv=Property::getMonthDateMonthPrevious($vacated_day);
                if($id=='All'){
                    $watermessage_data[] = array(
                        'agreementid' => $agreements->id,
                        'pid' => Property::decryptText($agreements->plot),
                        'id' => $hid,
                        'tid' => $tenantid,
                        'Refund' => $agreements->Refund,
                        'Deposit' => $agreements->Deposit,
                        'Arrears' => $agreements->Arrears,
                        'Damages' => $agreements->Damages,
                        'Transaction' => $agreements->Transaction,
                        'OtherCharges' => $agreements->OtherCharges,
                        'DateAssigned' => $agreements->MonthAssigned,
                        'DateVacated' => $vacated_day,
                        'VacatedOn' => $agreements->DateVacated,
                        'housename'=>$housename,
                        'tenantnamecheck' =>$tenantnamecheck,
                        'housenamecheck' =>$housenamecheck,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'Phone'=> $tenantphone,
                        'month' => $vacated_day,
                        'monthname'=>$monthnamesv,
                        'created_at' => $agreements->created_at
                    );
                }
                else{
                    if(Property::decryptText($id)==Property::decryptText($plot)){
                        $watermessage_data[] = array(
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tenantid,
                            'Refund' => $agreements->Refund,
                            'Deposit' => $agreements->Deposit,
                            'Arrears' => $agreements->Arrears,
                            'Damages' => $agreements->Damages,
                            'Transaction' => $agreements->Transaction,
                            'OtherCharges' => $agreements->OtherCharges,
                            'DateAssigned' => $agreements->MonthAssigned,
                            'DateVacated' => $vacated_day,
                            'VacatedOn' => $agreements->DateVacated,
                            'housename'=>$housename,
                            'tenantnamecheck' =>$tenantnamecheck,
                            'housenamecheck' =>$housenamecheck,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'Phone'=> $tenantphone,
                            'month' => $vacated_day,
                            'monthname'=>$monthnamesv,
                            'created_at' => $agreements->created_at
                        );
                    }
                }

                $sno1++;
            }

        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);
    }

    public static function setRefundPageInitial($month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            $currentdate= date('Y n');

            $monthnames='';
            $monthnames='';
            $selectedmonthname='';
            $currentmonthname='';

            
            $previousmonths= array();

            if($month=='all'){
                $month='all';
                $monthnames='All Months';
                $selectedmonthname='All Months';
                $currentmonthname='All Months';

                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                
                $endmonth=12;
                $sno=0;
                $rent=0.00;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }

            }
            else{
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                
                $selectedmonthname=Property::getMonthDateMonthPrevious($month);
                $endmonth=12;
                $sno=0;
                $rent=0.00;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames=Property::getMonthDateMonthPrevious($month);
            }

            

            $watermessage_data= array();
            $agreementsdata=[];

            if($month=='all'){
                $agreementsdata=Agreement::query()->where('Month','>',0)->orderByDesc('DateVacated')->get();
            }
            else{
                $agreementsdata=Agreement::orderByDesc('DateVacated')->where('Month',$month)->get();
            }
            // $agreementsdata=Agreement::orderByDesc('DateVacated')->where('Month',$month)->get();
            foreach($agreementsdata as $agreements){
                $housename=Property::getHouseName(Property::decryptText($agreements->house));
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                $tenantid=$agreements->tenant;
                $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                $hid= $agreements->house;
                $tid= $agreements->tenant;
                $plot= $agreements->plot;

                $tidcheck=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantnamecheck='';
                if (Property::decryptText($tidcheck)=='') {
                    $tenantnamecheck='';
                }
                else{
                    $tenantnamecheck=Property::checkCurrentTenantName(Property::decryptText($tidcheck));
                }

                $hidcheck=Property::checkCurrentTenantHouse(Property::decryptText($tid));
                $housenamecheck='';
                if (Property::decryptText($hidcheck)=='') {
                    $housenamecheck='';
                }
                else{
                    
                    $housenamecheck=Property::getHouseName(Property::decryptText($tid));
                }

                
                $vacated_day=date_format(date_create($agreements->DateVacated),'Y n');
                $monthnamesv=Property::getMonthDateMonthPrevious($vacated_day);
                        
                $watermessage_data[] = array(
                    'agreementid' => $agreements->id,
                    'pid' => Property::decryptText($agreements->plot),
                    'id' => $hid,
                    'tid' => $tenantid,
                    'Refund' => $agreements->Refund,
                    'Deposit' => $agreements->Deposit,
                    'Arrears' => $agreements->Arrears,
                    'Damages' => $agreements->Damages,
                    'Transaction' => $agreements->Transaction,
                    'OtherCharges' => $agreements->OtherCharges,
                    'DateAssigned' => $agreements->MonthAssigned,
                    'DateVacated' => $vacated_day,
                    'VacatedOn' => $agreements->DateVacated,
                    'housename'=>$housename,
                    'tenantnamecheck' =>$tenantnamecheck,
                    'housenamecheck' =>$housenamecheck,
                    'tenantname' => ucwords(strtolower($tenantname)),
                    'tenantfname' => ucwords(strtolower($tenantfname)),
                    'Phone'=> $tenantphone,
                    'month' => $vacated_day,
                    'monthname'=>$monthnamesv,
                    'created_at' => $agreements->created_at
                );
                

                $sno1++;
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }

        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);


    }

    public static function setDepositPage($id, $month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            
            $previousmonths= array();
            $monthnames='';
            $currentdate= '';
            $currentmonthname='';
            $selectedmonthname='';

            if($month!='all'){
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                $currentdate= date('Y n');
                $selectedmonthname=Property::getMonthDateMonthPrevious($month);
                $endmonth=12;
                $sno=0;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames=Property::getMonthDateMonthPrevious($month);
            }
            else{
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                $currentdate= 'all';
                $selectedmonthname='All Months';
                $endmonth=12;
                $sno=0;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname='All Months';
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames='All Months';
                $currentdate= 'all';
            }
            

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            elseif($id=='All'){
                $thisproperty='';
                // $houseinfo=Agreement::where('Month',$month)->get();
                // $houseinfo=House::all();
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                // $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $watermessage_data= array();
            
            $agreementsdata=[];

            if($month=='all'){
                $agreementsdata=Agreement::query()->where('Month','=',0)->where('Deposit','>',0)->orderByDesc('DateAssigned')->get();
            }
            else{
                $agreementsdata=Agreement::orderByDesc('DateAssigned')->where('Deposit','>',0)->where('MonthAssigned',$month)->where('Month',0)->get();
            }

            foreach($agreementsdata as $agreements){
                $housename=Property::getHouseName(Property::decryptText($agreements->house));
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                $tenantid=$agreements->tenant;
                $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                $hid= $agreements->house;
                $tid= $agreements->tenant;
                $plot= $agreements->plot;

                $tidcheck=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantnamecheck='';
                if (Property::decryptText($tidcheck)=='') {
                    $tenantnamecheck='';
                }
                else{
                    $tenantnamecheck=Property::checkCurrentTenantName(Property::decryptText($tidcheck));
                }

                $hidcheck=Property::checkCurrentTenantHouse(Property::decryptText($tid));
                $housenamecheck='';
                if (Property::decryptText($hidcheck)=='') {
                    $housenamecheck='';
                }
                else{
                    
                    $housenamecheck=Property::getHouseName(Property::decryptText($tid));
                }
                
                
                $vacated_day=($agreements->DateVacated=='0000-00-00' || $agreements->DateVacated==null)?'':date_format(date_create($agreements->DateVacated),'Y n');
                $monthnamesv=($vacated_day!='')?Property::getMonthDateMonthPrevious($vacated_day):'';
                
                $KPLCDeposit=Property::PaymentKPLC(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);
                $WaterDeposit=Property::PaymentWater(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);
                $HouseDeposit=Property::PaymentHseDeposit(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);


                if($id=='All'){
                    $watermessage_data[] = array(
                        'agreementid' => $agreements->id,
                        'pid' => Property::decryptText($agreements->plot),
                        'id' => $hid,
                        'tid' => $tenantid,
                        'Refund' => $agreements->Refund,
                        'Deposit' => $agreements->Deposit,
                        'Arrears' => $agreements->Arrears,
                        'KPLCDeposit' => $KPLCDeposit,
                        'WaterDeposit' => $WaterDeposit,
                        'HouseDeposit' => $HouseDeposit,
                        'DateAssigned' => $agreements->DateAssigned,
                        'DateVacated' => $vacated_day,
                        'VacatedOn' => $agreements->DateVacated,
                        'housename'=>$housename,
                        'tenantnamecheck' =>$tenantnamecheck,
                        'housenamecheck' =>$housenamecheck,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'Phone'=> $tenantphone,
                        'month' => $vacated_day,
                        'monthname'=>$monthnamesv,
                        'created_at' => $agreements->created_at
                    );
                }
                else{
                    if(Property::decryptText($id)==Property::decryptText($plot)){
                        $watermessage_data[] = array(
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tenantid,
                            'Refund' => $agreements->Refund,
                            'Deposit' => $agreements->Deposit,
                            'Arrears' => $agreements->Arrears,
                            'KPLCDeposit' => $KPLCDeposit,
                            'WaterDeposit' => $WaterDeposit,
                            'HouseDeposit' => $HouseDeposit,
                            'DateAssigned' => $agreements->DateAssigned,
                            'DateVacated' => $vacated_day,
                            'VacatedOn' => $agreements->DateVacated,
                            'housename'=>$housename,
                            'tenantnamecheck' =>$tenantnamecheck,
                            'housenamecheck' =>$housenamecheck,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'Phone'=> $tenantphone,
                            'month' => $vacated_day,
                            'monthname'=>$monthnamesv,
                            'created_at' => $agreements->created_at
                        );
                    }
                }

                $sno1++;
            }

        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);
    }

    public static function setDepositPageInitial($month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            $currentdate= date('Y n');

            $monthnames='';
            $monthnames='';
            $selectedmonthname='';
            $currentmonthname='';

            
            $previousmonths= array();

            if($month=='all'){
                $month='all';
                $monthnames='All Months';
                $selectedmonthname='All Months';
                $currentmonthname='All Months';

                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                
                $endmonth=12;
                $sno=0;
                $rent=0.00;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }

            }
            else{
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                
                $selectedmonthname=Property::getMonthDateMonthPrevious($month);
                $endmonth=12;
                $sno=0;
                $rent=0.00;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames=Property::getMonthDateMonthPrevious($month);
            }

            

            $watermessage_data= array();
            $agreementsdata=[];

            if($month=='all'){
                $agreementsdata=Agreement::query()->where('Month','=',0)->where('Deposit','>',0)->orderByDesc('DateAssigned')->get();
            }
            else{
                $agreementsdata=Agreement::orderByDesc('DateAssigned')->where('Deposit','>',0)->where('MonthAssigned',$month)->where('Month',0)->get();
            }
            // $agreementsdata=Agreement::orderByDesc('DateVacated')->where('Month',$month)->get();
            foreach($agreementsdata as $agreements){
                $housename=Property::getHouseName(Property::decryptText($agreements->house));
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                $tenantid=$agreements->tenant;
                $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                $hid= $agreements->house;
                $tid= $agreements->tenant;
                $plot= $agreements->plot;

                $tidcheck=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantnamecheck='';
                if (Property::decryptText($tidcheck)=='') {
                    $tenantnamecheck='';
                }
                else{
                    $tenantnamecheck=Property::checkCurrentTenantName(Property::decryptText($tidcheck));
                }

                $hidcheck=Property::checkCurrentTenantHouse(Property::decryptText($tid));
                $housenamecheck='';
                if (Property::decryptText($hidcheck)=='') {
                    $housenamecheck='';
                }
                else{
                    
                    $housenamecheck=Property::getHouseName(Property::decryptText($tid));
                }

                
                $vacated_day=($agreements->DateVacated=='0000-00-00' || $agreements->DateVacated==null)?'':date_format(date_create($agreements->DateVacated),'Y n');
                $monthnamesv=($vacated_day!='')?Property::getMonthDateMonthPrevious($vacated_day):'';
                
                $KPLCDeposit=Property::PaymentKPLC(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);
                $WaterDeposit=Property::PaymentWater(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);
                $HouseDeposit=Property::PaymentHseDeposit(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);


                $watermessage_data[] = array(
                    'agreementid' => $agreements->id,
                    'pid' => Property::decryptText($agreements->plot),
                    'id' => $hid,
                    'tid' => $tenantid,
                    'Refund' => $agreements->Refund,
                    'Deposit' => $agreements->Deposit,
                    'Arrears' => $agreements->Arrears,
                    'KPLCDeposit' => $KPLCDeposit,
                    'WaterDeposit' => $WaterDeposit,
                    'HouseDeposit' => $HouseDeposit,
                    'DateAssigned' => $agreements->DateAssigned,
                    'DateVacated' => $vacated_day,
                    'VacatedOn' => $agreements->DateVacated,
                    'housename'=>$housename,
                    'tenantnamecheck' =>$tenantnamecheck,
                    'housenamecheck' =>$housenamecheck,
                    'tenantname' => ucwords(strtolower($tenantname)),
                    'tenantfname' => ucwords(strtolower($tenantfname)),
                    'Phone'=> $tenantphone,
                    'month' => $vacated_day,
                    'monthname'=>$monthnamesv,
                    'created_at' => $agreements->created_at
                );
                

                $sno1++;
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }

        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);


    }

    public static function setLeasePage($id, $month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            
            $previousmonths= array();
            $monthnames='';
            $currentdate= '';
            $currentmonthname='';
            $selectedmonthname='';

            if($month!='all'){
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                $currentdate= date('Y n');
                $selectedmonthname=Property::getMonthDateMonthPrevious($month);
                $endmonth=12;
                $sno=0;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames=Property::getMonthDateMonthPrevious($month);
            }
            else{
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                $currentdate= 'all';
                $selectedmonthname='All Months';
                $endmonth=12;
                $sno=0;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname='All Months';
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames='All Months';
                $currentdate= 'all';
            }
            

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            elseif($id=='All'){
                $thisproperty='';
                // $houseinfo=Agreement::where('Month',$month)->get();
                // $houseinfo=House::all();
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                // $houseinfo=House::where('Plot',Property::decryptText($id))->get();
            }

            $watermessage_data= array();
            
            $agreementsdata=[];

            if($month=='all'){
                $agreementsdata=Agreement::query()->where('Month','=',0)->where('Deposit','>',0)->orderByDesc('DateAssigned')->get();
            }
            else{
                $agreementsdata=Agreement::orderByDesc('DateAssigned')->where('Deposit','>',0)->where('MonthAssigned',$month)->where('Month',0)->get();
            }

            foreach($agreementsdata as $agreements){
                $housename=Property::getHouseName(Property::decryptText($agreements->house));
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                $tenantid=$agreements->tenant;
                $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                $hid= $agreements->house;
                $tid= $agreements->tenant;
                $plot= $agreements->plot;

                $tidcheck=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantnamecheck='';
                if (Property::decryptText($tidcheck)=='') {
                    $tenantnamecheck='';
                }
                else{
                    $tenantnamecheck=Property::checkCurrentTenantName(Property::decryptText($tidcheck));
                }

                $hidcheck=Property::checkCurrentTenantHouse(Property::decryptText($tid));
                $housenamecheck='';
                if (Property::decryptText($hidcheck)=='') {
                    $housenamecheck='';
                }
                else{
                    
                    $housenamecheck=Property::getHouseName(Property::decryptText($tid));
                }
                
                
                $vacated_day=($agreements->DateVacated=='0000-00-00' || $agreements->DateVacated==null)?'':date_format(date_create($agreements->DateVacated),'Y n');
                $monthnamesv=($vacated_day!='')?Property::getMonthDateMonthPrevious($vacated_day):'';
                
                $Lease=Property::PaymentLease(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);
               
                if($Lease>0){
                    if($id=='All'){
                        $watermessage_data[] = array(
                            'agreementid' => $agreements->id,
                            'pid' => Property::decryptText($agreements->plot),
                            'id' => $hid,
                            'tid' => $tenantid,
                            'Refund' => $agreements->Refund,
                            'Deposit' => $agreements->Deposit,
                            'Arrears' => $agreements->Arrears,
                            'Lease' => $Lease,
                            'DateAssigned' => $agreements->DateAssigned,
                            'DateVacated' => $vacated_day,
                            'VacatedOn' => $agreements->DateVacated,
                            'housename'=>$housename,
                            'tenantnamecheck' =>$tenantnamecheck,
                            'housenamecheck' =>$housenamecheck,
                            'tenantname' => ucwords(strtolower($tenantname)),
                            'tenantfname' => ucwords(strtolower($tenantfname)),
                            'Phone'=> $tenantphone,
                            'month' => $vacated_day,
                            'monthname'=>$monthnamesv,
                            'created_at' => $agreements->created_at
                        );
                    }
                    else{
                        if(Property::decryptText($id)==Property::decryptText($plot)){
                            $watermessage_data[] = array(
                                'agreementid' => $agreements->id,
                                'pid' => Property::decryptText($agreements->plot),
                                'id' => $hid,
                                'tid' => $tenantid,
                                'Refund' => $agreements->Refund,
                                'Deposit' => $agreements->Deposit,
                                'Arrears' => $agreements->Arrears,
                                'Lease' => $Lease,
                                'DateAssigned' => $agreements->DateAssigned,
                                'DateVacated' => $vacated_day,
                                'VacatedOn' => $agreements->DateVacated,
                                'housename'=>$housename,
                                'tenantnamecheck' =>$tenantnamecheck,
                                'housenamecheck' =>$housenamecheck,
                                'tenantname' => ucwords(strtolower($tenantname)),
                                'tenantfname' => ucwords(strtolower($tenantfname)),
                                'Phone'=> $tenantphone,
                                'month' => $vacated_day,
                                'monthname'=>$monthnamesv,
                                'created_at' => $agreements->created_at
                            );
                        }
                    }

                    $sno1++;
                }
            }

        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);
    }

    public static function setLeasePageInitial($month){
        try { 
            $properties = Property::all();
            $propertyinfo= array();
            $sno=0;
            $sno1=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            $currentdate= date('Y n');

            $monthnames='';
            $monthnames='';
            $selectedmonthname='';
            $currentmonthname='';

            
            $previousmonths= array();

            if($month=='all'){
                $month='all';
                $monthnames='All Months';
                $selectedmonthname='All Months';
                $currentmonthname='All Months';

                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                
                $endmonth=12;
                $sno=0;
                $rent=0.00;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }

            }
            else{
                $startyear=2019;
                $startmonth=1;
                $endyear=date('Y');
                
                $selectedmonthname=Property::getMonthDateMonthPrevious($month);
                $endmonth=12;
                $sno=0;
                $rent=0.00;
                for ($i=$endyear; $i >= $startyear; $i--) { 
                    if ($i==2019) {
                        $startmonth=7;
                    }
                    else{
                        $startmonth=1;
                    }
    
                    if ($i==$endyear) {
                        $endmonth=date('n');
                    }
                    else{
                        $endmonth=12;
                    }
                    for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                        $months= $i.' '.$m;
                        $monthname=Property::getMonthDateMonthPrevious($months);
                        $monthly=Property::getMonthDateDash($months);
                        $yearly=Property::getYearDateDash($months);
                        $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                        $previousmonths[] = array(
                            'sno'=>$sno,
                            'month' => $months,
                            'monthname' => $monthname,
                            'monthly' => $monthly,
                            'yearly' => $yearly,
                            'currentdate' => $currentdate
                        );
                        $sno++;
                    }
                }
                $monthnames=Property::getMonthDateMonthPrevious($month);
            }

            

            $watermessage_data= array();
            $agreementsdata=[];

            if($month=='all'){
                $agreementsdata=Agreement::query()->where('Month','=',0)->where('Deposit','>',0)->orderByDesc('DateAssigned')->get();
            }
            else{
                $agreementsdata=Agreement::orderByDesc('DateAssigned')->where('Deposit','>',0)->where('MonthAssigned',$month)->where('Month',0)->get();
            }
            // $agreementsdata=Agreement::orderByDesc('DateVacated')->where('Month',$month)->get();
            foreach($agreementsdata as $agreements){
                $housename=Property::getHouseName(Property::decryptText($agreements->house));
                $tenantid='';
                $tenantname='';
                $tenantfname='';
                $tenantid=$agreements->tenant;
                $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                $hid= $agreements->house;
                $tid= $agreements->tenant;
                $plot= $agreements->plot;

                $tidcheck=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantnamecheck='';
                if (Property::decryptText($tidcheck)=='') {
                    $tenantnamecheck='';
                }
                else{
                    $tenantnamecheck=Property::checkCurrentTenantName(Property::decryptText($tidcheck));
                }

                $hidcheck=Property::checkCurrentTenantHouse(Property::decryptText($tid));
                $housenamecheck='';
                if (Property::decryptText($hidcheck)=='') {
                    $housenamecheck='';
                }
                else{
                    
                    $housenamecheck=Property::getHouseName(Property::decryptText($tid));
                }

                
                $vacated_day=($agreements->DateVacated=='0000-00-00' || $agreements->DateVacated==null)?'':date_format(date_create($agreements->DateVacated),'Y n');
                $monthnamesv=($vacated_day!='')?Property::getMonthDateMonthPrevious($vacated_day):'';
                
                $Lease=Property::PaymentLease(Property::decryptText($agreements->tenant),Property::decryptText($agreements->house),$agreements->MonthAssigned);
               
                if($Lease>0){

                    $watermessage_data[] = array(
                        'agreementid' => $agreements->id,
                        'pid' => Property::decryptText($agreements->plot),
                        'id' => $hid,
                        'tid' => $tenantid,
                        'Refund' => $agreements->Refund,
                        'Deposit' => $agreements->Deposit,
                        'Arrears' => $agreements->Arrears,
                        'Lease' => $Lease,
                        'DateAssigned' => $agreements->DateAssigned,
                        'DateVacated' => $vacated_day,
                        'VacatedOn' => $agreements->DateVacated,
                        'housename'=>$housename,
                        'tenantnamecheck' =>$tenantnamecheck,
                        'housenamecheck' =>$housenamecheck,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'Phone'=> $tenantphone,
                        'month' => $vacated_day,
                        'monthname'=>$monthnamesv,
                        'created_at' => $agreements->created_at
                    );
                    $sno1++;
                }
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }

        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'preview' =>false,
            'message'=>'Found '.($sno1).' Houses',
        ]);


    }
    
    
    
    public static function previewWaterbill(Request $request){
        // $files = $request->file('files');
        // $fileName = $files->getClientOriginalName();
        // $files->move(public_path('uploads'), $fileName);

        $pid=$request->input('pid');
        // $pid=Property::decryptText($pid);
        $month=$request->input('month');

        $monthnames=Property::getMonthDateMonthPrevious($month);
        $pcode=Property::getPropertyCode(Property::decryptText($pid));

        $sno=0;
        try { 
            $file = $request->file('files');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify("public/uploads/".$fileName);
            /**  Create a new Reader of the type that has been identified  **/
            
            
            
            // $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("public/uploads/".$fileName);

            // $worksheet1 = $spreadsheet->getActiveSheet();

            $simpe_data= array();
            // foreach ($worksheet1->getRowIterator() as $row11) {
            //     $cellIterator = $row11->getCellIterator();
            //     $cellIterator->setIterateOnlyExistingCells(FALSE);
            //     $rows='';
            //     foreach ($cellIterator as $cell) {
            //         $cellValue = $cell->getValue();
            //         if($cellValue=='UNIT' || $cellValue=="HSE/NO" || $cellValue=='Unit' || $cellValue=='Previous'){
            //             break;
            //         }
            //         else{
            //             $rows=$rows.$cellValue.' ';
            //         }
            //     }
            //     if($rows!=''){
            //         $simpe_data[] = array(
            //             'pid' => $rows
            //         );
            //     }
            // }

            // return ($simpe_data);


            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $objPHPExcel = $reader->load("public/uploads/".$fileName);
            $watermessage_data= array();
            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow=$worksheet->getHighestRow();
                $highestColumn=$worksheet->getHighestColumn();

                // return compact($highestRow);
                // $error=$highestColumn;
                //             return compact('error');
                
                // $sno1=1;
                // if ($highestColumn=="G") {
                //     for($row=1;$row<=$highestRow;$row++){
                //         $simpe_data1 =[
                //             'A' => $worksheet->getCell(Coordinate::stringFromColumnIndex(1) . $row)->getValue(),
                //             'B' => $worksheet->getCell(Coordinate::stringFromColumnIndex(2) . $row)->getValue(),
                //             'D' => $worksheet->getCell(Coordinate::stringFromColumnIndex(3) . $row)->getValue(),
                //             'D' => $worksheet->getCell(Coordinate::stringFromColumnIndex(4) . $row)->getValue(),
                //             'E' => $worksheet->getCell(Coordinate::stringFromColumnIndex(5) . $row)->getValue(),
                //             'F' => $worksheet->getCell(Coordinate::stringFromColumnIndex(6) . $row)->getValue(),
                //             'G' => $worksheet->getCell(Coordinate::stringFromColumnIndex(7) . $row)->getValue(),
                //         ];

                //         $simpe_data[] = array(
                //             $simpe_data1
                //         );
                        
                //         $sno1++;
                //     }
                    
                // }
                // return ($simpe_data);
                
                if ($highestColumn=="G") {
                    // if($worksheet->getCell('A1')->getValue() != "HSE/NO"){
                        for($row=2;$row<=$highestRow;$row++){
                            $rowfound='';

                            // $simpe_data1 =[
                            //     'A' => $worksheet->getCell(Coordinate::stringFromColumnIndex(1) . $row)->getValue(),
                            //     'B' => $worksheet->getCell(Coordinate::stringFromColumnIndex(2) . $row)->getValue(),
                            //     'D' => $worksheet->getCell(Coordinate::stringFromColumnIndex(3) . $row)->getValue(),
                            //     'D' => $worksheet->getCell(Coordinate::stringFromColumnIndex(4) . $row)->getValue(),
                            //     'E' => $worksheet->getCell(Coordinate::stringFromColumnIndex(5) . $row)->getValue(),
                            //     'F' => $worksheet->getCell(Coordinate::stringFromColumnIndex(6) . $row)->getValue(),
                            //     'G' => $worksheet->getCell(Coordinate::stringFromColumnIndex(7) . $row)->getValue(),
                            // ];
                            
                            $hse=$worksheet->getCell(Coordinate::stringFromColumnIndex(1) . $row)->getValue();
                            $tent=$worksheet->getCell(Coordinate::stringFromColumnIndex(2) . $row)->getValue();
                            $prev=$worksheet->getCell(Coordinate::stringFromColumnIndex(3) . $row)->getValue();
                            $curr=$worksheet->getCell(Coordinate::stringFromColumnIndex(4) . $row)->getValue();
                            $cost=$worksheet->getCell(Coordinate::stringFromColumnIndex(5) . $row)->getValue();
                            if (is_string($prev) || is_string($curr)) {
                                break;
                            }
                           
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

                            $tenant=Property::checkCurrentTenant(Property::decryptText($id));
                            $waterid=Property::checkCurrentTenantBill(Property::decryptText($id),Property::decryptText($tenant),$month);

                            $monthdate= Property::getLastMonthdate($month);
                            $lastmonth= Property::getLastMonth($month,$monthdate);

                            $saved_previous=Property::checkCurrentTenantPreviousBill(Property::decryptText($id),Property::decryptText($tenant),$month);
                            $last_current=Property::checkCurrentTenantCurrentBill(Property::decryptText($id),Property::decryptText($tenant),$lastmonth);
                            $saved_current=Property::checkCurrentTenantCurrentBill(Property::decryptText($id),Property::decryptText($tenant),$month);
                            $loading_bill=$prev.':'.$curr;
                            $saved_bill=$saved_previous.':'.$saved_current;
                            $saved=($loading_bill==$saved_bill)?'No':'Yes';
                            $prevmatches=($prev==$last_current)?'Yes':'No';

                            // return response()->json([
                            //     'status'=>401,
                            //     'message'=>$waterid,
                            //     'propert'=>$prevmatches,
                            //     'prev'=>$prev,
                            //     'curr'=>$curr,
                            //     'tenant'=>$tenant,
                            //     'id'=>Property::decryptText($id),
                            //     'tid'=>Property::decryptText($tenant),
                            // ]);


                            $tenantname='';
                            $tenantfname='';
                            if ($tenant=='') {
                                $tenant='Vacated';
                                $tenantname='House Vacant';
                                $tenantfname='House Vacant';
                            }
                            else{
                                $tenantname=Property::checkCurrentTenantName($tenant);
                                $tenantfname=Property::checkCurrentTenantFName($tenant);
                            }
                            $uploadedPlot=Property::getHousePlotUploaded($housenames);
                            
                            if(Property::decryptText($uploadedPlot) !=Property::decryptText($pid) && $row==1){
                                $error="The Uploaded Data is not for The Selected Property.\n Please re Upload the Valid data:\n";
                                // return compact('error');
                                return response()->json([
                                    'status'=>500,
                                    'message'=>$error,
                                ]);
                            }

                            
                            if($id==''){

                            }
                            else{
                                if($waterid==null){
                                    // return response()->json([
                                    //     'status'=>401,
                                    //     'message'=>$waterid,
                                    //     'propert'=>$prevmatches,
                                    //     'prev'=>$prev,
                                    //     'curr'=>$curr,
                                    // ]);
                                    if($tenant!='Vacated'){
                                        if($prevmatches=='Yes'){
                                            if($prev!='' && $curr!=''){
                                                $watermessage_data[] = array(
                                                    'pid' => $pid,
                                                    'id' => $id,
                                                    'tid' => $tenant,
                                                    'previous' => ($prev!='')?$prev:'',
                                                    'current' => ($curr!='')?$curr:'',
                                                    'saved_previous' => ($saved_previous!='')?$saved_previous:'',
                                                    'saved_current' => ($saved_current!='')?$saved_current:'',
                                                    'saved' =>$saved,
                                                    'saved_bill' =>$saved_bill,
                                                    'loading_bill' =>$loading_bill,
                                                    'prevmatches' =>$prevmatches,
                                                    'cost' => ($cost!='')?$cost:'',
                                                    'units' => $uni,
                                                    'total' => $total,
                                                    'total_os' => 0,
                                                    'housename'=>$housenames,
                                                    'tenantname' => ucwords(strtolower($tenantname)),
                                                    'tenantfname' => ucwords(strtolower($tenantfname)),
                                                    'waterid' => $waterid,
                                                    'month' => $month,
                                                    'monthname'=>$monthnames
                                                );
                                                $sno++;
                                            }
                                        }
                                        else{
                                            if($prev!='' && $curr!=''){
                                                $watermessage_data[] = array(
                                                    'pid' => $pid,
                                                    'id' => $id,
                                                    'tid' => $tenant,
                                                    'previous' => ($prev!='')?$prev:'',
                                                    'current' => ($curr!='')?$curr:'',
                                                    'saved_previous' => ($saved_previous!='')?$saved_previous:'',
                                                    'saved_current' => ($saved_current!='')?$saved_current:'',
                                                    'saved' =>$saved,
                                                    'saved_bill' =>$saved_bill,
                                                    'loading_bill' =>$loading_bill,
                                                    'prevmatches' =>$prevmatches,
                                                    'cost' => ($cost!='')?$cost:'',
                                                    'units' => $uni,
                                                    'total' => $total,
                                                    'total_os' => 0,
                                                    'housename'=>$housenames,
                                                    'tenantname' => ucwords(strtolower($tenantname)),
                                                    'tenantfname' => ucwords(strtolower($tenantfname)),
                                                    'waterid' => $waterid,
                                                    'month' => $month,
                                                    'monthname'=>$monthnames
                                                );
                                                $sno++;
                                            }
                                        }
                                    }
                                }
                                else{
                                    
                                    $watermessage_data[] = array(
                                        'pid' => $pid,
                                        'id' => $id,
                                        'tid' => $tenant,
                                        'previous' => ($prev!='')?$prev:'',
                                        'current' => ($curr!='')?$curr:'',
                                        'saved_previous' => ($saved_previous!='')?$saved_previous:'',
                                        'saved_current' => ($saved_current!='')?$saved_current:'',
                                        'saved' =>$saved,
                                        'saved_bill' =>$saved_bill,
                                        'loading_bill' =>$loading_bill,
                                        'prevmatches' =>$prevmatches,
                                        'cost' => ($cost!='')?$cost:'',
                                        'units' => $uni,
                                        'total' => $total,
                                        'total_os' => 0,
                                        'housename'=>$housenames,
                                        'tenantname' => ucwords(strtolower($tenantname)),
                                        'tenantfname' => ucwords(strtolower($tenantfname)),
                                        'waterid' => $waterid,
                                        'month' => $month,
                                        'monthname'=>$monthnames
                                    );
                                    $sno++;
                                }
                                
                            }
                        }
                    // }
                }
                else{
                    $error="Error in Water Bill Upload Format.\n Please Make it 7 Columns As Below:\nA:Housename, B:Tenant Name, C:Previous, D:Current, E:Cost, F:Units, G:Amount/Total";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }

                // return ($watermessage_data);
            }
            $curmonth=Property::getMonthDate($month);
            $billsaved=Property::getTotalWaterBillsHse(Property::decryptText($pid),$month);
            $billsent=Property::getTotalWaterMsgHse(Property::decryptText($pid),$month);
            $totalhouses=Property::getTotalHousesHse(Property::decryptText($pid));

        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
            
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }


        return response()->json([
            'status'=>200,
            'waterbilldata' =>$watermessage_data,
            'billsaved' =>$billsaved,
            'billsent' =>$billsent,
            'totalhouses' =>$totalhouses,
            'preview' =>true,
            'message'=>"There are ".($sno)." Waterbills for uploading or Updating.\nFor any other changes, please Add or Edit after closing Preview",
        ]);
    }


    public function savewaterbillnew(Request $request)
    {
        
            $hid=$request->input('hid');
            $pid=$request->input('pid');
            $Tenant=$request->input('Tenant');
            $month=$request->input('month');

            
            if ($Tenant=="") {
                $error='No Tenant Selected!';
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
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
            $monthdate= Property::getNextMonthdate($month);
            $nextmonth= Property::getNextMonth($month,$monthdate);
            $housename=Property::getHouseName(Property::decryptText($hid));
            $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
            if(!$paymentid=Property::getPaymentId(Property::decryptText($hid),Property::decryptText($Tenant),$nextmonth)){
                $paymentsnew = new Payment;
                $paymentsnew->plot=Property::decryptText($pid);
                $paymentsnew->house=Property::decryptText($hid);
                $paymentsnew->tenant=Property::decryptText($Tenant);
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
            $water->plot=Property::decryptText($pid);
            $water->house=Property::decryptText($hid);
            $water->tenant=Property::decryptText($Tenant);
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
                return response()->json([
                    'status'=>200,
                    'message'=>'Waterbill Record Added',
                ]);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>'Waterbill Record Not Added',
                ]);
            }
            
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
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
                $error='No Tenant Selected!';
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
         try {
            $Previous=$request->input('Previous');
            $Current=$request->input('Current');
            $Cost=round($request->input('Cost'),2);
            $Units=$request->input('Units');
            $Total=$request->input('Total');
            $Total_OS=$request->input('Total_OS');
            $totalwater=$Total+$Total_OS;
            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= Property::getNextMonthdate($month);
            $nextmonth= Property::getNextMonth($month,$monthdate);
            $housename=Property::getHouseName(Property::decryptText($hid));
            $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
            // return response()->json([
            //     'status'=>500,
            //     'message'=>Property::decryptText($hid)." ".Property::decryptText($pid)." ".Property::decryptText($Tenant),
            // ]);
            if(!$paymentid=Property::getPaymentId(Property::decryptText($hid),Property::decryptText($Tenant),$nextmonth)){
                $paymentsnew = new Payment;
                $paymentsnew->plot=Property::decryptText($pid);
                $paymentsnew->house=Property::decryptText($hid);
                $paymentsnew->tenant=Property::decryptText($Tenant);
                $paymentsnew->Month=$nextmonth;
                $paymentsnew->Waterbill=$totalwater;
                $paymentsnew->save();
            }
            else{
                $payments = Payment::findOrFail($paymentid);
                $payments->Waterbill=$totalwater;
                $payments->save();
            }

            if($water = Water::findOrFail($waterid)){
                $water->plot=Property::decryptText($pid);
                $water->house=Property::decryptText($hid);
                $water->tenant=Property::decryptText($Tenant);
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

                if($water->save()){
                    return response()->json([
                        'status'=>200,
                        'message'=>'Waterbill Record Updated',
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>'Waterbill Record Not Updated',
                    ]);
                }
            }
            else{
                return response()->json([
                    'status'=>401,
                    'message'=>'Not Saved Waterbill Found',
                ]);
            }
            

            } 
            
            catch(\Illuminate\Database\QueryException $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
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
            //updaten  existing or upload new  waterbill
            
            if($waterbillvaluesupdate){
                $allwaterbill=implode(",", $waterbillvaluesupdate);
                $eachwaterbill=explode(",", $allwaterbill);
                $monthdate= Property::getNextMonthdate($month);
                $nextmonth= Property::getNextMonth($month,$monthdate);

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
                        $error='No Tenant Information Found!';
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }

                    $DateTrans=date('Y-m-d');
                    $explomonth=explode(' ', $month);
                    $years=$explomonth[0];
                    $months=$explomonth[1];
                    $housename=Property::getHouseName(Property::decryptText($hid));
                    $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
                    
                    
                    if(!$paymentid=Property::getPaymentId(Property::decryptText($hid),Property::decryptText($Tenant),$nextmonth)){
                        $paymentsnew = new Payment;
                        $paymentsnew->plot=Property::decryptText($pid);
                        $paymentsnew->house=Property::decryptText($hid);
                        $paymentsnew->tenant=Property::decryptText($Tenant);
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

                    if($waterid=='null'){
                        $water = new Water;
                        $water->plot=Property::decryptText($pid);
                        $water->house=Property::decryptText($hid);
                        $water->tenant=Property::decryptText($Tenant);
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
                    else{
                        if($water = Water::findOrFail($waterid)){
                            $water->plot=Property::decryptText($pid);
                            $water->house=Property::decryptText($hid);
                            $water->tenant=Property::decryptText($Tenant);
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
                        else{
                            $msgerror="Not Saved";
                        }
                    }
                }
                //end of update
            }
        } 
        
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
                'mm' =>$waterbillvaluesupdate,
            ]);
        }
        

        if($msgerror=="Not Saved"){
            return response()->json([
                'status'=>200,
                'message'=>"Waterbill  Uploaded Partialy.\n Some House(s) information was not Saved.\n Please try again",
            ]);
        }
        
        else{
            return response()->json([
                'status'=>200,
                'message'=>"Waterbill  Uploaded Successfully.\n To Update A single House , Please Click Edit",
            ]);
        }
         
    }

    public function saveupdateHouseDetail(Request $request)
    {
        $UpdateValue=$request->input('UpdateValue');
        $detailpropertyid=$request->input('detailpropertyid');
        $valuestoupdate=$request->input('valuestoupdate');
        $msgerror="";
        try {
            //updaten  existing or upload new  waterbill
            
            if($valuestoupdate){
                $allwaterbill=implode(",", $valuestoupdate);
                $eachwaterbill=explode(",", $allwaterbill);
                foreach ($eachwaterbill as $eachwater) {
                    $mms=explode("?", $eachwater);
                    $hid=$mms[0];
                    $housename=$mms[1];
                    $Tenant=$mms[2];
                    $tenantname=$mms[3];

                    $DateTrans=date('Y-m-d');
                    // $explomonth=explode(' ', $month);
                    // $years=$explomonth[0];
                    // $months=$explomonth[1];
                    // $housename=Property::getHouseName(Property::decryptText($hid));
                    // $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;
                    
                    
                    $houseinfo = House::findOrFail(Property::decryptText($hid));
                    $houseinfo->$detailpropertyid=$UpdateValue;
                    if($houseinfo->save()){
                        
                    }
                    else{
                        $msgerror="Not Saved";
                    }

                    
                }
                //end of update
            }
        } 
        
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        

        if($msgerror=="Not Saved"){
            return response()->json([
                'status'=>200,
                'message'=>"House Updated Partially.\n Some House(s) information was not Update.\n Please try again",
            ]);
        }
        
        else{
            return response()->json([
                'status'=>200,
                'message'=>"Houses Updated Successfully.\n To Update A single House , Please Click Edit",
            ]);
        }
         
    }

    
    public static function setManageProperty(){
        
        $properties = Property::all();
        $thisproperty='';
        
        $propertyinfo= array();
        $sno=0;
        $allhouses=0;
        $alloccupiedhouses=0;
        foreach ($properties as $property) { 
            $allhouses =$allhouses + Property::getTotalHousesHse(Property::decryptText($property->id));
            $alloccupiedhouses =$alloccupiedhouses + Property::getTotalHousesOccupied(Property::decryptText($property->id));
        }
        $propertyinfo[] = array(
            'sno'=>$sno,
            'id' => 'all',
            'Plotcode' => 'all',
            'Plotname' => 'All Houses',
            'Plotarea' => 'all',
            'Plotaddr' => 'all',
            'Plotdesc' => 'all',
            'Waterbill' => 'n/a',
            'Deposit' => 'n/a',
            'Waterdeposit' => 'n/a',
            'Outsourced' => 'n/a',
            'Garbage' =>'n/a',
            'Kplcdeposit' => 'n/a',
            'propertytype' => 'n/a',
            'propertytypename' => 'n/a',
            'totalhouses' => $allhouses,
            'totaloccupied' => $alloccupiedhouses,
            'created_at' => ''
        );
        $sno++;

        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'propertytype' => $property->propertytype,
                'propertytypename' => Property::getPropertyTypeName($property->propertytype),
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }
        
        
        
        return response()->json([
            'status'=>200,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'message'=>'Found '.($sno).' Properties',
        ]);
    }

    public static function manageProperty($id){
        
        $properties = Property::all();
        $propertyinfo= array();
        $sno11=0;

        $allhouses=0;
        $alloccupiedhouses=0;
        foreach ($properties as $property) { 
            $allhouses =$allhouses + Property::getTotalHousesHse(Property::decryptText($property->id));
            $alloccupiedhouses =$alloccupiedhouses + Property::getTotalHousesOccupied(Property::decryptText($property->id));
        }
        $propertyinfo[] = array(
            'sno11'=>$sno11,
            'id' => 'all',
            'Plotcode' => 'ALL',
            'Plotname' => 'ALL HOUSES',
            'Plotarea' => 'all',
            'Plotaddr' => 'all',
            'Plotdesc' => 'all',
            'Waterbill' => 'n/a',
            'Deposit' => 'n/a',
            'Waterdeposit' => 'n/a',
            'Outsourced' => 'n/a',
            'Garbage' =>'n/a',
            'Kplcdeposit' => 'n/a',
            'propertytype' => 'n/a',
            'propertytypename' => 'n/a',
            'totalhouses' => $allhouses,
            'totaloccupied' => $alloccupiedhouses,
            'created_at' => ''
        );
        $sno11++;

        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno11'=>$sno11,
                'id' =>  $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'propertytype' => $property->propertytype,
                'propertytypename' => Property::getPropertyTypeName($property->propertytype),
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno11++;
        }

        $thisproperty='';
        // $houseinfo='';
        if($id==''){
            $thisproperty='';
            // $houseinfo='';
        }
        else if($id=='all'){
            // $thisproperty='';
            $thispropert= array(
                'id' => $id,
                'Plotcode' => 'ALL',
                'Plotname' => 'ALL HOUSES',
                'Plotarea' => 'all',
                'Plotaddr' => 'all',
                'Plotdesc' => 'all',
                'Waterbill' => 'n/a',
                'Deposit' => 'n/a',
                'Waterdeposit' => 'n/a',
                'Outsourced' => 'n/a',
                'Garbage' => 'n/a',
                'Kplcdeposit' => 'n/a',
                'created_at' => '',
                'updated_at' => ''
            );
            $thisproperty=$thispropert;

            // $houseinfo='';
        }
        else{
            $thisproperty=Property::findOrFail(Property::decryptText($id));
            // $thispropert= array();
            // $thispropert[] = array(
            //     'id' => $id,
            //     'Plotcode' => $thisproperty->Plotcode,
            //     'Plotname' => $thisproperty->Plotname,
            //     'Plotarea' => $thisproperty->Plotarea,
            //     'Plotaddr' => $thisproperty->Plotaddr,
            //     'Plotdesc' => $thisproperty->Plotdesc,
            //     'Waterbill' => $thisproperty->Waterbill,
            //     'Deposit' => $thisproperty->Deposit,
            //     'Waterdeposit' => $thisproperty->Waterdeposit,
            //     'Outsourced' => $thisproperty->Outsourced,
            //     'Garbage' => $thisproperty->Garbage,
            //     'Kplcdeposit' => $thisproperty->Kplcdeposit,
            //     'created_at' => $thisproperty->created_at,
            //     'updated_at' => $thisproperty->updated_at
            // );
            // $thisproperty=$thispropert;
            // $houseinfo=House::where('Plot',$id)->get();
        }

        $sno=0;
        $housescount=0;
        if($id=='vacant'){
            $housesinfos = House::orderByDesc('id')->where('Status','Vacant')->get();
            $housescount = House::where('Status','Vacant')->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant(Property::decryptText($houseid));
                $plotcode=Property::getPropertyCode(Property::decryptText($house->plot));
                $plotname=Property::getPropertyName(Property::decryptText($house->plot));
                $tenantname='';
                if ($tenant=='') {
                    $tenant='Vacated';
                    $tenantname='Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantFName($tenant);
                }
                $payments[] = array(
                    'id' =>         $house->id,
                    'Plot'=>        $house->plot,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      Property::encryptText($tenant),
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>    $plotcode,
                    'plotname'=>    $plotname,
                    'Rent'=>        $house->Rent,
                    'Deposit'=>     $house->Deposit,
                    'Water' =>      $house->Water,
                    'Lease' =>      $house->Lease,
                    'Garbage' =>    $house->Garbage,
                    'DueDay' =>     $house->DueDay,
                    'Status' =>     $house->Status,
                    'Kplc' =>       $house->Kplc,
                    'housetype' =>  $house->housetype,
                    'housetypename' => Property::getPropertyTypeName($house->housetype),
                    'created_at' => $house->created_at,
                );
            }
            $housesinfo=$payments;
            // return compact('housesinfo','housescount');
        }
        else if($id=='all'){
            $housesinfos = House::all();
            $housescount = House::all()->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant(Property::decryptText($houseid));
                $plotcode=Property::getPropertyCode(Property::decryptText($house->plot));
                $plotname=Property::getPropertyName(Property::decryptText($house->plot));
                $tenantname='';
                if ($tenant=='') {
                    $tenant='Vacated';
                    $tenantname='Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantFName($tenant);
                }
                $payments[] = array(
                    'id' =>         $house->id,
                    'Plot'=>        $house->plot,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      Property::encryptText($tenant),
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>    $plotcode,
                    'plotname'=>    $plotname,
                    'Rent'=>        $house->Rent,
                    'Deposit'=>     $house->Deposit,
                    'Water' =>      $house->Water,
                    'Lease' =>      $house->Lease,
                    'Garbage' =>    $house->Garbage,
                    'DueDay' =>     $house->DueDay,
                    'Status' =>     $house->Status,
                    'Kplc' =>       $house->Kplc,
                    'housetype' =>  $house->housetype,
                    'housetypename' => Property::getPropertyTypeName($house->housetype),
                    'created_at' => $house->created_at,
                );
            }

            // return $currentlyassigned=DB::table('agreements')->where([
            //     'Month'=>0
            // ])->max('tenant');
            // return $currentlyassigned;

            // return Agreement::all();
            $housesinfo=$payments;
            // return compact('housesinfo','housescount');
        }
        else{
            $housesinfos = House::where('Plot',Property::decryptText($id))->get();
            $housescount = House::where('Plot',Property::decryptText($id))->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant(Property::decryptText($houseid));
                $plotcode=Property::getPropertyCode(Property::decryptText($house->plot));
                $plotname=Property::getPropertyName(Property::decryptText($house->plot));
                $tenantname='';
                if ($tenant=='') {
                    $tenant='Vacated';
                    $tenantname='Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantFName($tenant);
                }
                $payments[] = array(
                    'id' =>         $house->id,
                    'Plot'=>        $id,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      Property::encryptText($tenant),
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>    $plotcode,
                    'plotname'=>    $plotname,
                    'Rent'=>        $house->Rent,
                    'Deposit'=>     $house->Deposit,
                    'Water' =>      $house->Water,
                    'Lease' =>      $house->Lease,
                    'Garbage' =>    $house->Garbage,
                    'DueDay' =>     $house->DueDay,
                    'Status' =>     $house->Status,
                    'Kplc' =>       $house->Kplc,
                    'housetype' =>  $house->housetype,
                    'housetypename' => Property::getPropertyTypeName($house->housetype),
                    'created_at' => $house->created_at,
                );
            }

            // return $currentlyassigned=DB::table('agreements')->where([
            //     'Month'=>0
            // ])->max('tenant');
            // return $currentlyassigned;

            // return Agreement::all();
            $housesinfo=$payments;
            // return compact('housesinfo','housescount');
        }
        
        
        return response()->json([
            'status'=>200,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'houseinfo' =>$housesinfo,
            'message'=>'Found '.($housescount).' Houses',
        ]);
    }

    public static function setManageTenant(){
        
        // $tenants = Tenant::orderByDesc('id')->where('Status','New')->orwhere('Status','Vacated')->get();
        $tenants = Tenant::orderByDesc('id')->where('Status','New')->get();
        $thisproperty='';
        $properties = Property::all();
        
        $tenantinfo= array();
        $sno=0;
        foreach ($tenants as $property) { 
            $tenantinfo[] = array(
                'sno'=>         $sno,
                'id' =>         $property->id,
                'Fname' =>      $property->Fname,
                'Oname' =>      ucwords(strtolower($property->Oname)),
                'Gender' =>     $property->Gender,
                'IDno' =>       $property->IDno,
                'Phone' =>       $property->Phone,
                'PhoneMasked'=> Property::getTenantPhoneMask($property->Phone),
                'Email' =>      $property->Email,
                'Status' =>     $property->Status,
                'Houses'=>      Property::tenantHousesAssigned(Property::decryptText($property->id)),
                'housesdata'=>  Property::tenantHousesOccupiedDataOnly(Property::decryptText($property->id)),
                'Housenames'=>  Property::tenantHousesOccupiedOnly(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }
        

        $propertyinfo= array();
        $sno1=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno1'=>$sno1,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno1++;
        }
        
        
        return response()->json([
            'status'=>200,
            'propertyinfo'=>$propertyinfo,
            'tenantinfo'=>$tenantinfo,
            'thisproperty'=>$thisproperty,
            'message'=>'Found '.($sno).' Tenants',
        ]);
    }

    public static function setManageTenantCategory(){
        
        // $tenants = Tenant::orderByDesc('id')->where('Status','New')->orwhere('Status','Vacated')->get();
        $tenants = Tenant::orderByDesc('id')->where('Status','New')->get();
        $thisproperty='';
        $properties = Property::all();
        
        $tenantinfo= array();
        $sno=0;
        foreach ($tenants as $property) { 
            $tenantinfo[] = array(
                'sno'=>         $sno,
                'id' =>         $property->id,
                'Fname' =>      $property->Fname,
                'Oname' =>      ucwords(strtolower($property->Oname)),
                'Gender' =>     $property->Gender,
                'IDno' =>       $property->IDno,
                'Phone' =>       $property->Phone,
                'PhoneMasked'=> Property::getTenantPhoneMask($property->Phone),
                'Email' =>      $property->Email,
                'Status' =>     $property->Status,
                'Houses'=>      Property::tenantHousesAssigned(Property::decryptText($property->id)),
                'housesdata'=>  Property::tenantHousesOccupiedDataOnly(Property::decryptText($property->id)),
                'Housenames'=>  Property::tenantHousesOccupiedOnly(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }
        

        $propertyinfo= array();
        $sno1=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno1'=>$sno1,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno1++;
        }
        
        
        return response()->json([
            'status'=>200,
            'propertyinfo'=>$propertyinfo,
            'tenantinfo'=>$tenantinfo,
            'thisproperty'=>$thisproperty,
            'message'=>'Found '.($sno).' Tenants',
        ]);
    }

    public static function manageTenantINCategory($id){
        $properties = Property::all();
        $thisproperty='';
        // $houseinfo='';
        $sno=0;
        $housescount=0;
        if($id==''){
            $thisproperty='';
            // $houseinfo='';
        }
        // else{
        //     $thisproperty=Property::findOrFail($id);
        //     // $houseinfo=House::where('Plot',$id)->get();
        // }

       
        else if($id=='vacant'){
            $housesinfos = House::orderByDesc('id')->where('Status','Vacant')->get();
            $housescount = House::where('Status','Vacant')->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant(Property::decryptText($houseid));
                $plotcode=Property::getPropertyCode(Property::decryptText($house->plot));
                $plotname=Property::getPropertyName(Property::decryptText($house->plot));
                $tenantname='';
                if ($tenant=='') {
                    $tenant='Vacated';
                    $tenantname='Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantFName(Property::decryptText($tenant));
                }
                $payments[] = array(
                    'id' =>         $house->id,
                    'Plot'=>        $house->plot,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      $tenant,
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>    $plotcode,
                    'plotname'=>    $plotname,
                    'Rent'=>        $house->Rent,
                    'Deposit'=>     $house->Deposit,
                    'Water' =>      $house->Water,
                    'Lease' =>      $house->Lease,
                    'Garbage' =>    $house->Garbage,
                    'DueDay' =>     $house->DueDay,
                    'Status' =>     $house->Status,
                    'Kplc' =>       $house->Kplc,
                    'created_at' => $house->created_at,
                );
            }
            $housesinfo=$payments;
            // return compact('housesinfo','housescount');
        }
        else if($id=='Vacated' || $id=='New' || $id=='Assigned' || $id=='Reassigned' || $id=='Other' || $id=='Transferred'){
            $tenants = Tenant::orderByDesc('id')->where('Status',$id)->get();
            
            $tenantinfo= array();
            $sno=0;
            foreach ($tenants as $property) { 
                $tenantinfo[] = array(
                    'sno'=>         $sno,
                    'id' =>         $property->id,
                    'Fname' =>      $property->Fname,
                    'Oname' =>      ucwords(strtolower($property->Oname)),
                    'Gender' =>     $property->Gender,
                    'IDno' =>       Property::getTenantIDNoMask($property->IDno),
                    'Phone' =>       $property->Phone,
                    'PhoneMasked'=> Property::getTenantPhoneMask($property->Phone),
                    'Email' =>      $property->Email,
                    'Status' =>     $property->Status,
                    'Houses'=>      Property::tenantHousesAssigned(Property::decryptText($property->id)),
                    'housesdata'=>  Property::tenantHousesOccupiedDataOnly(Property::decryptText($property->id)),
                    'Housenames'=>  Property::tenantHousesOccupiedOnly(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $housesinfo=$tenantinfo;
        }
        else if($id=='All'){
            $tenants = Tenant::orderByDesc('id')->get();
            
            $tenantinfo= array();
            $sno=0;
            foreach ($tenants as $property) { 
                $tenantinfo[] = array(
                    'sno'=>         $sno,
                    'id' =>         $property->id,
                    'Fname' =>      $property->Fname,
                    'Oname' =>      ucwords(strtolower($property->Oname)),
                    'Gender' =>     $property->Gender,
                    'IDno' =>       Property::getTenantIDNoMask($property->IDno),
                    'Phone' =>       $property->Phone,
                    'PhoneMasked'=> Property::getTenantPhoneMask($property->Phone),
                    'Email' =>      $property->Email,
                    'Status' =>     $property->Status,
                    'Houses'=>      Property::tenantHousesAssigned(Property::decryptText($property->id)),
                    'housesdata'=>  Property::tenantHousesOccupiedDataOnly(Property::decryptText($property->id)),
                    'Housenames'=>  Property::tenantHousesOccupiedOnly(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            $housesinfo=$tenantinfo;
        }

        else{
            $thisproperty=Property::findOrFail(Property::decryptText($id));
            
            $thispropert[] = array(
                'id' => $id,
                'Plotcode' => $thisproperty->Plotcode,
                'Plotname' => $thisproperty->Plotname,
                'Plotarea' => $thisproperty->Plotarea,
                'Plotaddr' => $thisproperty->Plotaddr,
                'Plotdesc' => $thisproperty->Plotdesc,
                'Waterbill' => $thisproperty->Waterbill,
                'Deposit' => $thisproperty->Deposit,
                'Waterdeposit' => $thisproperty->Waterdeposit,
                'Outsourced' => $thisproperty->Outsourced,
                'Garbage' => $thisproperty->Garbage,
                'Kplcdeposit' => $thisproperty->Kplcdeposit,
                'created_at' => $thisproperty->created_at,
                'updated_at' => $thisproperty->updated_at
            );

            $thisproperty=$thispropert;
            
            $agreements=Agreement::orderByDesc('id')->where('plot',Property::decryptText($id))->where('Month',0)->get();
            $agreementinfo= array();
            $sno2=0;
            foreach ($agreements as $agreement) {
                $plotcode=Property::getPropertyCode($agreement->plot);
                $plotname=Property::getPropertyName($agreement->plot);

                $tenantfname=Property::TenantFNames($agreement->tenant);
                $Fname=Property::getTenantFname($agreement->tenant);
                $Oname=Property::getTenantOname($agreement->tenant);

                $tenantname=Property::TenantNames($agreement->tenant);

                $houseid=Property::checkCurrentTenantHouse($agreement->tenant);

                $agreementinfo[] = array(
                    'sno'=>             $sno2,
                    'id' =>             $agreement->tenant,
                    'aid' =>            $agreement->id,
                    'Plot'=>            $agreement->plot,
                    'House'=>           $agreement->house,
                    'Housename'=>       Property::getHouseName(Property::decryptText($houseid)),
                    'Tenant'=>          $agreement->tenant,
                    'Fname' =>          Property::getTenantFname(Property::decryptText($agreement->tenant)),
                    'Oname' =>          ucwords(strtolower(Property::getTenantOname(Property::decryptText($agreement->tenant)))),
                    'Phone'=>           Property::getTenantPhone(Property::decryptText($agreement->tenant)),
                    'PhoneMasked'=>     Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($agreement->tenant))),
                    'Email'=>           Property::getTenantEmail(Property::decryptText($agreement->tenant)),
                    'Gender'=>          Property::getTenantGender(Property::decryptText($agreement->tenant)),
                    'IDno'=>            Property::getTenantIDNoMask(Property::getTenantIDno(Property::decryptText($agreement->tenant))),
                    'Status'=>          Property::tenantStatus(Property::decryptText($agreement->tenant)),
                    'tenantname'=>      ucwords(strtolower($tenantname)),
                    'tenantfname'=>     ucwords(strtolower($tenantfname)),
                    'Houses'=>  Property::tenantHousesAssigned(Property::decryptText($agreement->tenant)),
                    'housesdata'=>  Property::tenantHousesOccupiedDataOnly(Property::decryptText($agreement->tenant)),
                    'Housenames'=>  Property::tenantHousesOccupiedOnly(Property::decryptText($agreement->tenant)),
                    'housesoccupied'=>  Property::tenantHousesOccupied(Property::decryptText($agreement->tenant),$agreement->house),
                    'plotcode'=>        $plotcode,
                    'plotname'=>        $plotname,
                    'Transaction'=>     $agreement->Transaction,
                    'Refund'=>          $agreement->Refund,
                    'Deposit'=>         $agreement->Deposit,
                    'Arrears' =>        $agreement->Arrears,
                    'Damages' =>        $agreement->Damages,
                    'Month' =>          $agreement->Month,
                    'DateVacated' =>    $agreement->DateVacated,
                    'DateTo' =>         $agreement->DateTo,
                    'DateAssigned' =>   Property::checkCurrentTenantHouseDateAssigned($agreement->id),
                    'created_at' =>     Property::getTenantCReatedAt(Property::decryptText($agreement->tenant)),
                );
                $sno2++;
            }


            
            $housesinfo=$agreementinfo;
            // return compact('housesinfo','housescount');
        }
        $totalproperties=0;
        $propertyinfo= array();
        $sno1=0;
        $alltotalproperties=($properties->count());

        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno1'=>$sno1,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse($property->id),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied($property->id),
                'created_at' => $property->created_at
            );
            $sno1++;
        }

        
        
        return response()->json([
            'status'=>200,
            'properties'=>$properties,
            'propertyinfo'=>$propertyinfo,
            'tenantinfo'=>$housesinfo,
            'thisproperty'=>$thisproperty,
            'message'=>'Action Loaded Succesfully',
        ]);
    }

    public static function manageTenantIN($id){
        $thistenant='';
        $sno=0;
        if($id==''){
            $thistenant='';
        }

        else{

            // $id=Property::decryptText($id);
            // $tenants = Tenant::orderByDesc('id')->where('id',$id)->get();
            // $tenantname=Property::TenantNames($id);
            // $thistenant= $tenants;
            $thistenant=Property::tenantsHidDataThis($id);
            // $thistenant['tenantname']=ucwords(strtolower($tenantname));
            // $thistenant[0]['id']=Property::encryptText($id);
            // $thistenant['Houses']=Property::tenantHousesAssigned($id);
            // $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly($id);
            // $thistenant['Housenames']=Property::tenantHousesOccupiedOnly($id);
            // $thistenant['Fname']=Property::getTenantFname($id);
            // $thistenant['Oname']=Property::getTenantOname($id);
            // $thistenant['Email']=Property::getTenantEmail($id);
            // $thistenant['Gender']=Property::getTenantGender($id);
            // $thistenant['IDno']=Property::getTenantIDNoMask(Property::getTenantIDno($id));
            
            // $thistenant['created_at']=Property::getTenantCReatedAt($id);
            // $thistenant['Status']=Property::getTenantStatus($id);
            // $thistenant['Phone']=Property::getTenantPhone($id);
            // $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone($id));
           
            

            // $agreements=Agreement::orderByDesc('id')->where('Tenant',$id)->where('Month',0)->get();
            $agreements=Agreement::orderBy('DateVacated')->where('tenant',Property::decryptText($id))->get();
            $agreementinfo= array();
            $sno2=0;
            foreach ($agreements as $agreement) {
                $plotcode=Property::getPropertyCode(Property::decryptText($agreement->plot));
                $plotname=Property::getPropertyName(Property::decryptText($agreement->plot));

                $tenantfname=Property::TenantFNames(Property::decryptText($agreement->tenant));
                $Fname=Property::getTenantFname(Property::decryptText($agreement->tenant));
                $Oname=Property::getTenantOname(Property::decryptText($agreement->tenant));

                $tenantname=Property::TenantNames(Property::decryptText($agreement->tenant));

                $houseid=Property::checkCurrentTenantHouse(Property::decryptText($agreement->tenant));

                $Status=($agreement->Month>0?"Vacated":Property::tenantStatus(Property::decryptText($agreement->tenant)));
                $agreementinfo[] = array(
                    'sno'=>             $sno2,
                    'id' =>             $id,
                    'ids' =>            $agreement->tenant,
                    'aid' =>            $agreement->id,
                    'Plot'=>            $agreement->plot,
                    'House'=>           $agreement->house,
                    'Housename'=>       Property::getHouseName(Property::decryptText($agreement->house)),
                    'Tenant'=>          $id,
                    'Fname' =>          Property::getTenantFname(Property::decryptText($agreement->tenant)),
                    'Oname' =>          ucwords(strtolower(Property::getTenantOname(Property::decryptText($agreement->tenant)))),
                    'Phone'=>           Property::getTenantPhone(Property::decryptText($agreement->tenant)),
                    'PhoneMasked'=>     Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($agreement->tenant))),
                    'Email'=>           Property::getTenantEmail(Property::decryptText($agreement->tenant)),
                    'Gender'=>          Property::getTenantGender(Property::decryptText($agreement->tenant)),
                    // 'IDno'=>            Property::getTenantIDno($agreement->tenant),
                    'IDno'=>            Property::getTenantIDNoMask(Property::getTenantIDno(Property::decryptText($agreement->tenant))),
                    'Status'=>          $Status,
                    'tenantname'=>      ucwords(strtolower($tenantname)),
                    'tenantfname'=>     ucwords(strtolower($tenantfname)),
                    'Houses'=>  Property::tenantHousesAssigned(Property::decryptText($agreement->tenant)),
                    'housesdata'=>  Property::tenantHousesOccupiedDataOnly(Property::decryptText($agreement->tenant)),
                    'Housenames'=>  Property::tenantHousesOccupiedOnly(Property::decryptText($agreement->tenant)),
                    'housesassigned'=>  Property::tenantHousesAssigned(Property::decryptText($agreement->tenant)),
                    'housesoccupied'=>  Property::tenantHousesOccupied(Property::decryptText($agreement->tenant),Property::decryptText($agreement->House)),
                    'plotcode'=>        $plotcode,
                    'plotname'=>        $plotname,
                    'Transaction'=>     $agreement->Transaction,
                    'Refund'=>          $agreement->Refund,
                    'Deposit'=>         $agreement->Deposit,
                    'Arrears' =>        $agreement->Arrears,
                    'Damages' =>        $agreement->Damages,
                    'Month' =>          $agreement->Month,
                    'DateVacated' =>    $agreement->DateVacated,
                    'DateTo' =>         $agreement->DateTo,
                    'DateAssigned' =>   Property::checkCurrentTenantHouseDateAssigned(Property::decryptText($agreement->id)),
                    'created_at' =>     Property::getTenantCReatedAt(Property::decryptText($agreement->tenant)),
                );
                $sno2++;
            }

            // $housesinfo=$agreementinfo;

        }


        $tenantsi= Property::tenantsHidData();

        $properties = Property::orderBy('id')->get();
        $housesinfoss = House::orderBy('id')->get();
        $housesinfo= $housesinfoss;
        // $count= $housesinfoss->count();
        // for ($i=0; $i < $count; $i++) { 
        //     $idds=$housesinfo[$i]->Plot;
        //     $idd=Property::encryptText($idds);
        //     $housesinfo[$i]->Plot=$idd;
        // }

        // return $housesinfo;
        
        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }

       
        return response()->json([
            'status'=>200,
            'properties'=>'',
            'thistenant'=>$thistenant,
            'tenantinfo'=>$tenantsi,
            'houseinfo'=>$housesinfo,
            'propertyinfo'=>$propertyinfo,
            'agreementinfo'=>$agreementinfo,
            'thisproperty'=>'',
            'message'=>'Tenant Loaded Succesfully',
        ]);
    }


    public static function manageHouseTenants($plot,$id){
        
        $properties = Property::all();

        $propertyinfo= array();
        $sno11=0;

        $allhouses=0;
        $alloccupiedhouses=0;
        foreach ($properties as $property) { 
            $allhouses =$allhouses + Property::getTotalHousesHse(Property::decryptText($property->id));
            $alloccupiedhouses =$alloccupiedhouses + Property::getTotalHousesOccupied(Property::decryptText($property->id));
        }
        $propertyinfo[] = array(
            'sno11'=>$sno11,
            'id' => 'all',
            'Plotcode' => 'ALL',
            'Plotname' => 'ALL HOUSES',
            'Plotarea' => 'all',
            'Plotaddr' => 'all',
            'Plotdesc' => 'all',
            'Waterbill' => 'n/a',
            'Deposit' => 'n/a',
            'Waterdeposit' => 'n/a',
            'Outsourced' => 'n/a',
            'Garbage' =>'n/a',
            'Kplcdeposit' => 'n/a',
            'propertytype' => 'n/a',
            'propertytypename' => 'n/a',
            'totalhouses' => $allhouses,
            'totaloccupied' => $alloccupiedhouses,
            'created_at' => ''
        );
        $sno11++;

        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno11'=>$sno11,
                // 'id' => $plot,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'propertytype' => $property->propertytype,
                'propertytypename' => Property::getPropertyTypeName($property->propertytype),
                'totalhouses' =>Property::getTotalHousesHse($property->id),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied($property->id),
                'created_at' => $property->created_at
            );
            $sno11++;
        }


        $thisproperty='';
        // $houseinfo='';
        if($plot==''){
            $thisproperty='';
            // $houseinfo='';
        }
        else if($plot=='all'){
            // $thisproperty='';
            $thispropert= array(
                'id' => $plot,
                'Plotcode' => 'ALL',
                'Plotname' => 'ALL HOUSES',
                'Plotarea' => 'all',
                'Plotaddr' => 'all',
                'Plotdesc' => 'all',
                'Waterbill' => 'n/a',
                'Deposit' => 'n/a',
                'Waterdeposit' => 'n/a',
                'Outsourced' => 'n/a',
                'Garbage' => 'n/a',
                'Kplcdeposit' => 'n/a',
                'propertytype' => 'n/a',
                'propertytypename' => 'n/a',
                'created_at' => '',
                'updated_at' => ''
            );
            $thisproperty=$thispropert;

            // $houseinfo='';
        }
        else{
            $plotid=Property::getHouseProperty(Property::decryptText($id));
            $thisproperty=Property::findOrFail(Property::decryptText($plotid));
            // $houseinfo=House::where('Plot',$id)->get();
        }

        if($plot=='vacant'){
            $housesinfo = House::orderByDesc('id')->where('Status','Vacant')->get();
        }
        else if($plot=='all'){
            $housesinfos = House::all();
            $housescount = House::all()->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant(Property::decryptText($houseid));
                $plotcode=Property::getPropertyCode(Property::decryptText($house->plot));
                $plotname=Property::getPropertyName(Property::decryptText($house->plot));
                $tenantname='';
                if ($tenant=='') {
                    $tenant='Vacated';
                    $tenantname='Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantFName($tenant);
                }
                $payments[] = array(
                    'id' =>         $house->id,
                    'Plot'=>        $house->plot,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      Property::encryptText($tenant),
                    'Tenant'=>      Property::encryptText($tenant),
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>    $plotcode,
                    'plotname'=>    $plotname,
                    'Rent'=>        $house->Rent,
                    'Deposit'=>     $house->Deposit,
                    'Water' =>      $house->Water,
                    'Lease' =>      $house->Lease,
                    'Garbage' =>    $house->Garbage,
                    'DueDay' =>     $house->DueDay,
                    'Status' =>     $house->Status,
                    'Kplc' =>       $house->Kplc,
                    'created_at' => $house->created_at,
                );
            }

            // return $currentlyassigned=DB::table('agreements')->where([
            //     'Month'=>0
            // ])->max('tenant');
            // return $currentlyassigned;

            // return Agreement::all();
            $housesinfo=$payments;
            // return compact('housesinfo','housescount');
        }
        else{
            $plotid=Property::getHouseProperty(Property::decryptText($id));
            $housesinfo = House::where('Plot',Property::decryptText($plotid))->get();
        }
        
        $propertyhouses = House::all();
        // $tenantsi = Tenant::orderByDesc('id')->get();
        // $tenantsiss = Tenant::orderByDesc('id')->get();
        $tenantsi= Property::tenantsHidData();
        // return House::findOrFail(Property::decryptText($id))?'yes':'no';
        try{
            if($thishouse =House::findOrFail(Property::decryptText($id))){
                $tenantt=Property::checkCurrentTenant(Property::decryptText($id));
                $tenanttname='';
                if ($tenantt=='') {
                    $tenanttname='Vacant';
                    $tenantt='';
                }
                else{
                    $tenanttname=Property::checkCurrentTenantFName($tenantt);
                }
    
                $thishouse['tenantname']=$tenanttname;
                $thishouse['tenant']=Property::checkCurrentTenantID($tenantt);
                $thishouse['tid']=Property::checkCurrentTenantID($tenantt);
                $thishouse['house']=$id;
                
                $curtenant=Property::decryptText(Property::checkCurrentTenantID($tenantt));

                $agreements=Agreement::orderByDesc('id')->where('House',Property::decryptText($id))->get();
                $agreementinfo= array();
                foreach ($agreements as $agreement) {
                    $plotcode=Property::getPropertyCode(Property::decryptText($agreement->plot));
                    $plotname=Property::getPropertyName(Property::decryptText($agreement->plot));
    
                    $tenantfname=Property::TenantFNames(Property::decryptText($agreement->tenant));
                    $tenantname=Property::TenantNames(Property::decryptText($agreement->tenant));
    
                    $houseid=Property::checkCurrentTenantHouse(Property::decryptText($agreement->tenant));
                    
                    $agreementinfo[] = array(
                        'id' =>             $agreement->id,
                        'Plot'=>            $agreement->plot,
                        'House'=>           $agreement->house,
                        'Housename'=>       Property::getHouseName($houseid),
                        'Tenant'=>          $agreement->tenant,
                        'iscurrent'=>       ($curtenant == Property::decryptText($agreement->tenant))?"Yes":"No",
                        'hid'=>             $agreement->house,
                        'tid'=>             Property::checkCurrentTenantID(Property::decryptText($agreement->tenant)),
                        'Phone'=>           Property::getTenantPhone(Property::decryptText($agreement->tenant)),
                        'PhoneMasked'=>     Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($agreement->tenant))),
                        'Email'=>           Property::getTenantEmail(Property::decryptText($agreement->tenant)),
                        'Gender'=>          Property::getTenantGender(Property::decryptText($agreement->tenant)),
                        'IDno'=>            Property::getTenantIDno(Property::decryptText($agreement->tenant)),
                        'Status'=>          Property::tenantStatus(Property::decryptText($agreement->tenant)),
                        'tenantname'=>      ucwords(strtolower($tenantname)),
                        'tenantfname'=>     ucwords(strtolower($tenantfname)),
                        'housesdata'=>      Property::tenantHousesOccupiedDataOnly(Property::decryptText($agreement->tenant)),
                        'housesassigned'=>  Property::tenantHousesAssigned(Property::decryptText($agreement->tenant)),
                        'housesoccupied'=>  Property::tenantHousesOccupied(Property::decryptText($agreement->tenant),Property::decryptText($agreement->house)),
                        'plotcode'=>        $plotcode,
                        'plotname'=>        $plotname,
                        'Transaction'=>     $agreement->Transaction,
                        'Refund'=>          $agreement->Refund,
                        'Deposit'=>         $agreement->Deposit,
                        'Arrears' =>        $agreement->Arrears,
                        'Damages' =>        $agreement->Damages,
                        'Month' =>          $agreement->Month,
                        'DateVacated' =>    $agreement->DateVacated,
                        'DateTo' =>         $agreement->DateTo,
                        'DateAssigned' =>   Property::checkCurrentTenantHouseDateAssigned(Property::decryptText($agreement->id)),
                        'created_at' =>     $agreement->created_at,
                    );
                }   

                // return agreementinfo;
            
            }
            else{
                $thishouse='';
                $agreementinfo='';
            }
        }
        catch(\Exception $ex){ 
            $thishouse='';
            $agreementinfo='';
        }
        

        
        return response()->json([
            'status'=>200,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'houseinfo' =>$housesinfo,
            'tenantinfo' =>$tenantsi,
            'thishouse' =>$thishouse,
            'propertyhouses' =>$propertyhouses,
            'agreementinfo' =>$agreementinfo,
            'message'=>'Found House Info',
        ]);
    }

    public static function manageVacateTenant($hid,$id){
        // $id=TenantID,$hid=HouseID
        // Tenant id=$id
        // House id=$hid
        
        try{
            $properties = Property::all();
            $thisproperty='';
            // $houseinfo='';
            if($hid=="None"){
                $hid=Property::checkCurrentTenantHouse(Property::decryptText($id));
                if($hid== null){
                    $hid='';
                }
                // return response()->json([
                //     'status'=>500,
                //     'message'=>$id,
                //     'hid' => $hid,
                // ]);
            }

            $idss=$id;
            if($id=="None"){
                $id=Property::checkCurrentTenant(Property::decryptText($hid));
                
                $idss=$id;
                $id = Property::encryptText($id);
            }
            

            $tenantstatus=Property::tenantStatus(Property::decryptText($id));
            
            // return response()->json([
            //     'status'=>500,
            //     'message'=>$id,
            //     'hid' => $hid,
            //     'tenantstatus' => $tenantstatus,
            // ]);
            
            if($tenantstatus =='Assigned' || $tenantstatus=='Reassigned' || $tenantstatus=='Transferred' ){
                $housestatus=Property::getHouseStatus(Property::decryptText($hid));
                if($housestatus=="Vacant"){
                    $hid=Property::checkCurrentTenantHouse(Property::decryptText($id));
                }
            }

            $housestatus=Property::getHouseStatus(Property::decryptText($hid));
            
            
            
            // $houseid=$house->id;
            $tenantt=Property::decryptText($id);
            $tenanttname='';
            if ($tenantt=='') {
                $tenanttname='Vacant';
                $tenantt='';
            }
            else{
                $tenanttname=Property::checkCurrentTenantFName(Property::decryptText($id));
            }

            
            
            //start of usage information
            if($tenantstatus=="Vacated" || $tenantstatus=="New" || $tenantstatus=="Other" || $hid==""){
                $payments='';
            }
            else{
                // return response()->json([
                //     'status'=>500,
                //     'message'=>$id,
                //     'hid' => $hid,
                //     'tenantstatus' => $tenantstatus,
                // ]);
                $houseshere= Agreement::where('Tenant',$tenantt)->where('House',Property::decryptText($hid))->where('Month',0)->get();
                $housescount=0;
                
                $payments= array();
                foreach ($houseshere as $agreement) {
                    $houseid=$agreement->house;
                    $aid=$agreement->id;
                    $DateAssigned=$agreement->DateAssigned;
                    $dateToMonthName=Property::dateToMonthNameValue($DateAssigned);
                    
                    $tenant=$tenantt;
                    
                    // if($house=House::where('id',$houseid)->where('status','Occupied')->get()->first()){
                    if($house=House::where('id',Property::decryptText($houseid))->get()->first()){
                        
                        $housescount++;
                        $plotcode=Property::getPropertyCode($agreement->plot);
                        $plotname=Property::getPropertyName($agreement->plot);
                        $tenantname=Property::checkCurrentTenantFName($tenant);
                        //payment info
                        $houseid= Property::decryptText($houseid);
                        $agreements=DB::table('agreements')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->get();
                        
                        $Arrears=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Arrears');
                
                        $Excess=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Excess');
                
                        $Rent=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Rent');
                
                        $Garbage=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Garbage');
                
                        $KPLC=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('KPLC');
                
                        $HseDeposit=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('HseDeposit');
                
                        $Water=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Water');
                
                        $Lease=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Lease');
                
                        $Waterbill=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Waterbill');
                
                        $Equity=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Equity');
                
                        $Cooperative=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Cooperative');
                
                        $Others=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Others');
                
                        $PaidUploaded=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('PaidUploaded');

                        $TotalUsed = $Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
                        $TotalPaid = $Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                        $Balance =   $TotalUsed-$TotalPaid;
                        $Refund =    ($Balance-($agreement->Deposit));

                        $payments[] = array(
                            'id' =>         $agreement->house,
                            'Plot'=>        $agreement->plot,
                            'aid' =>        $aid,
                            'Housename'=>   $house->Housename,
                            'House'=>       $hid,
                            'tenant'=>      $id,
                            'tenantname'=>  ucwords(strtolower($tenantname)),
                            'plotcode'=>    $plotcode,
                            'plotname'=>    $plotname,
                            'Rent'=>        $house->Rent,
                            'Deposit'=>     $agreement->Deposit,
                            'Water' =>      $house->Water,
                            'Lease' =>      $house->Lease,
                            'Garbage' =>    $house->Garbage,
                            'DueDay' =>     $house->DueDay,
                            'TotalUsed' =>  $TotalUsed,
                            'TotalPaid' =>  $TotalPaid,
                            'Balance' =>    $Balance,
                            'Refund'  =>    $Refund,
                            'Status' =>     $house->Status,
                            'Kplc' =>       $house->Kplc,
                            'dateToMonthName' => $dateToMonthName,
                            'created_at' => $house->created_at,
                        );
                    }
                }
            }
            //end of usage information

            $tenantfullname=Property::TenantNames($tenantt);
            // $thishouse='';
            if($hid==""){
                $thishouse='';
            }
            else{
                $thishouse=House::findOrFail(Property::decryptText($hid));
                $thishouse['tenantfullname']=$tenantfullname;
                $thishouse['tenantname']=$tenanttname;
                $thishouse['tenant']=$id;
            }
            // $thishouse=Property::housesHidDataThis($hid,$id,$tenanttname,$tenantfullname);

            
            $propertyhouses = House::all();
            if($housestatus=="Vacant"){
                $agreementinfo='';
            }
            else{
                $agreements=Agreement::orderByDesc('id')->where('House',Property::decryptText($hid))->where('Month',0)->get();
                $agreementinfo= array();
                foreach ($agreements as $agreement) {
                    $plotcode=Property::getPropertyCode(Property::decryptText($agreement->Plot));
                    $plotname=Property::getPropertyName(Property::decryptText($agreement->Plot));

                    $tenantfname=Property::TenantFNames(Property::decryptText($agreement->tenant));
                    $tenantname=Property::TenantNames(Property::decryptText($agreement->tenant));

                    $houseid=Property::checkCurrentTenantHouse(Property::decryptText($agreement->tenant));
                    
                    $agreementinfo[] = array(
                        'id' =>             $agreement->id,
                        'Plot'=>            $agreement->plot,
                        'House'=>           $agreement->house,
                        'Housename'=>       Property::getHouseName(Property::decryptText($houseid)),
                        'Tenant'=>          $agreement->tenant,
                        'Phone'=>           Property::getTenantPhone(Property::decryptText($agreement->tenant)),
                        'PhoneMasked'=>     Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($agreement->tenant))),
                        'Email'=>           Property::getTenantEmail(Property::decryptText($agreement->tenant)),
                        'Gender'=>          Property::getTenantGender(Property::decryptText($agreement->tenant)),
                        'IDno'=>            Property::getTenantIDno(Property::decryptText($agreement->tenant)),
                        'Status'=>          Property::tenantStatus(Property::decryptText($agreement->tenant)),
                        'tenantname'=>      ucwords(strtolower($tenantname)),
                        'tenantfname'=>     ucwords(strtolower($tenantfname)),
                        'housesassigned'=>  Property::tenantHousesAssigned(Property::decryptText($agreement->tenant)),
                        'housesoccupied'=>  Property::tenantHousesOccupied(Property::decryptText($agreement->tenant),Property::decryptText($agreement->house)),
                        'plotcode'=>        $plotcode,
                        'plotname'=>        $plotname,
                        'Transaction'=>     $agreement->Transaction,
                        'Refund'=>          $agreement->Refund,
                        'Deposit'=>         $agreement->Deposit,
                        'Arrears' =>        $agreement->Arrears,
                        'Damages' =>        $agreement->Damages,
                        'Month' =>          $agreement->Month,
                        'DateVacated' =>    $agreement->DateVacated,
                        'DateTo' =>         $agreement->DateTo,
                        'DateAssigned' =>   Property::checkCurrentTenantHouseDateAssigned($agreement->id),
                        'created_at' =>     $agreement->created_at,
                    );
                }
            }

            $thistenant=Property::tenantsHidDataThisVac($id,$hid);
            // $tenants = Tenant::orderByDesc('id')->where('id',$id)->get();
            // $tenantname=Property::TenantNames($id);
            // $thistenant= $tenants;
            // $thistenant['tenantname']=ucwords(strtolower($tenantname));
            // $thistenant['id']=Property::encryptText($id);
            // $thistenant['Houses']=Property::tenantHousesAssigned($id);
            // $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly($id);
            $thistenant['Housenames']=Property::getHouseName(Property::decryptText($hid));
            // $thistenant['Fname']=Property::getTenantFname($id);
            // $thistenant['Oname']=Property::getTenantOname($id);
            // $thistenant['Email']=Property::getTenantEmail($id);
            // $thistenant['Gender']=Property::getTenantGender($id);
            // $thistenant['IDno']=Property::getTenantIDno($id);
            // $thistenant['created_at']=Property::getTenantCReatedAt($id);
            // $thistenant['Status']=Property::getTenantStatus($id);
            // $thistenant['Phone']=Property::getTenantPhone($id);
            // $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone($id));
            
            // ['Assigned','New','Vacated','Reassigned','Transferred','Other'
            // $tenantsi = Tenant::orderByDesc('id')->where('Status','Assigned')->orwhere('Status','Reassigned')->orwhere('Status','Transferred')->get();
            $tenantsi= Property::tenantsHidDataVacate();
            $properties = Property::orderBy('id')->get();
            $housesinfo = House::orderByDesc('id')->where('Status','Occupied')->get();
            
            $propertyinfo= array();
            $sno=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => $property->id,
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                    // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                    'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                    'created_at' => $property->created_at
                );
                $sno++;
            }

            return response()->json([
                'status'=>200,
                'hid' =>$hid,
                'tid' =>$id,
                'thishouse' =>$thishouse,
                'tenantinfo'=>$tenantsi,
                'houseinfo'=>$housesinfo,
                'propertyinfo'=>$propertyinfo,
                'thistenant'=>$thistenant,
                'agreementinfo' =>$agreementinfo,
                'payments'=>$payments,
                'message'=>'Found House Info',
            ]);
        }
        catch(\Illuminate\Database\QueryException $ex){ 

            $errors=$ex->getMessage();
            // 2002
            $beingusederror='No connection could be made because the target machine actively refused it';

            $error=$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Connection has been Lost. Please Contact Support\n";
            }

            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }


    public static function manageReassignTenant($hid,$id,$shid){
        // $id=TenantID,$hid=HouseID,$shid=HouseID Changing to
        try{
            $properties = Property::all();
            $thisproperty='';
            
            $hid=Property::decryptText($hid);
            $id=Property::decryptText($id);
            if($hid=="None"){
                $hid=Property::checkCurrentTenantHouse($id);
            }

            if($id=="None"){
                $id=Property::checkCurrentTenant($hid);
            }

            $tenantstatus=Property::tenantStatus($id);
            
            
            if($tenantstatus =='Assigned' || $tenantstatus=='Reassigned' || $tenantstatus=='Transferred' ){
                $housestatus=Property::getHouseStatus($hid);
                if($housestatus=="Vacant"){
                    $hid=Property::checkCurrentTenantHouse($id);
                }
            }

            $housestatus=Property::getHouseStatus($hid);
            // return response()->json([
            //     'status'=>200,
            //     'hid' =>$hid,
            //     'tid' =>$id,
            //     'status'=>$housestatus,
            //     'tenantsst'=>$tenantstatus,
            //     'message'=>'Found House Info',
            // ]);

            
            
            $curhouse=House::findOrFail($hid);
            $thishouse='';
            if($shid=="None"){
                $thishouse='';
                $shid='';
            }
            else{
                $thishouse=House::findOrFail(Property::decryptText($shid));
            }
            
            // $houseid=$house->id;
            $tenantt=$id;
            $tenanttname='';
            if ($tenantt=='') {
                $tenanttname='Vacant';
                $tenantt='';
            }
            else{
                $tenanttname=Property::checkCurrentTenantFName($id);
            }

            

            //start of usage information
            if($tenantstatus=="Vacated" || $tenantstatus=="New" || $tenantstatus=="Other"){
                $payments='';
            }
            else{
                $houseshere= Agreement::where('Tenant',$tenantt)->where('House',$hid)->where('Month',0)->get();
                $housescount=0;
                
                $payments= array();
                foreach ($houseshere as $agreement) {
                    $houseid=Property::decryptText($agreement->house);
                    $aid=Property::decryptText($agreement->id);
                    $DateAssigned=$agreement->DateAssigned;
                    $dateToMonthName=Property::dateToMonthNameValue($DateAssigned);
                    
                    $tenant=$tenantt;
                    // if($house=House::where('id',$houseid)->where('status','Occupied')->get()->first()){
                    if($house=House::where('id',$houseid)->get()->first()){
                        $housescount++;
                        $plotcode=Property::getPropertyCode(Property::decryptText($agreement->plot));
                        $plotname=Property::getPropertyName(Property::decryptText($agreement->plot));
                        $tenantname=Property::checkCurrentTenantFName($tenant);
                        //payment info
                        $agreements=DB::table('agreements')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->get();
                        
                        $Arrears=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Arrears');
                
                        $Excess=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Excess');
                
                        $Rent=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Rent');
                
                        $Garbage=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Garbage');
                
                        $KPLC=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('KPLC');
                
                        $HseDeposit=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('HseDeposit');
                
                        $Water=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Water');
                
                        $Lease=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Lease');
                
                        $Waterbill=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Waterbill');
                
                        $Equity=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Equity');
                
                        $Cooperative=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Cooperative');
                
                        $Others=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('Others');
                
                        $PaidUploaded=DB::table('payments')->where([
                            'Tenant'=>$tenantt,
                            'House'=>$houseid
                        ])->sum('PaidUploaded');

                        $TotalUsed = $Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
                        $TotalPaid = $Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                        $Balance =   $TotalUsed-$TotalPaid;
                        $Refund =    ($Balance-($agreement->Deposit));

                        $payments[] = array(
                            'id' =>         $agreement->house,
                            'Plot'=>        $agreement->plot,
                            'aid' =>        $aid,
                            'Housename'=>   $house->Housename,
                            'tenant'=>      $tenant,
                            'tenantname'=>  ucwords(strtolower($tenantname)),
                            'plotcode'=>    $plotcode,
                            'plotname'=>    $plotname,
                            'Rent'=>        $house->Rent,
                            'Deposit'=>     $agreement->Deposit,
                            'Water' =>      $house->Water,
                            'Lease' =>      $house->Lease,
                            'Garbage' =>    $house->Garbage,
                            'DueDay' =>     $house->DueDay,
                            'TotalUsed' =>  $TotalUsed,
                            'TotalPaid' =>  $TotalPaid,
                            'Balance' =>    $Balance,
                            'Refund'  =>    $Refund,
                            'Status' =>     $house->Status,
                            'Kplc' =>       $house->Kplc,
                            'dateToMonthName' => $dateToMonthName,
                            'created_at' => $house->created_at,
                        );
                    }
                }
            }
            //end of usage information

            $tenantfullname=Property::TenantNames($tenantt);
            $curhouse['tenantfullname']=$tenantfullname;
            $curhouse['tenantname']=$tenanttname;
            $curhouse['tenant']=Property::encryptText($tenantt);
            
            $propertyhouses = House::all();
            if($housestatus=="Vacant"){
                $agreementinfo='';
            }
            else{
                $agreements=Agreement::orderByDesc('id')->where('House',$hid)->where('Month',0)->get();
                $agreementinfo= array();
                foreach ($agreements as $agreement) {
                    $plotcode=Property::getPropertyCode($agreement->Plot);
                    $plotname=Property::getPropertyName($agreement->Plot);

                    $tenantfname=Property::TenantFNames($agreement->manageTenantINCategoryenant);
                    $tenantname=Property::TenantNames($agreement->manageTenantINCategoryenant);

                    $houseid=Property::checkCurrentTenantHouse($agreement->manageTenantINCategoryenant);
                    
                    $agreementinfo[] = array(
                        'id' =>             $agreement->id,
                        'Plot'=>            Property::encryptText($agreement->Plot),
                        'House'=>           Property::encryptText($agreement->House),
                        'Housename'=>       Property::getHouseName($houseid),
                        'Tenant'=>          Property::encryptText($agreement->manageTenantINCategoryenant),
                        'Phone'=>           Property::getTenantPhone($agreement->manageTenantINCategoryenant),
                        'PhoneMasked'=>     Property::getTenantPhoneMask(Property::getTenantPhone($agreement->manageTenantINCategoryenant)),
                        'Email'=>           Property::getTenantEmail($agreement->manageTenantINCategoryenant),
                        'Gender'=>          Property::getTenantGender($agreement->manageTenantINCategoryenant),
                        'IDno'=>            Property::getTenantIDno($agreement->manageTenantINCategoryenant),
                        'Status'=>          Property::tenantStatus($agreement->manageTenantINCategoryenant),
                        'tenantname'=>      ucwords(strtolower($tenantname)),
                        'tenantfname'=>     ucwords(strtolower($tenantfname)),
                        'housesassigned'=>  Property::tenantHousesAssigned($agreement->manageTenantINCategoryenant),
                        'housesoccupied'=>  Property::tenantHousesOccupied($agreement->manageTenantINCategoryenant,$agreement->House),
                        'plotcode'=>        $plotcode,
                        'plotname'=>        $plotname,
                        'Transaction'=>     $agreement->Transaction,
                        'Refund'=>          $agreement->Refund,
                        'Deposit'=>         $agreement->Deposit,
                        'Arrears' =>        $agreement->Arrears,
                        'Damages' =>        $agreement->Damages,
                        'Month' =>          $agreement->Month,
                        'DateVacated' =>    $agreement->DateVacated,
                        'DateTo' =>         $agreement->DateTo,
                        'DateAssigned' =>   Property::checkCurrentTenantHouseDateAssigned($agreement->id),
                        'created_at' =>     $agreement->created_at,
                    );
                }
            }

            $tenants = Tenant::orderByDesc('id')->where('id',$id)->get();
            $tenantname=Property::TenantNames($id);
            $thistenant= $tenants;
            $thistenant['tenantname']=ucwords(strtolower($tenantname));
            $thistenant['id']=Property::encryptText($id);
            $thistenant['Houses']=Property::tenantHousesAssigned($id);
            $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly($id);
            $thistenant['curhouse']=$curhouse;
            $thistenant['Housenames']=Property::getHouseName($hid);
            $thistenant['Fname']=Property::getTenantFname($id);
            $thistenant['Oname']=Property::getTenantOname($id);
            $thistenant['Email']=Property::getTenantEmail($id);
            $thistenant['Gender']=Property::getTenantGender($id);
            $thistenant['IDno']=Property::getTenantIDno($id);
            $thistenant['created_at']=Property::getTenantCReatedAt($id);
            $thistenant['Status']=Property::getTenantStatus($id);
            $thistenant['Phone']=Property::getTenantPhone($id);
            $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone($id));
            
            // ['Assigned','New','Vacated','Reassigned','Transferred','Other'
            // $tenantsi = Tenant::orderByDesc('id')->where('Status','Assigned')->orwhere('Status','Reassigned')->orwhere('Status','Transferred')->get();
            $tenantsi=Property::tenantsHidDataReassign();
            $properties = Property::orderBy('id')->get();
            $housesinfo = House::orderByDesc('id')->where('Status','Vacant')->get();

            $propertyinfo= array();
            $sno=0;
            foreach ($properties as $property) { 
                $propertyinfo[] = array(
                    'sno'=>$sno,
                    'id' => Property::encryptText($property->id),
                    'Plotcode' => $property->Plotcode,
                    'Plotname' => $property->Plotname,
                    'Plotarea' => $property->Plotarea,
                    'Plotaddr' => $property->Plotaddr,
                    'Plotdesc' => $property->Plotdesc,
                    'Waterbill' => $property->Waterbill,
                    'Deposit' => $property->Deposit,
                    'Waterdeposit' => $property->Waterdeposit,
                    'Outsourced' => $property->Outsourced,
                    'Garbage' => $property->Garbage,
                    'Kplcdeposit' => $property->Kplcdeposit,
                    'totalhouses' =>Property::getTotalHousesHse($property->id),
                    // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                    'totaloccupied' =>Property::getTotalHousesOccupied($property->id),
                    'created_at' => $property->created_at
                );
                $sno++;
            }
            
            return response()->json([
                'status'=>200,
                'hid' =>Property::encryptText($hid),
                'tid' =>Property::encryptText($id),
                'shid' =>$shid,
                'thishouse' =>$thishouse,
                'tenantinfo'=>$tenantsi,
                'houseinfo'=>$housesinfo,
                'propertyinfo'=>$propertyinfo,
                'thistenant'=>$thistenant,
                'agreementinfo' =>$agreementinfo,
                'payments'=>$payments,
                'message'=>'Found House Info',
            ]);
        }
        catch(\Illuminate\Database\QueryException $ex){ 

            $errors=$ex->getMessage();
            // 2002
            $beingusederror='No connection could be made because the target machine actively refused it';

            $error=$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Connection has been Lost. Please Contact Support\n";
            }

            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    
    public static function manageAssignTenant($hid,$id){
        
        $properties = Property::all();
        $thisproperty='';
        // $houseinfo='';
        $hid=Property::decryptText($hid);
        $id=Property::decryptText($id);
        $tenantt=$id;
        $tenanttname='';
        if ($tenantt=='') {
            $tenanttname='Vacant';
            $tenantt='';
        }
        else{
            $tenanttname=Property::checkCurrentTenantFName($id);
        }


        $tenantfullname=Property::TenantNames($tenantt);

        if($hid=="None"){
            $thishouse=House::where('Status','Vacant')->get()->first();
        }
        else{
            $thishouse=House::findOrFail($hid);
        }
        $thishouse['tenantfullname']=$tenantfullname;
        $thishouse['tenantname']=$tenanttname;
        $thishouse['tenant']=Property::encryptText($tenantt);
        
        $propertyhouses = House::all();
        
        

        $tenants = Tenant::orderByDesc('id')->where('id',$id)->get();
        $tenantname=Property::TenantNames($id);
        $thistenant= $tenants;
        $thistenant['tenantname']=ucwords(strtolower($tenantname));
        $thistenant['id']=Property::encryptText($id);
        $thistenant['Houses']=Property::tenantHousesAssigned($id);
        // $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly($id);
        $thistenant['Housenames']=Property::getHouseName($hid);
        $thistenant['Fname']=Property::getTenantFname($id);
        $thistenant['Oname']=Property::getTenantOname($id);
        $thistenant['Email']=Property::getTenantEmail($id);
        $thistenant['Gender']=Property::getTenantGender($id);
        $thistenant['IDno']=Property::getTenantIDno($id);
        $thistenant['created_at']=Property::getTenantCReatedAt($id);
        $thistenant['Status']=Property::getTenantStatus($id);
        $thistenant['Phone']=Property::getTenantPhone($id);
        $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone($id));
           
        
        // $tenantsi = Tenant::orderByDesc('id')->where('Status','New')->orwhere('Status','Vacated')->get();
        $tenantsi=Property::tenantsHidDataNew();
        $properties = Property::orderBy('id')->get();
        $housesinfo = House::orderByDesc('id')->where('Status','Vacant')->get();

        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse($property->id),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied($property->id),
                'created_at' => $property->created_at
            );
            $sno++;
        }
        
        return response()->json([
            'status'=>200,
            'thishouse' =>$thishouse,
            'tenantinfo'=>$tenantsi,
            'houseinfo'=>$housesinfo,
            'propertyinfo'=>$propertyinfo,
            'thistenant'=>$thistenant,
            'agreementinfo' =>'',
            'payments'=>'',
            'message'=>'Found House Info',
        ]);
    }

    public static function manageAddHouseTenant($hid,$id){
        
        $properties = Property::all();
        $thisproperty='';
        // $houseinfo='';
        
        $hid=Property::decryptText($hid);
        $id=Property::decryptText($id);
        
        $tenantt=$id;
        $tenanttname='';
        if ($tenantt=='') {
            $tenanttname='Vacant';
            $tenantt='';
        }
        else{
            $tenanttname=Property::checkCurrentTenantFName(Property::decryptText($id));
        }


        $tenantfullname=Property::TenantNames(Property::decryptText($id));

        if($hid=="None"){
            $thishouse=House::where('Status','Vacant')->get()->first();
        }
        else{
            $thishouse=House::findOrFail($hid);
        }
        $thishouse['tenantfullname']=$tenantfullname;
        $thishouse['tenantname']=$tenanttname;
        $thishouse['tenant']=$tenantt;
        
        $propertyhouses = House::all();
        
        

        $tenants = Tenant::orderByDesc('id')->where('id',Property::decryptText($id))->get();
        $tenantname=Property::TenantNames(Property::decryptText($id));
        $thistenant= $tenants;
        $thistenant['tenantname']=ucwords(strtolower($tenantname));
        $thistenant['id']=$id;
        $thistenant['Houses']=Property::tenantHousesAssigned(Property::decryptText($id));
        $thistenant['housesdatas']=Property::tenantHousesOccupiedOnly(Property::decryptText($id));
        $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly(Property::decryptText($id));
        $thistenant['Housenames']=Property::getHouseName($hid);
        $thistenant['Fname']=Property::getTenantFname(Property::decryptText($id));
        $thistenant['Oname']=Property::getTenantOname(Property::decryptText($id));
        $thistenant['Email']=Property::getTenantEmail(Property::decryptText($id));
        $thistenant['Gender']=Property::getTenantGender(Property::decryptText($id));
        $thistenant['IDno']=Property::getTenantIDno(Property::decryptText($id));
        $thistenant['created_at']=Property::getTenantCReatedAt(Property::decryptText($id));
        $thistenant['Status']=Property::getTenantStatus(Property::decryptText($id));
        $thistenant['Phone']=Property::getTenantPhone(Property::decryptText($id));
        $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($id)));
           
        
        // $tenantsi = Tenant::orderByDesc('id')->where('Status','Assigned')->orwhere('Status','Reassigned')->orwhere('Status','Transferred')->orwhere('Status','Other')->get();
        $tenantsi=Property::tenantsHidDataAddHouse();
        $properties = Property::orderBy('id')->get();
        $housesinfo = House::orderByDesc('id')->where('Status','Vacant')->get();

        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse($property->id),
                // 'totaltenants' =>Property::getTotalTenantsHse($property->id),
                'totaloccupied' =>Property::getTotalHousesOccupied($property->id),
                'created_at' => $property->created_at
            );
            $sno++;
        }
        
        return response()->json([
            'status'=>200,
            'thishouse' =>$thishouse,
            'tenantinfo'=>$tenantsi,
            'houseinfo'=>$housesinfo,
            'propertyinfo'=>$propertyinfo,
            'thistenant'=>$thistenant,
            'agreementinfo' =>'',
            'payments'=>'',
            'message'=>'Found House Info',
        ]);
    }
    
    
    public function plotsmgrhousesvacatetenant($id)
    {
        $id=Property::decryptText($id);
        $houseshere= Agreement::where('Tenant',$id)->where('Month',0)->get();
        $housescount=0;
        $payments= array();
        foreach ($houseshere as $agreement) {
            $houseid=$agreement->House;
            $aid=$agreement->id;
            $DateAssigned=$agreement->DateAssigned;
            $dateToMonthName=TenantController::dateToMonthName($DateAssigned);
            
            $tenant=$id;
            if($house=House::where('id',$houseid)->where('status','Occupied')->get()->first()){
                $housescount++;
                $plotcode=Property::getPropertyCode($agreement->Plot);
                $plotname=Property::getPropertyName($agreement->Plot);
                $tenantname=Property::checkCurrentTenantFName($tenant);
                //payment info
                $agreements=DB::table('agreements')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->get();
                
                $Arrears=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Arrears');
        
                $Excess=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Excess');
        
                $Rent=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Rent');
        
                $Garbage=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Garbage');
        
                $KPLC=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('KPLC');
        
                $HseDeposit=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('HseDeposit');
        
                $Water=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Water');
        
                $Lease=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Lease');
        
                $Waterbill=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Waterbill');
        
                $Equity=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Equity');
        
                $Cooperative=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Cooperative');
        
                $Others=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('Others');
        
                $PaidUploaded=DB::table('payments')->where([
                    'Tenant'=>$id,
                    'House'=>$houseid
                ])->sum('PaidUploaded');

                $TotalUsed = $Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
                $TotalPaid = $Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                $Balance =   $TotalUsed-$TotalPaid;
                $Refund =    ($Balance-($agreement->Deposit));

                $payments[] = array(
                    'id' =>         Property::encrypText($agreement->House),
                    'Plot'=>        Property::encryptText($agreement->Plot),
                    'aid' =>        $aid,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      Property::encrypText($tenant),
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>    $plotcode,
                    'plotname'=>    $plotname,
                    'Rent'=>        $house->Rent,
                    'Deposit'=>     $agreement->Deposit,
                    'Water' =>      $house->Water,
                    'Lease' =>      $house->Lease,
                    'Garbage' =>    $house->Garbage,
                    'DueDay' =>     $house->DueDay,
                    'TotalUsed' =>  $TotalUsed,
                    'TotalPaid' =>  $TotalPaid,
                    'Balance' =>    $Balance,
                    'Refund'  =>    $Refund,
                    'Status' =>     $house->Status,
                    'Kplc' =>       $house->Kplc,
                    'dateToMonthName' => $dateToMonthName,
                    'created_at' => $house->created_at,
                );
            }
        }
        $housesinfo=$payments;
        return compact('housesinfo','housescount');
    }
    
    public function deleteProperty(Request $request)
    {
        try{
            $id=$request->input('id');
            $plotname=Property::getPropertyName(Property::decryptText($id));
            if($property = Property::find(Property::decryptText($id))){
                $property->delete();
                Property::setUserLogs('Property '.$plotname.' Deleted');
                $success="Property has been Deleted.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                Property::setUserLogs('Property '.$plotname.' Deleted');
                $error="Property is Not Found.\n";
                return response()->json([
                    'status'=>401,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';

            $error="Property Not Deleted.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Property is Occupied.\n";
            }
            
            Property::setUserLogs('Property '.(Property::decryptText($id)).') Not Deleted.' .$error);
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Property Not Deleted.\n".$ex->getMessage();
            Property::setUserLogs('Property '.(Property::decryptText($id)).') Not Deleted.' .$error);
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function saveProperty(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Plotname' => ['required', 'string', 'max:100'],
            'Plotarea' => ['required', 'string', 'max:100'],
            'Plotcode' => ['required', 'string', 'max:100'],   
            'Plotaddr' => ['nullable', 'string', 'max:255'],
            'Waterbill' => ['required', 'string', 'max:100'],
            'Plotdesc' => ['nullable', 'string', 'max:255'],
            'Deposit' => ['required', 'string', 'max:100'],
            'Waterdeposit' => ['required', 'string', 'max:100'],
            'Outsourced' => ['required', 'string', 'max:100'],
            'Garbage' => ['required', 'string', 'max:100'],
            'Kplcdeposit' => ['required', 'string', 'max:100'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $propertyinfo = new Property;
                $propertyinfo->Plotname =$request->input('Plotname');
                $propertyinfo->Plotarea =$request->input('Plotarea');
                $propertyinfo->Plotcode =$request->input('Plotcode');
                if($request->input('Plotaddr')!="" || $request->input('Plotaddr') !=null) {
                    $propertyinfo->Plotaddr =$request->input('Plotaddr');
                }
                if($request->input('Plotdesc')!="" || $request->input('Plotdesc') !=null) {
                    $propertyinfo->Plotdesc =$request->input('Plotdesc');
                }
                $propertyinfo->Waterbill =$request->input('Waterbill');
                $propertyinfo->Deposit =$request->input('Deposit');
                $propertyinfo->Waterdeposit =$request->input('Waterdeposit');
                $propertyinfo->Outsourced =$request->input('Outsourced');
                $propertyinfo->Garbage =$request->input('Garbage');
                $propertyinfo->Kplcdeposit =$request->input('Kplcdeposit');
                if($request->input('propertyhousetypeid')!='') {
                    $propertyinfo->propertytype =$request->input('propertyhousetypeid');
                }
                if($propertyinfo->save()){
                    Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Added');
                    $success="Property Information has been Saved.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Not Saved');
                    $error="Property Information has not Been Saved.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Property Was Not Saved.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Property is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="Property Not Saved.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
        }
         
    }


    public function updateProperty(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Plotname' => ['required', 'string', 'max:100'],
            'Plotarea' => ['required', 'string', 'max:100'],
            'Plotcode' => ['required', 'string', 'max:100'],   
            'Plotaddr' => ['nullable', 'string', 'max:255'],
            'Waterbill' => ['required', 'string', 'max:100'],
            'Plotdesc' => ['nullable', 'string', 'max:255'],
            'Deposit' => ['required', 'string', 'max:100'],
            'Waterdeposit' => ['required', 'string', 'max:100'],
            'Outsourced' => ['required', 'string', 'max:100'],
            'Garbage' => ['required', 'string', 'max:100'],
            'Kplcdeposit' => ['required', 'string', 'max:100'],
        ]);

        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $id=$request->input('id');
                if($propertyinfo = Property::find(Property::decryptText($id))){
                    $propertyinfo->Plotname =$request->input('Plotname');
                    $propertyinfo->Plotarea =$request->input('Plotarea');
                    $propertyinfo->Plotcode =$request->input('Plotcode');
                    if($request->input('Plotaddr')!="" || $request->input('Plotaddr') !=null) {
                        $propertyinfo->Plotaddr =$request->input('Plotaddr');
                    }
                    if($request->input('Plotdesc')!="" || $request->input('Plotdesc') !=null) {
                        $propertyinfo->Plotdesc =$request->input('Plotdesc');
                    }
                    // $propertyinfo->Plotaddr =$request->input('Plotaddr');
                    // $propertyinfo->Plotdesc =$request->input('Plotdesc');
                    $propertyinfo->Waterbill =$request->input('Waterbill');
                    $propertyinfo->Deposit =$request->input('Deposit');
                    $propertyinfo->Waterdeposit =$request->input('Waterdeposit');
                    $propertyinfo->Outsourced =$request->input('Outsourced');
                    $propertyinfo->Garbage =$request->input('Garbage');
                    $propertyinfo->Kplcdeposit =$request->input('Kplcdeposit');
                    $propertyinfo->Kplcdeposit =$request->input('Kplcdeposit');
                    if($request->input('propertyhousetypeid')!='') {
                        $propertyinfo->propertytype =$request->input('propertyhousetypeid');
                    }
                    
                    if($propertyinfo->save()){
                        Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Updated');
                        $success="Property Information has been Updated.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Not Updated');
                        $error="Property Information has not Been Updated.\n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $error="Property is Not Found.\n";
                    return response()->json([
                        'status'=>401,
                        'message'=>$error,
                    ]);
                }
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingsavederror='1062';
                $beingusederror='1451';
                $error="Property Not Updated.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Property is Being Used.\n";
                }
                if (preg_match("/$beingsavederror/i", $errors)) {
                    $error="Property is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="Property Not Updated.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
        }
    }

    public function updateHouse(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Housename' => ['required', 'string', 'max:150'],
            'Rent' => ['required', 'integer'],
            'Deposit' => ['required', 'integer'],   
            'Kplc' => ['required', 'integer'],
            'Water' => ['required', 'integer'],
            'Lease' => ['required', 'integer'],
            'Garbage' => ['required', 'integer'],
            'DueDay' => ['required', 'integer', 'max:31'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $id=$request->input('hid');
                $id=Property::decryptText($id);
                if($houseinfo = House::find($id)){
                    $houseinfo->Housename =$request->input('Housename');
                    $houseinfo->Rent =$request->input('Rent');
                    $houseinfo->Kplc =$request->input('Kplc');
                    $houseinfo->Lease =$request->input('Lease');
                    $houseinfo->Garbage =$request->input('Garbage');
                    $houseinfo->Water =$request->input('Water');
                    $houseinfo->Deposit =$request->input('Deposit');
                    $houseinfo->DueDay =$request->input('DueDay');
                    if($request->input('propertyhousetypeid')!='') {
                        $houseinfo->housetype =$request->input('propertyhousetypeid');
                    }
                    if(Property::getHouseAgreement($id)>0){
                        $houseinfo->Status =$request->input('Status');
                    }
                    if($houseinfo->save()){
                        Property::setUserLogs('House '.Property::getHouseName($id).' Updated');
                        $success="House Information has been Updated.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        Property::setUserLogs('House '.Property::getHouseName($id).' Not Updated');
                        $error="House Information has not Been Updated.\n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $error="House is Not Found.\n";
                    return response()->json([
                        'status'=>401,
                        'message'=>$error,
                    ]);
                }
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingsavederror='1062';
                $beingusederror='1451';
                $error="House Not Updated.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="House is Being Used.\n";
                }
                if (preg_match("/$beingsavederror/i", $errors)) {
                    $error="House is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="House Not Updated.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
        }
         
    }

    
    public function deleteHouse(Request $request)
    {
        try{
            $id=$request->input('id');
            $id=Property::decryptText($id);
            if($property = House::find($id)){
                $property->delete();
                $success="House has been Deleted.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="House is Not Found.\n";
                return response()->json([
                    'status'=>401,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="House Not Deleted.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="House is Occupied.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="House Not Deleted.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function saveHouse(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Housename' => ['required', 'string', 'max:150'],
            'id' => ['required', 'string'],
            'Rent' => ['required', 'integer'],
            'Deposit' => ['required', 'integer'],   
            'Kplc' => ['required', 'integer'],
            'Water' => ['required', 'integer'],
            'Lease' => ['required', 'integer'],
            'Garbage' => ['required', 'integer'],
            'DueDay' => ['required', 'integer', 'max:31'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $plotid=$request->input('id');
            if(!Property::find(Property::decryptText($plotid))){
                $error="Property Selected is Not Found.\n";
                return response()->json([
                    'status'=>401,
                    'message'=>$error,
                ]);
            }

            $plotcode=$request->input('Plotcode');
            Property::setUserLogs('Saving New House for Property '. $plotcode);

            try { 
                $houseinfo = new House;
                $houseinfo->Housename =$request->input('Housename');
                $houseinfo->Plot =Property::decryptText($request->input('id'));
                $houseinfo->Rent =$request->input('Rent');
                $houseinfo->Deposit =$request->input('Deposit');
                $houseinfo->Kplc =$request->input('Kplc');
                $houseinfo->Lease =$request->input('Lease');
                $houseinfo->Water =$request->input('Water');
                $houseinfo->Garbage =$request->input('Garbage');
                $houseinfo->DueDay =$request->input('DueDay');
                if($request->input('propertyhousetypeid')!='') {
                    $houseinfo->housetype =$request->input('propertyhousetypeid');
                }
                if($houseinfo->save()){
                    Property::setUserLogs('New House '.$request->input('Housename').' Information Saved for Property '. $plotcode);
                    $success="House Information Saved!.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Information for House Not Saved for Property ". $plotcode.".\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="House Was Not Saved.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="House is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="House Not Saved.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }

    public function savePropertyHouseType(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'group' => ['required', 'string', 'max:150'],
            'typename' => ['required', 'string','max:150'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            $group=$request->input('group');
            $typename=$request->input('typename');
            $uniqID=$group.'-'.$typename;
            Property::setUserLogs('Saving New PropertyHouse ('.$typename.') type for group '. $group);

            try { 
                $propertyhousetype = new Propertyhousetype;
                $propertyhousetype->group =$group;
                $propertyhousetype->typename =$typename;
                $propertyhousetype->typeuniqueid =$uniqID;
                if($propertyhousetype->save()){
                    Property::setUserLogs('New PropertyHouse '.$typename.' Information Saved for group '. $group);
                    $success="Type Information Saved!.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    $error="Information for Type Not Saved for group ". $group.".\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Type Was Not Saved.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Type is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="Type Not Saved.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }

    public function updatePropertyHouseType(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'id' => ['required', 'numeric'],
            'typename' => ['required', 'string','max:150'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $id=$request->input('id');
            $group=$request->input('group');
            $typename=$request->input('typename');
            $uniqID=$group.'-'.$typename;
            Property::setUserLogs('Updating New PropertyHouse ('.$typename.') type for group '. $group);

            try { 
                if($propertyhousetype = Propertyhousetype::find($id)){
                    $propertyhousetype->typename =$typename;
                    $propertyhousetype->typeuniqueid =$uniqID;
                    if($propertyhousetype->save()){
                        Property::setUserLogs('New PropertyHouse '.$typename.' Information Updated for group '. $group);
                        $success="Type Information Updated!.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        $error="Information for Type Not Updated for group ". $group.".\n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $error="PropertyHouse Type is Not Found.\n";
                    return response()->json([
                        'status'=>401,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Type Was Not Updated.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Type is Already Updated.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="Type Not Updated.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }

    public function deletePropertyHouseType(Request $request)
    {
        try{
            $id=$request->input('id');
            if($property = Propertyhousetype::find($id)){
                $property->delete();
                $success="Type has been Deleted.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="Type is Not Found.\n";
                return response()->json([
                    'status'=>401,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Type Not Deleted.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Type is Assigned attached to House or Property.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Type Not Deleted.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function updateTenant(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Fname' => ['required', 'string', 'max:150'],
            'Oname' => ['required', 'string', 'max:150'],
            'Gender' => ['required', 'string'],   
            'Email' => ['nullable','email', 'string'],
            'Phone' => ['required', 'numeric','min:10'],
            'IDno' => ['required', 'integer'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $id=$request->input('id');
                $id=Property::decryptText($id);
                Property::setUserLogs('Updating Tenant '.Property::checkCurrentTenantName($id));
                if($tenantinfo = Tenant::find($id)){
                    $tenantinfo->Fname =$request->input('Fname');
                    $tenantinfo->Oname =$request->input('Oname');
                    $tenantinfo->Gender =$request->input('Gender');
                    $tenantinfo->Email =$request->input('Deposit');
                    $tenantinfo->Phone =$request->input('Phone');
                    $tenantinfo->IDno =$request->input('IDno');
                    if($tenantinfo->save()){
                        Property::setUserLogs('Tenant '.Property::checkCurrentTenantName($id).' Updated');
                        $success="Tenant Information has been Updated.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        Property::setUserLogs('Tenant '.Property::checkCurrentTenantName($id).' Not Updated');
                        $error="Tenant Information has not Been Updated.\n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    $error="Tenant is Not Found.\n";
                    return response()->json([
                        'status'=>401,
                        'message'=>$error,
                    ]);
                }
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingsavederror='1062';
                $beingusederror='1451';
                $error="Tenant Not Updated.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Tenant is Being Used.\n";
                }
                if (preg_match("/$beingsavederror/i", $errors)) {
                    $error="Tenant is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                $error="Tenant Not Updated.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
        }
         
    }

    
    public function deleteTenant(Request $request)
    {
        try{
            $id=$request->input('id');
            if($property = Tenant::find(Property::decryptText($id))){
                $property->delete();
                $success="Tenant has been Deleted.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="Tenant is Not Found.\n";
                return response()->json([
                    'status'=>401,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Tenant Not Deleted.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Tenant is Assigned House.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Tenant Not Deleted.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function saveTenant(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Fname' => ['required', 'string', 'max:150'],
            'Oname' => ['required', 'string', 'max:150'],
            'Gender' => ['required', 'string'],   
            'Email' => ['nullable','email', 'string'],
            'Phone' => ['required', 'numeric','min:10'],
            'IDno' => ['required', 'integer'],
            'Status' => ['required', 'string', 'max:150'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $tenantname=$request->input('Fname').' '.$request->input('Oname');
            Property::setUserLogs('Saving New Tenant '. $tenantname);

            try { 
                $tenantinfo = new Tenant;
                $tenantinfo->Fname =$request->input('Fname');
                $tenantinfo->Oname =$request->input('Oname');
                $tenantinfo->Gender =$request->input('Gender');
                $tenantinfo->Email =$request->input('Deposit');
                $tenantinfo->Phone =$request->input('Phone');
                $tenantinfo->IDno =$request->input('IDno');
                $tenantinfo->Status =$request->input('Status');
                if($tenantinfo->save()){
                    Property::setUserLogs($tenantname.' Tenant Information Saved');
                    $success="Tenant Information Saved!.\n";
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                    ]);
                }
                else{
                    Property::setUserLogs($tenantname.' Tenant Information not Saved with Error');
                    $error="Information for Tenant Not Saved : ". $tenantname.".\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                Property::setUserLogs($tenantname.' Tenant Information not Saved.');
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Tenant Was Not Saved.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Tenant is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                Property::setUserLogs($tenantname.' Tenant Information not Saved.');
                $error="House Not Saved.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }


    public function assignHouse(Request $request)
    {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'Refund' => ['required', 'numeric','min:0'],
            'InitialCharges' => ['required', 'numeric','min:0'],
            'MonthlyCharges' => ['required', 'numeric','min:0'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $hid=Property::decryptText($request->input('hid'));
            $tid=Property::decryptText($request->input('tid'));
            $DateAssigned=$request->input('DateAssigned');
            $Nature=$request->input('Nature');
            $tenantname=Property::checkCurrentTenantName($tid);

            $Refund=$request->input('Refund');
            $InitialCharges=$request->input('InitialCharges');
            $MonthlyCharges=$request->input('MonthlyCharges');

            $isHouseRent=$request->input('HouseRent');
            $isGarbage=$request->input('Garbage');
            $isHouseDeposit=$request->input('HouseDeposit');
            $isWaterDeposit=$request->input('WaterDeposit');
            $isKPLCDeposit=$request->input('KPLCDeposit');
            $isLease=$request->input('Lease');

            $Housename=Property::getHouseName($hid);
            Property::setUserLogs('Assigning Tenant '. $tenantname .' to House '. $Housename);
            try { 
                if((Property::TenantStatus($tid)=='New') || (Property::TenantStatus($tid)=='Vacated')){
            
                }
                else{
                    Property::setUserLogs('Tenant '. $tenantname .' Could not be Assgined to House '. $Housename);
                    $error='Tenant '.$tenantname." is already assigned House. \nPlease Choose Add House Option.";
                    
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
                
                
                ///

                if($vacanthouse=House::where('id',$hid)->where('status','Vacant')->get()->first()){
                    $Housename=$vacanthouse->Housename;
                    $Rent=$vacanthouse->Rent;
                    $Garbage=$vacanthouse->Garbage;
                    $KPLC=$vacanthouse->Kplc;
                    $HseDeposit=$vacanthouse->Deposit;
                    $Water=$vacanthouse->Water;
                    $Lease=$vacanthouse->Lease;
    
                    $deposits=$Refund;
                    $month=date_format(date_create($DateAssigned),'Y n');
                    // $pid=Property::getHousePropertyID($hid);
                    $pid=$vacanthouse->plot;
                    $tenantphone=Property::getTenantPhone($tid);
                    $uid=Property::decryptText($pid).' '.$hid.' '.$tid;
    
                    // return response()->json([
                    //     'status'=>500,
                    //     'message'=>$hid." ".$tid." ".Property::decryptText($pid),
                    // ]);

                    //Save/Assign New Tenant to house
                    $agreement = new Agreement;
                    $agreement->plot=Property::decryptText($pid);
                    $agreement->house=$hid;
                    $agreement->tenant=$tid;
                    $agreement->MonthAssigned=$month;
                    $agreement->DateAssigned=$DateAssigned;
                    $agreement->DateTo=$DateAssigned;
                    $agreement->Deposit=$deposits;
                    $agreement->Phone=$tenantphone;
                    $agreement->UniqueID=$uid;
    
                    if($agreement->save()){
                        // save bills information
                        $payments = new Payment;
                        $payments->Plot=Property::decryptText($pid);
                        $payments->House=$hid;
                        $payments->Tenant=$tid;
                        $payments->Month=$month;
                        $payments->save();

                        $paymentid=$payments->id;
                        // Property::updatePaymentsNew($paymentid,$isHouseRent?$Rent:0.00,$isGarbage?$Garbage:0.00,$isKPLCDeposit?$KPLC:0.00,$isHouseDeposit?$HseDeposit:0.00,$isWaterDeposit?$Water:0.00,$isLease?$Lease:0.00);

                        $updatepayments = Payment::findOrFail($paymentid);
                        $updatepayments->Rent =$isHouseRent?$Rent:0.00;
                        $updatepayments->Garbage =$isGarbage?$Garbage:0.00;
                        $updatepayments->KPLC =$isKPLCDeposit?$KPLC:0.00;
                        $updatepayments->HseDeposit =$isHouseDeposit?$HseDeposit:0.00;
                        $updatepayments->Water =$isWaterDeposit?$Water:0.00;
                        $updatepayments->Lease =$isLease?$Lease:0.00;
                        $updatepayments->save();
                        
                        // if ($Nature=="New") {
                        // Property::updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease);
                        // }
                        // else{
                        //     $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
                        // }
                        Property::updateTenantHouse($tid,$hid);
    
                        Property::setUserLogs($tenantname.' Tenant Assigned to House: '.$Housename);
                        \DB::commit();
                        $success=$tenantname.' Tenant Assigned to House: '.$Housename."!\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        
                        \DB::rollback();
                        Property::setUserLogs($tenantname.' Tenant Not Assigned to House: '.$Housename);
                        $error=$tenantname.' Tenant Not Assigned to House: '.$Housename." \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    \DB::rollback();
                    Property::setUserLogs("Selected Houe Not Found , ".$tenantname.' Tenant Not Assigned to House: '.$Housename);
                    
                    $error="Selected House Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }

                ///
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Tenant Was Not Assigned.\n".$ex->getMessage();
                \DB::rollback();
                Property::setUserLogs($tenantname.' Tenant not Assigned . Error: '.$ex->getMessage());
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Tenant is Already Assigned to this House\n. Ensure You Select a different Month\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                Property::setUserLogs($tenantname.' Tenant not Assigned . Error: '.$ex->getMessage());
                $error="Tenant Not Assigned.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }

    public function addTenantHouse(Request $request)
    {
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'Refund' => ['required', 'numeric','min:0'],
            'InitialCharges' => ['required', 'numeric','min:0'],
            'MonthlyCharges' => ['required', 'numeric','min:0'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            
            $hid=Property::decryptText($request->input('hid'));
            
            $tid=Property::decryptText($request->input('tid'));
            
            $DateAssigned=$request->input('DateAssigned');
            $Nature=$request->input('Nature');
            $tenantname=Property::checkCurrentTenantName($tid);
            
            $Refund=$request->input('Refund');
            $InitialCharges=$request->input('InitialCharges');
            $MonthlyCharges=$request->input('MonthlyCharges');

            $isHouseRent=$request->input('HouseRent');
            $isGarbage=$request->input('Garbage');
            $isHouseDeposit=$request->input('HouseDeposit');
            $isWaterDeposit=$request->input('WaterDeposit');
            $isKPLCDeposit=$request->input('KPLCDeposit');
            $isLease=$request->input('Lease');

            $Housename=Property::getHouseName($hid);
            Property::setUserLogs('Adding Tenant '. $tenantname .' another House '. $Housename);
            try { 
                if((Property::TenantStatus($tid)=='New') || (Property::TenantStatus($tid)=='Vacated')){
                    Property::setUserLogs('Tenant '. $tenantname .' Could Not be added House '. $Housename);
                    $error='Tenant '.$tenantname." is Not Assigned House. \nPlease Choose Assign House Option.";
                    
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
                
                ///
                
                if($vacanthouse=House::where('id',$hid)->where('status','Vacant')->get()->first()){
                    $Housename=$vacanthouse->Housename;
                    $Rent=$vacanthouse->Rent;
                    $Garbage=$vacanthouse->Garbage;
                    $KPLC=$vacanthouse->Kplc;
                    $HseDeposit=$vacanthouse->Deposit;
                    $Water=$vacanthouse->Water;
                    $Lease=$vacanthouse->Lease;
    
                    $deposits=$Refund;
                    $month=date_format(date_create($DateAssigned),'Y n');
                    $pid=Property::decryptText(Property::getHousePropertyID($hid));
                    $tenantphone=Property::getTenantPhone($tid);
                    $uid=$pid.' '.$hid.' '.$tid.' '.$month;

                    
    
                    //Save/Assign New Tenant to house
                    $agreement = new Agreement;
                    $agreement->plot=$pid;
                    $agreement->house=$hid;
                    $agreement->tenant=$tid;
                    $agreement->MonthAssigned=$month;
                    $agreement->DateAssigned=$DateAssigned;
                    $agreement->DateTo=$DateAssigned;
                    $agreement->Deposit=$deposits;
                    $agreement->Phone=$tenantphone;
                    $agreement->UniqueID=$uid;
    
                    if($agreement->save()){
                        // save bills information
                        $payments = new Payment;
                        $payments->Plot=$pid;
                        $payments->House=$hid;
                        $payments->Tenant=$tid;
                        $payments->Month=$month;
                        $payments->save();

                        $paymentid=$payments->id;
                        // Property::updatePaymentsNew($paymentid,$isHouseRent?$Rent:0.00,$isGarbage?$Garbage:0.00,$isKPLCDeposit?$KPLC:0.00,$isHouseDeposit?$HseDeposit:0.00,$isWaterDeposit?$Water:0.00,$isLease?$Lease:0.00);

                        $updatepayments = Payment::findOrFail($paymentid);
                        $updatepayments->Rent =$isHouseRent?$Rent:0.00;
                        $updatepayments->Garbage =$isGarbage?$Garbage:0.00;
                        $updatepayments->KPLC =$isKPLCDeposit?$KPLC:0.00;
                        $updatepayments->HseDeposit =$isHouseDeposit?$HseDeposit:0.00;
                        $updatepayments->Water =$isWaterDeposit?$Water:0.00;
                        $updatepayments->Lease =$isLease?$Lease:0.00;
                        $updatepayments->save();
                        
                        // if ($Nature=="New") {
                        // Property::updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease);
                        // }
                        // else{
                        //     $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
                        // }
                        Property::updateTenantHouseAdd($hid);
    
                        Property::setUserLogs($tenantname.' Tenant Added another House: '.$Housename);
                        \DB::commit();
                        $success=$tenantname.' Tenant Added another House: '.$Housename."!\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        Property::setUserLogs($tenantname.' Tenant Not Added another House: '.$Housename);
                        \DB::rollback();
                        $error=$tenantname.' Tenant Not added another House: '.$Housename." \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    Property::setUserLogs("Selected Houe Not Found , ".$tenantname.' Tenant Not added another House: '.$Housename);
                    \DB::rollback();
                    $error="Selected House Not Found.\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }

                ///
                
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Tenant Was Not Added Another House.\n".$ex->getMessage();
                Property::setUserLogs($tenantname.' Tenant not Added another House . Error: '.$ex->getMessage());
                \DB::rollback();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Tenant is Already Added to this House\n. Ensure You Select a different Month\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                Property::setUserLogs($tenantname.' Tenant not Added Another House . Error: '.$ex->getMessage());
                \DB::rollback();
                $error="Tenant Not Added Another house.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }

    public function reassignHouse(Request $request)
    {
        \DB::beginTransaction();
        $validator=Validator::make($request->all(),[
            'Refund' => ['required', 'numeric','min:0'],
            'InitialCharges' => ['required', 'numeric','min:0'],
            'MonthlyCharges' => ['required', 'numeric','min:0'],
            'Charges' => ['required', 'numeric','min:0'],
            'CarriedForward' => ['required', 'numeric'],
        ]);


        if($validator->fails()){
            return response()->json([
                'status'=>500,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $hid=Property::decryptText($request->input('hid'));//new
            $tid=Property::decryptText($request->input('tid'));
            $fromhid=Property::decryptText($request->input('fromhid'));//old
            $Refund=$request->input('Refund');
            $InitialCharges=$request->input('InitialCharges');
            $MonthlyCharges=$request->input('MonthlyCharges');
            $DateAssigned=$request->input('DateAssigned');
            $Charges=$request->input('Charges');
            $CarriedForward=$request->input('CarriedForward');

            $Excess=$CarriedForward>0?0.00:abs($CarriedForward);
            $Arrears=$CarriedForward>0?$CarriedForward:0.00;

            $isHouseRent=$request->input('HouseRent');
            $isGarbage=$request->input('Garbage');
            $isHouseDeposit=$request->input('HouseDeposit');
            $isWaterDeposit=$request->input('WaterDeposit');
            $isKPLCDeposit=$request->input('KPLCDeposit');
            $isLease=$request->input('Lease');


            $tenantname=Property::checkCurrentTenantName($tid);


            if((Property::TenantStatus($tid)=='New') || (Property::TenantStatus($tid)=='Vacated')){
                $error='Tenant '.$tenantname." is Not Assigned House. \nPlease Choose Assign House Option.";
                
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            else{
                
            }
            try { 
                if($vacanthouse=House::where('id',$hid)->where('status','Vacant')->get()->first()){
                    $Housename=$vacanthouse->Housename;
                    
                    $Rent=$vacanthouse->Rent?$vacanthouse->Rent:0.00;
                    $Garbage=$vacanthouse->Garbage?$vacanthouse->Garbage:0.00;
                    $KPLC=$vacanthouse->KPLC?$vacanthouse->KPLC:0.00;
                    $HseDeposit=$vacanthouse->HseDeposit?$vacanthouse->HseDeposit:0.00;
                    $Water=$vacanthouse->Water?$vacanthouse->Water:0.00;

                    $Lease=$vacanthouse->Lease?$vacanthouse->Lease:0.00;
                    
                    $deposits=$HseDeposit+$Water+$KPLC;
                    $month=date_format(date_create($DateAssigned),'Y n');

                    $pid=Property::decryptText(Property::getHousePropertyID($hid));//new
                    $pidfrom=Property::decryptText(Property::getHousePropertyID($fromhid));//old
                    $tenantphone=Property::getTenantPhone($tid);
                    $uid=$pidfrom.' '.$fromhid.' '.$tid;//old
                    $newuid=$pid.' '.$hid.' '.$tid;//new

                    

                    $aid=Property::getAgreementIds($pidfrom,$fromhid,$tid);
                    
                    // $success='<span>'.$tenantname.' Tenant Re-Assigned to House: '.$fromhid.' Tenant: '.$tid.' Plot: '.$pidfrom.'!</br>';
                    // $error='<span>'.$tenantname.' Tenant Re-Assigned to House: '.$aid.'!</br>';
                    // return compact('error');

                    // $balance=Property::TenantBalance($tid,$fromhid);
                    // if ($balance>0) {
                    //     $Arrears=$Arrears+$balance;
                    // }
                    // else{
                    //     $Excess=$Excess+abs($balance);
                    // }

                    //reassign tenant house
                    $agreement = new Agreement;
                    $agreement->plot=$pid;
                    $agreement->house=$hid;
                    $agreement->tenant=$tid;
                    $agreement->MonthAssigned=$month;
                    $agreement->DateAssigned=$DateAssigned;
                    $agreement->DateTo=$DateAssigned;
                    $agreement->Deposit=$deposits;
                    $agreement->Phone=$tenantphone;
                    $agreement->UniqueID=$newuid;
                    

                    if($agreement->save()){
                        //update old tenant house information
                        
                        $uidold=$pidfrom.' '.$fromhid.' '.$tid.' '.$month;//old
                        $updateagreement = Agreement::findOrFail(Property::decryptText($aid));
                        // return response()->json([
                        //     'status'=>500,
                        //     'message'=>$newuid,
                        //     'aid'=>Property::decryptText($aid),
                        // ]);
                        $updateagreement->DateVacated=$DateAssigned;
                        $updateagreement->Month=$month;
                        $updateagreement->DateTo=$DateAssigned;
                        $updateagreement->DateVacated=$DateAssigned;
                        $updateagreement->UniqueID=$uidold;

                        $updateagreement->save();
                        // save assigned tenant house bills information
                        $payments = new Payment;
                        $payments->Plot=$pid;
                        $payments->House=$hid;
                        $payments->Tenant=$tid;
                        $payments->Rent =$isHouseRent?$Rent:0.00;
                        $payments->Garbage =$isGarbage?$Garbage:0.00;
                        $payments->Arrears =$Arrears;
                        $payments->Excess =$Excess;
                        $payments->KPLC =$isKPLCDeposit?$KPLC:0.00;
                        $payments->HseDeposit =$isHouseDeposit?$HseDeposit:0.00;
                        $payments->Water =$isWaterDeposit?$Water:0.00;
                        $payments->Lease =$isLease?$Lease:0.00;
                        $payments->Month=$month;
                        $payments->save();
                        $paymentid=$payments->id;

                       Property::updateTenantReassign($tid,$hid,$fromhid);
                        

                        Property::setUserLogs($tenantname.' Tenant Re-Assigned to House: '.$Housename);
                        \DB::commit();
                        $success="".$tenantname." Tenant Re-Assigned to House: ".$Housename."!\n";
                        
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        Property::setUserLogs($tenantname.' Tenant Not Re-Assigned to House: '.$Housename);
                        $error="".$tenantname." Tenant Not Re-Assigned to House: ".$Housename." \n";
                        return response()->json([
                            'status'=>500,
                            'message'=>$error,
                        ]);
                    }
                }
                else{
                    \DB::rollback();
                    $error="Selected House Not Found\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }

            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Tenant Was Not Re-Assigned\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Tenant is Already Re-Assigned to this House\n. Ensure You Select a different Month\n";
                }
                \DB::rollback();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                \DB::rollback();
                Property::setUserLogs($tenantname.' Tenant not Re-Assigned . Error: '.$ex->getMessage());
                $error="Tenant Not Re-Assigned\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
        }
    }
    

    public function vacateHouse(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Damages' => ['required', 'numeric','min:0'],
            'Transaction' => ['required', 'numeric','min:0'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            
            $hid=Property::decryptText($request->input('hid'));
            $tid=Property::decryptText($request->input('tid'));
            $aid=Property::decryptText($request->input('aid'));
            
            $tenantname=Property::checkCurrentTenantName($tid);
            $housename=Property::getHouseName($hid);
            Property::setUserLogs('Vacating Tenant '. $tenantname .' from House '. $housename);
            try { 
                $Deposit=$request->input('Deposit');
                $Refund=$request->input('Refund');
                $Arrears=$request->input('Arrears');
                $Damages=$request->input('Damages');
                $DateVacated=$request->input('DateVacated');
                $Transaction=$request->input('Transaction');

                $month=date_format(date_create($DateVacated),'Y n');
                $tenantassignedhse=Property::tenantHousesAssigned($tid);

                $agreement = Agreement::findOrFail($aid);
                $agreement->DateVacated=$DateVacated;
                $agreement->Deposit=$Deposit;
                $agreement->Damages=$Damages;
                $agreement->Refund=$Refund;
                $agreement->Month=$month;
                $agreement->Arrears=$Arrears;
                $agreement->DateTo=$DateVacated;
                $agreement->Transaction=$Transaction;

                if($agreement->save()){
                    $tenantassignedhse=Property::tenantHousesAssigned($tid);
                    Property::updateTenantVacate($tid,$hid,$tenantassignedhse);
                    Property::setUserLogs($tenantname.' Vacated from '. $housename.' Successfully.');
                    $success="Tenant Vacated from House!.\n";
                    $currenthouseid=Property::checkCurrentTenantHouse($tid);
                    return response()->json([
                        'status'=>200,
                        'message'=>$success,
                        'houses'=>$tenantassignedhse,
                        'currenthouseid'=>$currenthouseid,
                    ]);
                }
                else{
                    Property::setUserLogs($tenantname.' Not Vacated from House '.$housename);
                    $error="Tenant not Vacated : ". $tenantname.".\n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
            } 
            catch(\Illuminate\Database\QueryException $ex){ 
                Property::setUserLogs($tenantname.' Not Vacated from House.');
                $errors=$ex->getMessage();
                $beingusederror='1062 ';
                $error="Tenant Could not be Vacated.\n".$ex->getMessage();
                if (preg_match("/$beingusederror/i", $errors)) {
                    $error="Tenant is Already Saved.\n";
                }
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }
            catch(\Exception $ex){ 
                Property::setUserLogs($tenantname.' Not Vacated from House.');
                $error="Tenant Not Vacated.\n".$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

        }
        
    }


    
    public static function getSearchResult($search){

            // $ip=\Request::ip();
            // $useragents=\Request::userAgent();
        
            // return response()->json([
            //     'status'=>500,
            //     'ip'=>$ip,
            //     'message'=>$useragents,
            // ]);
        $thistenant='';
        $thisproperty='';
        $thishouse='';
        $sno=0;

        $month=date('Y n');
        $curmonth=date('M, Y');

        $monthdate= Property::getLastMonthdate($month);
        $previousmonth= Property::getLastMonth($month,$monthdate);

        $previousmonthname=Property::getMonthDateMonthPrevious($previousmonth);
        $thisplotname='';
        $thisplotid='';

        if($search==''){
            $thistenant='';
            $thisproperty='';
            $thishouse='';
        }
        else{
            if(strlen($search) >= 3){
                $houses=House::query()
                        ->where('Housename','LIKE',"%{$search}%")
                        ->orWhere('Status','LIKE',"%{$search}%")->get();
                $thishouse= array();
                $sno2=0;
                foreach ($houses as $house) { 
                    $Plotname=Property::getPropertyName(Property::decryptText($house->plot));
                    $thisplotname=$Plotname;
                    $thisplotid=$house->plot;
                    $thishouse[] = array(
                        'sno'=>         $sno2,
                        'id' =>         $house->id,
                        'pid' =>        $house->plot,
                        'Housename'=>   $house->Housename,
                        'Plotname'=>    $Plotname,
                        'Rent' =>       $house->Rent,
                        'Deposit' =>    $house->Deposit,
                        'Water' =>      $house->Water,
                        'Garbage' =>    $house->Garbage,
                        'Lease' =>      $house->Lease,
                        'DueDay' =>     $house->DueDay,
                        'Status' =>     $house->Status,
                        'created_at' => $house->created_at
                    );
                    $sno2++;
                }
            }
            else{
                $thishouse='';
                $thisplotname='';
                $thisplotid='';
            }


            $properties=Property::query()
                    ->where('Plotname','LIKE',"%{$search}%")
                    ->orWhere('Plotcode','LIKE',"%{$search}%")->get();
            $thisproperty= array();
            $sno1=0;
            foreach ($properties as $property) { 
                $totalhouses=Property::getTotalHousesHse(Property::decryptText($property->id));
                $thisproperty[] = array(
                    'sno'=>         $sno1,
                    'id' =>         $property->id,
                    'Plotname'=>    $property->Plotname,
                    'Plotcode' =>   $property->Plotcode,
                    'totalhouses'=> $totalhouses,
                    'created_at' => $property->created_at
                );
                $sno1++;
            }

            if(strlen($search) >= 3){
                $tenants=Tenant::query()
                        ->where('Fname','LIKE',"%{$search}%")
                        ->orWhere('Oname','LIKE',"%{$search}%")->get();
                $thistenant= array();
                $sno=0;
                foreach ($tenants as $tenant) { 
                    $tenantname=Property::TenantNames(Property::decryptText($tenant->id));
                    $thistenant[] = array(
                        'sno'=>         $sno,
                        'id' =>         $tenant->id,
                        'tenantname'=>  ucwords(strtolower($tenantname)),
                        'Fname' =>      $tenant->Fname,
                        'Oname' =>      ucwords(strtolower($tenant->Oname)),
                        'Gender' =>     $tenant->Gender,
                        'Status' =>     $tenant->Status,
                        'created_at' => $tenant->created_at
                    );
                    $sno++;
                }
            }
            else{
                $thistenant='';
            }
        }

       
        return response()->json([
            'status'=>200,
            'properties'=>'',
            'propertyinfo'=>$search,
            'thistenant'=>$thistenant,
            'thishouse'=>$thishouse,
            'thisproperty'=>$thisproperty,
            'month'=>       $month,
            'curmonthname'=>    $curmonth,
            'previousmonthname'=>   $previousmonthname,
            'previousmonth'=>   $previousmonth,
            'thisplotname'=>    $thisplotname,
            'thisplotid'=>      $thisplotid,
            'message'=>'Search Results Loaded Succesfully',
        ]);

    }

    public function sendNewMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'Phone' => ['required', 'string','min:13'],
            'Message' => ['required', 'string','max:479'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            $phone=$request->input('Phone');
            $message=$request->input('Message');

            

            $allmessages=explode(",", $phone);
            // $eachmessage=explode(",", $allmessages);
            // $msgerror="";

            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($allmessages as $eachmsg) {
                if(strlen($eachmsg)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$eachmsg." Or More Phone Numbers is not in provided format/length\nPlease use something like '+254712345678'",
                    ]);
                }
            }

            try {
                // Initialize the SDK
                $AT          = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms        = $AT->sms();
                
                $result = $sms->send([
                    'to'      => $phone,
                    'message' => $message,
                    'from'    => $apiFrom 
                ]);

                $enjson=json_encode($result);
                $characters = json_decode($enjson,true);

                // Property::setUserLogs($enjson);
                // Property::setUserLogs($characters);
                
                if($characters['status']=='error'){
                    return response()->json([
                        'status'=>500,
                        'message'=>$characters['data'],
                    ]);
                }
                $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                if(sizeof($recipients)>0){
                    $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                    $sentmgs= substr($messagesent, 8);  
                    $totalmgs= substr($messagesent, 10);  
                    $sentsuccess='';
                    $senterror='';
                    if ($sentmgs==0) {
                        return response()->json([
                            'status'=>500,
                            'message'=>"Message Was not Sent!!",
                        ]);
                    }
                    else{
                        foreach ($recipients as $number) {
                            $userid=Auth::user()->id;
                            $watermessage = new Message;
                            $watermessage->user=$userid;
                            $watermessage->To=$number["number"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->Code=$number["statusCode"];
                            $watermessage->Status=$characters["status"];
                            $watermessage->PatchNo=$Patchno;
                            if ($watermessage->save()){
                                $sentsuccess =$sentsuccess.$number['number'].',';
                            }
                            else{
                                $senterror =$senterror.$number['number'].',';
                            }
                        }
                        $success='';
                        if($sentsuccess!=''){
                            $success=$success."Message sent to ".$sentsuccess." /n";
                        }
                        if($senterror!=''){
                            $success=$success."Message not sent to ".$senterror." /n";
                        }

                        Property::setUserLogs($success);

                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Message Was not Sent!!",
                    ]);
                }
            } 
            catch(\GuzzleHttp\Exception\ConnectException $e) {
                $error=$e->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                
                $networkerror='cURL error 6:';
                if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                    // return "Net Error";
                    return response()->json([
                        'status'=>500,
                        'message'=>"No Internet",
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Internal Error: ".$error,
                    ]);
                }
            }
            catch(\Exception $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Exception\ErrorException $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Illuminate\Database\QueryException $ex){ 
                $error=$ex->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                return response()->json([
                    'status'=>500,
                    'message'=>"DB Error: ".$error,
                ]);
            }
        }

    }

    public function sendSingleWaterMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'Phone' => ['required', 'numeric','min:9'],
            'Message' => ['required', 'string','min:0'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            $id=$request->input('hid');
            $month=$request->input('month');
            $phone='+254'.$request->input('Phone');
            $message=$request->input('Message');

            

            $allmessages=explode(",", $phone);
            // $eachmessage=explode(",", $allmessages);
            // $msgerror="";

            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($allmessages as $eachmsg) {
                if(strlen($eachmsg)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$eachmsg." Or More Phone Numbers is not in provided format\nPlease use something like '+254712345678'",
                    ]);
                }
            }

            try {
                // Initialize the SDK
                $AT          = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms        = $AT->sms();
                
                $result = $sms->send([
                    'to'      => $phone,
                    'message' => $message,
                    'from'    => $apiFrom
                ]);

                $enjson=json_encode($result);
                $characters = json_decode($enjson,true);

                // Property::setUserLogs($enjson);
                // Property::setUserLogs($characters);
                
                if($characters['status']=='error'){
                    return response()->json([
                        'status'=>500,
                        'message'=>$characters['data'],
                    ]);
                }
                $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                if(sizeof($recipients)>0){
                    $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                    $sentmgs= substr($messagesent, 8);  
                    $totalmgs= substr($messagesent, 10);  
                    $sentsuccess='';
                    $senterror='';
                    if ($sentmgs==0) {
                        return response()->json([
                            'status'=>500,
                            'message'=>"Message Was not Sent!!",
                        ]);
                    }
                    else{
                        foreach ($recipients as $number) {
                            $userid=Auth::user()->id;
                            // $watermessage = new Message;

                            $watermessage = new WaterMessage;
                            $watermessage->House=Property::decryptText($id);
                            $watermessage->Message=$message;
                            $watermessage->Month=$month;
                            $watermessage->user=$userid;
                            $watermessage->To=$number["number"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->Code=$number["statusCode"];
                            $watermessage->Status=$characters["status"];
                            $watermessage->PatchNo=$Patchno;
                            if ($watermessage->save()){
                                $sentsuccess =$sentsuccess.$number['number'].',';
                            }
                            else{
                                $senterror =$senterror.$number['number'].',';
                            }
                        }
                        $success='';
                        if($sentsuccess!=''){
                            $success=$success."Message sent to ".$sentsuccess." /n";
                        }
                        if($senterror!=''){
                            $success=$success."Message not sent to ".$senterror." /n";
                        }

                        Property::setUserLogs($success);

                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Message Was not Sent!!",
                    ]);
                }
            } 
            catch(\GuzzleHttp\Exception\ConnectException $e) {
                $error=$e->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                
                $networkerror='cURL error 6:';
                if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                    // return "Net Error";
                    return response()->json([
                        'status'=>500,
                        'message'=>"No Internet",
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Internal Error: ".$error,
                    ]);
                }
            }
            catch(\Exception $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Exception\ErrorException $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Illuminate\Database\QueryException $ex){ 
                $error=$ex->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                return response()->json([
                    'status'=>500,
                    'message'=>"DB Error: ".$error,
                ]);
            }
        }

    }


    public function sendAllWaterMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'waterchoosen' => ['required', 'array'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            // $id=$request->input('id');
            $month=$request->input('month');
            // $phone='+254'.$request->input('Phone');
            $waterchoosen=$request->input('waterchoosen');
            

            $allmessages=implode(",", $waterchoosen);
            $eachmessage=explode(",", $allmessages);
            $msgerror="";
            $sentsuccess='';
            $successcount='';
            $senterror=0;
            $errorcount=0;
            $allcount=0;
            
            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($eachmessage as $eachmsg) {
              
                $mms=explode("/", $eachmsg);
                $phone='+254'.$mms[0];
                $id=$mms[1];
                $message=$mms[2];

                if(strlen($phone)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$phone." Or More Phone Numbers is not in provided format\nPlease use something like '+254712345678'",
                    ]);
                }
                

                try {
                    $allcount++;
                    // Initialize the SDK
                    $AT          = new AfricasTalking($username, $apiKey);
                    // Get the application service
                    $sms        = $AT->sms();
                    
                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $apiFrom 
                    ]);

                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);

                    // Property::setUserLogs($enjson);
                    // Property::setUserLogs($characters);
                    
                    if($characters['status']=='error'){
                        return response()->json([
                            'status'=>500,
                            'message'=>$characters['data'],
                        ]);
                    }
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                    if(sizeof($recipients)>0){
                        $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                        $sentmgs= substr($messagesent, 8);  
                        $totalmgs= substr($messagesent, 10);  
                        
                        if ($sentmgs==0) {
                            // return response()->json([
                            //     'status'=>500,
                            //     'message'=>"Message Was not Sent!!",
                            // ]);
                            $errorcount++;
                        }
                        else{
                            foreach ($recipients as $number) {
                                $userid=Auth::user()->id;
                                // $watermessage = new Message;

                                $watermessage = new WaterMessage;
                                $watermessage->House=Property::decryptText($id);
                                $watermessage->Message=$message;
                                $watermessage->Month=$month;
                                $watermessage->user=$userid;
                                $watermessage->To=$number["number"];
                                $watermessage->Cost=$number["cost"];
                                $watermessage->MessageId=$number["messageId"];
                                $watermessage->Message=$message;
                                $watermessage->Code=$number["statusCode"];
                                $watermessage->Status=$characters["status"];
                                $watermessage->PatchNo=$Patchno;
                                if ($watermessage->save()){
                                    $sentsuccess =$sentsuccess.$number['number'].',';
                                    $successcount++;
                                }
                                else{
                                    $senterror =$senterror.$number['number'].',';
                                    $errorcount++;
                                }
                            }
                        }
                    
                    }
                    else{
                        $errorcount++;
                        // return response()->json([
                        //     'status'=>500,
                        //     'message'=>"Message Was not Sent!!",
                        // ]);
                    }
                } 
                catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                    
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        // return "Net Error";
                        return response()->json([
                            'status'=>500,
                            'message'=>"No Internet",
                        ]);
                    }
                    else{
                        return response()->json([
                            'status'=>500,
                            'message'=>"Internal Error: ".$error,
                        ]);
                    }
                }
                catch(\Exception $ex){ 
                    $error=$ex->getMessage();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Other Error: ".$error,
                    ]);
                }
                catch(\Exception\ErrorException $ex){ 
                    $error=$ex->getMessage();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Other Error: ".$error,
                    ]);
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $error=$ex->getMessage();
                    // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                    return response()->json([
                        'status'=>500,
                        'message'=>"DB Error: ".$error,
                    ]);
                }
            }

            $success='';
            $msgs='';
            if($sentsuccess!=''){
                $success=$success."Message sent to ".$sentsuccess." /n";
                $msgs=$msgs."About ".$successcount." Messages were Sent Successfully.\n";
            }
            if($senterror!=''){
                $success=$success."Message not sent to ".$senterror." /n";
                $msgs=$msgs."About ".$errorcount." Messages were Not Sent.\n";
            }

            Property::setUserLogs($success);

            return response()->json([
                'status'=>200,
                'message'=>$msgs,
            ]);
        }

    }

    
    public function sendTenantMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'Phone' => ['required', 'array'],
            'Message' => ['required', 'string','max:479'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            $phone=$request->input('Phone');
            $message=$request->input('Message');

            
            $allmessages=implode(",", $phone);
            $eachmessage=explode(",", $allmessages);

            // $allmessages=explode(",", $phone);
            // $eachmessage=explode(",", $allmessages);
            // $msgerror="";

            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($eachmessage as $eachmsg) {
                if(strlen($eachmsg)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$eachmsg." Or More Phone Numbers is not in provided format\nPlease use something like '+254712345678'",
                    ]);
                }
            }

            try {
                // Initialize the SDK
                $AT          = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms        = $AT->sms();
                
                $result = $sms->send([
                    'to'      => $phone,
                    'message' => $message,
                    'from'    => $apiFrom 
                ]);

                $enjson=json_encode($result);
                $characters = json_decode($enjson,true);

                // Property::setUserLogs($enjson);
                // Property::setUserLogs($characters);
                
                if($characters['status']=='error'){
                    return response()->json([
                        'status'=>500,
                        'message'=>$characters['data'],
                    ]);
                }
                $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                if(sizeof($recipients)>0){
                    $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                    $sentmgs= substr($messagesent, 8);  
                    $totalmgs= substr($messagesent, 10);  
                    $sentsuccess='';
                    $senterror='';
                    if ($sentmgs==0) {
                        return response()->json([
                            'status'=>500,
                            'message'=>"Message Was not Sent!!",
                        ]);
                    }
                    else{
                        foreach ($recipients as $number) {
                            $userid=Auth::user()->id;
                            $watermessage = new Message;
                            $watermessage->user=$userid;
                            $watermessage->To=$number["number"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->Code=$number["statusCode"];
                            $watermessage->Status=$characters["status"];
                            $watermessage->PatchNo=$Patchno;
                            if ($watermessage->save()){
                                $sentsuccess =$sentsuccess.$number['number'].',';
                            }
                            else{
                                $senterror =$senterror.$number['number'].',';
                            }
                        }
                        $success='';
                        if($sentsuccess!=''){
                            $success=$success."Message sent to ".$sentsuccess." /n";
                        }
                        if($senterror!=''){
                            $success=$success."Message not sent to ".$senterror." /n";
                        }

                        Property::setUserLogs($success);

                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Message Was not Sent!!",
                    ]);
                }
            } 
            catch(\GuzzleHttp\Exception\ConnectException $e) {
                $error=$e->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                
                $networkerror='cURL error 6:';
                if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                    // return "Net Error";
                    return response()->json([
                        'status'=>500,
                        'message'=>"No Internet",
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Internal Error: ".$error,
                    ]);
                }
            }
            catch(\Exception $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Exception\ErrorException $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Illuminate\Database\QueryException $ex){ 
                $error=$ex->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                return response()->json([
                    'status'=>500,
                    'message'=>"DB Error: ".$error,
                ]);
            }
        }

    }

    public function sendAllTenantMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'waterchoosen' => ['required', 'array'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            // $id=$request->input('id');
            $month=$request->input('month');
            // $phone='+254'.$request->input('Phone');
            $waterchoosen=$request->input('waterchoosen');
            

            $allmessages=implode(",", $waterchoosen);
            $eachmessage=explode(",", $allmessages);
            $msgerror="";
            $sentsuccess='';
            $successcount='';
            $senterror=0;
            $errorcount=0;
            $allcount=0;
            
            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($eachmessage as $eachmsg) {
                $mms=explode("/", $eachmsg);
                $phone=$mms[0];
                $id=$mms[1];
                $message=$mms[2];

                if(strlen($phone)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$phone." Or More Phone Numbers is not in provided format\nPlease use something like '+254712345678'",
                    ]);
                }
                

                try {
                    $allcount++;
                    // Initialize the SDK
                    $AT          = new AfricasTalking($username, $apiKey);
                    // Get the application service
                    $sms        = $AT->sms();
                    
                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $apiFrom 
                    ]);

                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);

                    // Property::setUserLogs($enjson);
                    // Property::setUserLogs($characters);
                    
                    if($characters['status']=='error'){
                        return response()->json([
                            'status'=>500,
                            'message'=>$characters['data'],
                        ]);
                    }
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                    if(sizeof($recipients)>0){
                        $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                        $sentmgs= substr($messagesent, 8);  
                        $totalmgs= substr($messagesent, 10);  
                        
                        if ($sentmgs==0) {
                            // return response()->json([
                            //     'status'=>500,
                            //     'message'=>"Message Was not Sent!!",
                            // ]);
                            $errorcount++;
                        }
                        else{
                            foreach ($recipients as $number) {
                                $userid=Auth::user()->id;
                                // $watermessage = new Message;

                                $watermessage = new Message;
                                $watermessage->user=$userid;
                                $watermessage->To=$number["number"];
                                $watermessage->Cost=$number["cost"];
                                $watermessage->MessageId=$number["messageId"];
                                $watermessage->Message=$message;
                                $watermessage->Code=$number["statusCode"];
                                $watermessage->Status=$characters["status"];
                                $watermessage->PatchNo=$Patchno;
                                if ($watermessage->save()){
                                    $sentsuccess =$sentsuccess.$number['number'].',';
                                    $successcount++;
                                }
                                else{
                                    $senterror =$senterror.$number['number'].',';
                                    $errorcount++;
                                }
                            }
                        }
                    
                    }
                    else{
                        $errorcount++;
                        // return response()->json([
                        //     'status'=>500,
                        //     'message'=>"Message Was not Sent!!",
                        // ]);
                    }
                } 
                catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                    
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        // return "Net Error";
                        return response()->json([
                            'status'=>500,
                            'message'=>"No Internet",
                        ]);
                    }
                    else{
                        return response()->json([
                            'status'=>500,
                            'message'=>"Internal Error: ".$error,
                        ]);
                    }
                }
                catch(\Exception $ex){ 
                    $error=$ex->getMessage();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Other Error: ".$error,
                    ]);
                }
                catch(\Exception\ErrorException $ex){ 
                    $error=$ex->getMessage();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Other Error: ".$error,
                    ]);
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $error=$ex->getMessage();
                    // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                    return response()->json([
                        'status'=>500,
                        'message'=>"DB Error: ".$error,
                    ]);
                }
            }

            $success='';
            $msgs='';
            if($sentsuccess!=''){
                $success=$success."Message sent to ".$sentsuccess." /n";
                $msgs=$msgs."About ".$successcount." Messages were Sent Successfully.\n";
            }
            if($senterror!=''){
                $success=$success."Message not sent to ".$senterror." /n";
                $msgs=$msgs."About ".$errorcount." Messages were Not Sent.\n";
            }

            Property::setUserLogs($success);

            return response()->json([
                'status'=>200,
                'message'=>$msgs,
            ]);
        }

    }

    public function sendReminderMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'Phone' => ['required', 'numeric','min:9'],
            'Message' => ['required', 'string','min:0'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            
            $pid=$request->input('pid');
            $id=$request->input('hid');
            $month=$request->input('month');
            $phone=$request->input('Phone');
            $message=$request->input('Message');

            

            $allmessages=explode(",", $phone);
            // $eachmessage=explode(",", $allmessages);
            // $msgerror="";

            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($allmessages as $eachmsg) {
                if(strlen($eachmsg)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$eachmsg." Or More Phone Numbers is not in provided format\nPlease use something like '+254712345678'",
                    ]);
                }
            }

            try {
                // Initialize the SDK
                $AT          = new AfricasTalking($username, $apiKey);
                // Get the application service
                $sms        = $AT->sms();
                
                $result = $sms->send([
                    'to'      => $phone,
                    'message' => $message,
                    'from'    => $apiFrom
                ]);

                $enjson=json_encode($result);
                $characters = json_decode($enjson,true);

                // Property::setUserLogs($enjson);
                // Property::setUserLogs($characters);
                
                if($characters['status']=='error'){
                    return response()->json([
                        'status'=>500,
                        'message'=>$characters['data'],
                    ]);
                }
                $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                if(sizeof($recipients)>0){
                    $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                    $sentmgs= substr($messagesent, 8);  
                    $totalmgs= substr($messagesent, 10);  
                    $sentsuccess='';
                    $senterror='';
                    if ($sentmgs==0) {
                        return response()->json([
                            'status'=>500,
                            'message'=>"Message Was not Sent!!",
                        ]);
                    }
                    else{
                        foreach ($recipients as $number) {
                            $userid=Auth::user()->id;
                            // $watermessage = new Message;
                            $watermessage = new PaymentMessage;
                            $watermessage->Plot=Property::decryptText($pid);
                            $watermessage->House=$id;
                            $watermessage->Tenant=Property::decryptText(Property::checkCurrentTenant($id));
                            $watermessage->Message=$message;
                            $watermessage->Month=$month;
                            $watermessage->user=$userid;
                            $watermessage->To=$number["number"];
                            $watermessage->Cost=$number["cost"];
                            $watermessage->MessageId=$number["messageId"];
                            $watermessage->Message=$message;
                            $watermessage->Code=$number["statusCode"];
                            $watermessage->Status=$characters["status"];
                            $watermessage->PatchNo=$Patchno;
                            if ($watermessage->save()){
                                $sentsuccess =$sentsuccess.$number['number'].',';
                            }
                            else{
                                $senterror =$senterror.$number['number'].',';
                            }
                        }
                        $success='';
                        if($sentsuccess!=''){
                            $success=$success."Message sent to ".$sentsuccess." /n";
                        }
                        if($senterror!=''){
                            $success=$success."Message not sent to ".$senterror." /n";
                        }

                        Property::setUserLogs($success);

                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Message Was not Sent!!",
                    ]);
                }
            } 
            catch(\GuzzleHttp\Exception\ConnectException $e) {
                $error=$e->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                
                $networkerror='cURL error 6:';
                if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                    // return "Net Error";
                    return response()->json([
                        'status'=>500,
                        'message'=>"No Internet",
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>"Internal Error: ".$error,
                    ]);
                }
            }
            catch(\Exception $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Exception\ErrorException $ex){ 
                $error=$ex->getMessage();
                return response()->json([
                    'status'=>500,
                    'message'=>"Other Error: ".$error,
                ]);
            }
            catch(\Illuminate\Database\QueryException $ex){ 
                $error=$ex->getMessage();
                // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                return response()->json([
                    'status'=>500,
                    'message'=>"DB Error: ".$error,
                ]);
            }
        }

    }


    public function sendAllReminderMessage(Request $request)
    {
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // // Set the numbers you want to send to in international format
        // $phone ="";
        // $message ="";
        $validator=Validator::make($request->all(),[
            'waterchoosen' => ['required', 'array'],
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            // $id=$request->input('id');
            $month=$request->input('month');
            $pid=$request->input('pid');
            // $phone='+254'.$request->input('Phone');
            $waterchoosen=$request->input('waterchoosen');
            

            $allmessages=implode(",", $waterchoosen);
            $eachmessage=explode(",", $allmessages);
            $msgerror="";
            $sentsuccess='';
            $successcount='';
            $senterror=0;
            $errorcount=0;
            $allcount=0;
            
            $Patchno=date('Y'.'m'.'d'.'H'.'i'.'s');
            foreach ($eachmessage as $eachmsg) {
                $mms=explode("/", $eachmsg);
                $phone=$mms[0];
                $id=$mms[1];
                $message=$mms[2];

                if(strlen($phone)!=13){
                    return response()->json([
                        'status'=>500,
                        'message'=>$phone." Or More Phone Numbers is not in provided format\nPlease use something like '+254712345678'",
                    ]);
                }
                

                try {
                    $allcount++;
                    // Initialize the SDK
                    $AT          = new AfricasTalking($username, $apiKey);
                    // Get the application service
                    $sms        = $AT->sms();
                    
                    $result = $sms->send([
                        'to'      => $phone,
                        'message' => $message,
                        'from'    => $apiFrom 
                    ]);

                    $enjson=json_encode($result);
                    $characters = json_decode($enjson,true);

                    // Property::setUserLogs($enjson);
                    // Property::setUserLogs($characters);
                    
                    if($characters['status']=='error'){
                        return response()->json([
                            'status'=>500,
                            'message'=>$characters['data'],
                        ]);
                    }
                    $recipients=$characters["data"]["SMSMessageData"]["Recipients"];
                    if(sizeof($recipients)>0){
                        $messagesent =$characters["data"]["SMSMessageData"]["Message"];
                        $sentmgs= substr($messagesent, 8);  
                        $totalmgs= substr($messagesent, 10);  
                        
                        if ($sentmgs==0) {
                            // return response()->json([
                            //     'status'=>500,
                            //     'message'=>"Message Was not Sent!!",
                            // ]);
                            $errorcount++;
                        }
                        else{
                            foreach ($recipients as $number) {
                                $userid=Auth::user()->id;
                                // $watermessage = new Message;

                                $watermessage = new PaymentMessage;
                                $watermessage->Plot=Property::decryptText($pid);
                                $watermessage->House=$id;
                                $watermessage->Tenant=Property::decryptText(Property::checkCurrentTenant($id));
                                $watermessage->Message=$message;
                                $watermessage->Month=$month;
                                $watermessage->user=$userid;
                                $watermessage->To=$number["number"];
                                $watermessage->Cost=$number["cost"];
                                $watermessage->MessageId=$number["messageId"];
                                $watermessage->Message=$message;
                                $watermessage->Code=$number["statusCode"];
                                $watermessage->Status=$characters["status"];
                                $watermessage->PatchNo=$Patchno;
                                if ($watermessage->save()){
                                    $sentsuccess =$sentsuccess.$number['number'].',';
                                    $successcount++;
                                }
                                else{
                                    $senterror =$senterror.$number['number'].',';
                                    $errorcount++;
                                }
                            }
                        }
                    
                    }
                    else{
                        $errorcount++;
                        // return response()->json([
                        //     'status'=>500,
                        //     'message'=>"Message Was not Sent!!",
                        // ]);
                    }
                } 
                catch(\GuzzleHttp\Exception\ConnectException $e) {
                    $error=$e->getMessage();
                    // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                    
                    $networkerror='cURL error 6:';
                    if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                        // return "Net Error";
                        return response()->json([
                            'status'=>500,
                            'message'=>"No Internet",
                        ]);
                    }
                    else{
                        return response()->json([
                            'status'=>500,
                            'message'=>"Internal Error: ".$error,
                        ]);
                    }
                }
                catch(\Exception $ex){ 
                    $error=$ex->getMessage();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Other Error: ".$error,
                    ]);
                }
                catch(\Exception\ErrorException $ex){ 
                    $error=$ex->getMessage();
                    return response()->json([
                        'status'=>500,
                        'message'=>"Other Error: ".$error,
                    ]);
                }
                catch(\Illuminate\Database\QueryException $ex){ 
                    $error=$ex->getMessage();
                    // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
                    return response()->json([
                        'status'=>500,
                        'message'=>"DB Error: ".$error,
                    ]);
                }
            }

            $success='';
            $msgs='';
            if($sentsuccess!=''){
                $success=$success."Message sent to ".$sentsuccess." /n";
                $msgs=$msgs."About ".$successcount." Messages were Sent Successfully.\n";
            }
            if($senterror!=''){
                $success=$success."Message not sent to ".$senterror." /n";
                $msgs=$msgs."About ".$errorcount." Messages were Not Sent.\n";
            }

            Property::setUserLogs($success);

            return response()->json([
                'status'=>200,
                'message'=>$msgs,
            ]);
        }

    }

    

    public function getPropertyHouseType($group){
        // $messageinfo=Message::query()
        //             ->where('deleted_at','==',NULL)
        //             ->orderByDesc('updated_at')->limit(1000)->get();

        $typeinfo = Propertyhousetype::orderByDesc('updated_at')->where('group',$group)->get();
        
        $propertyhousetypedata= array();
        $sno=0;
        // Message To  Status Cost Code MessageId DateSent
        foreach ($typeinfo as $type) { 
                $propertyhousetypedata[] = array(
                    'sno'           =>$sno,
                    'id'            => $type->id,
                    'group'       => $type->group,
                    'typename'        =>$type->typename,
                    'created_at'    => $type->created_at,
                    'updated_at'    => $type->updated_at
                );
                $sno++;
        }


        return response()->json([
            'status'=>200,
            'propertyhousetypedata'=>$propertyhousetypedata,
            'totals' => $sno,
            'Type'=>'Found '.($sno).' Types',
        ]);
    }

    public function getComposedMessages(){
        // $messageinfo=Message::query()
        //             ->where('deleted_at','==',NULL)
        //             ->orderByDesc('updated_at')->limit(1000)->get();

        $messageinfo = Message::orderByDesc('updated_at')->where('deleted_at',NULL)->limit(1000)->get();
        
        $messagesdata= array();
        $sno=0;
        // Message To  Status Cost Code MessageId DateSent
        foreach ($messageinfo as $messages) { 
                $messagesdata[] = array(
                    'sno'           =>$sno,
                    'id'            => $messages->id,
                    'MessageMasked' => Property::getMessageMask($messages->Message),
                    'MessageFormated' => Property::getMessageFormated($messages->Message),
                    'Message'       => $messages->Message,
                    'Phone'         => $messages->To,
                    'Status'        =>$messages->Status,
                    'Cost'          =>$messages->Cost,
                    'MessageId'     =>$messages->MessageId,
                    'DateSent'      =>$messages->DateSent,
                    'created_at'    => $messages->created_at,
                    'updated_at'    => $messages->updated_at
                );
                $sno++;
        }


        return response()->json([
            'status'=>200,
            'messagesdata'=>$messagesdata,
            'totals' => $sno,
            'message'=>'Found '.($sno).' Messages',
        ]);
    }

    public function setWaterbillPageMessages($id,$month){
       
        $properties = Property::all();
        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }

        try { 
            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else if($id==0){
                $PinitialId=Property::where('Waterbill','Monthly')->get()->first();
                $thisproperty=Property::findOrFail(Property::decryptText($PinitialId->id));
                $houseinfo=House::where('Plot',Property::decryptText($PinitialId->id))->get();
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $sno1=1;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }

            
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= $result['id'];
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                // $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tid)), 0);
                $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),Property::decryptText($tid),$month);
                $housename=$result['Housename'];
                $tenantname='';
                $tenantfname='';
                if ($tid=='') {
                    $tenant='Vacated';
                    $tenantname=($rent==0)?'Caretaker':'House Vacant';
                    $tenantfname=($rent==0)?'Caretaker':'House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
                    $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
                }

                
                

                
                if($waterbills=Water::where('house',Property::decryptText($hid))->where('Month',$month)->get()->first()){
                    
                    // return Property::decryptText($waterbills->tenant);
                    $tenantid=$waterbills->tenant;

                    $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                    $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                    $tenantphone=Property::getTenantPhone(Property::decryptText($tenantid));

                    $prevbill=($waterbills->Previous!='')?$waterbills->Previous:'';
                    $curbill=($waterbills->Current!='')?$waterbills->Current:'';
                    $saved_bill=$prevbill.':'.$curbill;
                    $loading_bill=$prevbill.':'.$curbill;
                    $sent=Property::getSentDate(Property::decryptText($hid),$month,$waterbills->Current);
                    //first argument should be plot
                    
                    $isNumberBlacklisted=Property::getIfBlacklistedNumber(Property::decryptText($hid),Property::decryptText($tenantid));
                    
                    $watermessage_data[] = array(
                        'pid' => $id,
                        'id' => $hid,
                        'house' =>Property::decryptText($hid),
                        'tid' => $tenantid,
                        'previous' => ($waterbills->Previous!='')?$waterbills->Previous:'',
                        'current' => ($waterbills->Current!='')?$waterbills->Current:'',
                        'saved_previous' => '',
                        'saved_current' => '',
                        'saved' =>'No',
                        'present' =>'Yes',
                        'saved_bill' =>$saved_bill,
                        'loading_bill' =>$loading_bill,
                        'prevmatches' =>'Yes',
                        'messageSent' =>$sent,
                        'cost' => $waterbills->Cost,
                        'units' => $waterbills->Units,
                        'total' => $waterbills->Total,
                        'status' => $waterbills->Status,
                        'total_os' => $waterbills->Total_OS,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'phone' =>$tenantphone,
                        'isNumberBlacklisted' =>$isNumberBlacklisted,
                        'waterid' => $waterid,
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'monthdate'=>Property::getMonthDateAddWaterP($month),
                        'created_at' => $waterbills->created_at
                    );
                    $sno1++;

                }

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'message'=>'Found '.($sno).' Months',
        ]);
    }


    public function setTenantPageMessages($id){
       
        $properties = Property::all();
        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }

        try { 
            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('plot',Property::decryptText($id))->get();
            }

            $sno1=0;
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= $result['id'];
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone($tid), 0);
                $housename=$result['Housename'];
                $tenantname='';
                $tenantfname='';
                if ($tid=='') {
                    $tenant='Vacated';
                    $tenantname=($rent==0)?'Caretaker':'House Vacant';
                    $tenantfname=($rent==0)?'Caretaker':'House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName(Property::decryptText($tid));
                    $tenantfname=Property::checkCurrentTenantFName(Property::decryptText($tid));
                }

                
                

                
                if($waterbills=Tenant::where('id',Property::decryptText($tid))->get()->first()){
                    
                    $tenantid=$waterbills->id;
                    $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                    $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                    $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)),0);

                    $watermessage_data[] = array(
                        'pid' => $id,
                        'id' => $hid,
                        'house' =>Property::decryptText($hid),
                        'tid' => $tenantid,
                        'status' => $waterbills->Status,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'phone' =>$tenantphone,
                        'isblacklisted' =>$waterbills->isblacklisted,
                        'created_at' => $waterbills->created_at
                    );
                    $sno1++;

                }

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'totals' => $sno1,
            'message'=>'Found '.($sno).' Months',
        ]);
    }


    public function setPaymentPageMessages($id,$month){
       
        $properties = Property::all();
        $propertyinfo= array();
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }

        try { 
            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $sno1=1;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }

            
            $monthnames=Property::getMonthDateMonthPrevious($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $hid= $result['id'];
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone($tid), 0);
                $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),$tid,$month);
                $housename=$result['Housename'];
                $tenantname='';
                $tenantfname='';
                if ($tid=='') {
                    $tenant='Vacated';
                    $tenantname=($rent==0)?'Caretaker':'House Vacant';
                    $tenantfname=($rent==0)?'Caretaker':'House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName($tid);
                    $tenantfname=Property::checkCurrentTenantFName($tid);
                }

                
                

                
                if($waterbills=Water::where('House',Property::decryptText($hid))->where('Month',$month)->get()->first()){
                    
                    $tenantid=$waterbills->tenant;
                    $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                    $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                    $tenantphone=Property::getTenantPhone(Property::decryptText($tenantid));

                    
                    $prevbill=($waterbills->Previous!='')?$waterbills->Previous:'';
                    $curbill=($waterbills->Current!='')?$waterbills->Current:'';
                    $saved_bill=$prevbill.':'.$curbill;
                    $loading_bill=$prevbill.':'.$curbill;
                    $sent=Property::getSentDate(Property::decryptText($hid),$month,$waterbills->Current);
                    $watermessage_data[] = array(
                        'pid' => $id,
                        'id' => $hid,
                        'house' =>Property::decryptText($hid),
                        'tid' => $tenantid,
                        'previous' => ($waterbills->Previous!='')?$waterbills->Previous:'',
                        'current' => ($waterbills->Current!='')?$waterbills->Current:'',
                        'saved_previous' => '',
                        'saved_current' => '',
                        'saved' =>'No',
                        'present' =>'Yes',
                        'saved_bill' =>$saved_bill,
                        'loading_bill' =>$loading_bill,
                        'prevmatches' =>'Yes',
                        'messageSent' =>$sent,
                        'cost' => $waterbills->Cost,
                        'units' => $waterbills->Units,
                        'total' => $waterbills->Total,
                        'status' => $waterbills->Status,
                        'total_os' => $waterbills->Total_OS,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'phone' =>$tenantphone,
                        'waterid' => $waterid,
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'monthdate'=>Property::getMonthDateAddWaterP($month),
                        'created_at' => $waterbills->created_at
                    );
                    $sno1++;

                }

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'message'=>'Found '.($sno).' Months',
        ]);
    }

    public function setReminderPageMessages($id,$month){
        
        $properties = Property::all();
        $propertyinfo= array();
        
        $sno=0;
        foreach ($properties as $property) { 
            $propertyinfo[] = array(
                'sno'=>$sno,
                'id' => $property->id,
                'Plotcode' => $property->Plotcode,
                'Plotname' => $property->Plotname,
                'Plotarea' => $property->Plotarea,
                'Plotaddr' => $property->Plotaddr,
                'Plotdesc' => $property->Plotdesc,
                'Waterbill' => $property->Waterbill,
                'Deposit' => $property->Deposit,
                'Waterdeposit' => $property->Waterdeposit,
                'Outsourced' => $property->Outsourced,
                'Garbage' => $property->Garbage,
                'Kplcdeposit' => $property->Kplcdeposit,
                'totalhouses' =>Property::getTotalHousesHse(Property::decryptText($property->id)),
                // 'totaltenants' =>Property::getTotalTenantsHse(Property::decryptText($property->id)),
                'totaloccupied' =>Property::getTotalHousesOccupied(Property::decryptText($property->id)),
                'created_at' => $property->created_at
            );
            $sno++;
        }

        

        try { 
            $thisproperty='';
            $houseinfo='';
            $thispropert= array();
            if($id==''){
                $thisproperty='';
                $houseinfo='';
            }
            else{
                $thisproperty=Property::findOrFail(Property::decryptText($id));
                $houseinfo=House::where('plot',Property::decryptText($id))->get();
            }

            $startyear=2019;
            $startmonth=1;
            $endyear=date('Y');
            $currentdate= date('Y n');
            $selectedmonthname=Property::getMonthDateMonthPrevious($month);
            $endmonth=12;
            $previousmonths= array();
            $sno=0;
            $sno1=1;
            $rent=0.00;
            for ($i=$endyear; $i >= $startyear; $i--) { 
                if ($i==2019) {
                    $startmonth=7;
                }
                else{
                    $startmonth=1;
                }

                if ($i==$endyear) {
                    $endmonth=date('n');
                }
                else{
                    $endmonth=12;
                }
                for ($m=$endmonth; $m >=$startmonth ; $m--) { 
                    $months= $i.' '.$m;
                    $monthname=Property::getMonthDateMonthPrevious($months);
                    $monthly=Property::getMonthDateDash($months);
                    $yearly=Property::getYearDateDash($months);
                    $currentmonthname=Property::getMonthDateMonthPrevious($currentdate);
                    $previousmonths[] = array(
                        'sno'=>$sno,
                        'month' => $months,
                        'monthname' => $monthname,
                        'monthly' => $monthly,
                        'yearly' => $yearly,
                        'currentdate' => $currentdate
                    );
                    $sno++;
                }
            }

            
            $monthnames=Property::getMonthWaterDate($month);
            $watermessage_data= array();
            foreach($houseinfo as $result){
                $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;
                $KCB=0;$MPesa=0;$Cash=0;$Cheque=0;

                $hid= $result['id'];
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                $tenantphone='+254'.substr(Property::getTenantPhone($tid), 0);
                $waterid=Property::checkCurrentTenantBill(Property::decryptText($hid),$tid,$month);
                $housename=$result['Housename'];
                $tenantname='';
                $tenantfname='';
                if ($tid=='') {
                    $tenant='Vacated';
                    $tenantname=($rent==0)?'Caretaker':'House Vacant';
                    $tenantfname=($rent==0)?'Caretaker':'House Vacant';
                }
                else{
                    $tenantname=Property::checkCurrentTenantName($tid);
                    $tenantfname=Property::checkCurrentTenantFName($tid);
                }
                
                if($payment=Payment::where('House',Property::decryptText($hid))->where('Month',$month)->get()->first()){
                    
                    $tenantid=$payment->tenant;
                    $tenantname=Property::TenantNames(Property::decryptText($tenantid));
                    $tenantfname=Property::TenantFNames(Property::decryptText($tenantid));
                    $tenantphone='+254'.substr(Property::getTenantPhone(Property::decryptText($tenantid)), 0);

                    $Rent           =$payment->Rent;
                    $Water          =$payment->Water;
                    $Garbage        =$payment->Garbage;
                    $Lease          =$payment->Lease;
                    $HseDeposit     =$payment->HseDeposit;
                    $KPLC           =$payment->KPLC;
                    $Waterbill      =$payment->Waterbill;
                    $Arrears        =$payment->Arrears;
                    $Excess         =$payment->Excess;
                    $Equity         =$payment->Equity;
                    $Cooperative    =$payment->Cooperative;
                    $Others         =$payment->Others;
                    $PaidUploaded   =$payment->PaidUploaded;
                    $KCB            =$payment->KCB;
                    $MPesa          =$payment->MPesa;
                    $Cash           =$payment->Cash;
                    $Cheque         =$payment->Cheque;
                    $Penalty        =$payment->Penalty;

                    $TotalUsed  =$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Penalty;
                    $TotalPaid  =$Equity+$Cooperative+$Cash+$Cheque+$MPesa+$KCB+$Others+$PaidUploaded;
                    
                    

                    $CarriedForward=($Arrears-$Excess);

                    $Balance    =($TotalUsed-$TotalPaid)+$CarriedForward;
                    
                    $sent=Property::getSentDatePayment(Property::decryptText($id),Property::decryptText($tenantid),$month);
                    $isNumberBlacklisted=Property::getIfBlacklistedNumber(Property::decryptText($id),Property::decryptText($tenantid));
                    // $isNumberBlacklisted=Property::getTenantIdUsingPhone($tenantphone);
                    $watermessage_data[] = array(
                        'pid' => $id,
                        'id' => $hid,
                        'house' =>Property::decryptText($hid),
                        'tid' => $tenantid,
                        'Rent' => $Rent,
                        'Garbage' => $Garbage,
                        'KPLC' => $KPLC,
                        'HseDeposit' => $HseDeposit,
                        'Water' => $Water,
                        'Lease' => $Lease,
                        'Waterbill' => $Waterbill,
                        'Equity' => $Equity,
                        'Cooperative' => $Cooperative,
                        'Others' => $Others,
                        'Excess' => $Excess,
                        'Arrears' => $Arrears,
                        'PaidUploaded' => $PaidUploaded,
                        'TotalUsed' => $TotalUsed,
                        'TotalPaid' => $TotalPaid,
                        'CarriedForward' => $CarriedForward,
                        'Penalty' => $Penalty,
                        'Balance' => $Balance,
                        'messageSent' =>$sent,
                        'isNumberBlacklisted' =>$isNumberBlacklisted,
                        'housename'=>$housename,
                        'tenantname' => ucwords(strtolower($tenantname)),
                        'tenantfname' => ucwords(strtolower($tenantfname)),
                        'phone' =>$tenantphone,
                        'waterid' => $waterid,
                        'month' => $month,
                        'monthname'=>$monthnames,
                        'monthdate'=>Property::getMonthDateAddWaterP($month),
                        'created_at' => $payment->created_at
                    );
                    $sno1++;

                }

                
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'previousmonths'=>$previousmonths,
            'propertyinfo'=>$propertyinfo,
            'thisproperty'=>$thisproperty,
            'waterbilldata' =>$watermessage_data,
            'currentdate' => $currentdate,
            'currentmonthname' => $currentmonthname,
            'selectedmonthname' => $selectedmonthname,
            'selectedmonth' => $month,
            'totals' => $sno1,
            'message'=>'Found '.($sno).' Months',
        ]);
    }

    
    
    public function deleteNewMessage(Request $request)
    {
        try{
            $id=$request->input('id');
            if($message = Message::find($id)){
                $current_date=date('Y-m-d H:i:s');
               
                $message->deleted_at=$current_date;
                $message->save();

                // $message->delete();
                $success="Message Marked as Deleted.\n";
                return response()->json([
                    'status'=>200,
                    'message'=>$success,
                ]);
            }
            else{
                $error="Message is Not Found.\n";
                return response()->json([
                    'status'=>401,
                    'message'=>$error,
                ]);
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error="Message Not Deleted.\n".$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error="Message is Occupied.\n";
            }
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error="Message Not Deleted.\n".$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    } 

    public function saveRentGarbagenew(Request $request)
    {
        $hid=$request->input('hid');
        $paymentid=$request->input('paymentid');
        $pid=$request->input('pid');
        $Tenant=$request->input('Tenant');
        $month=$request->input('month');

        
        if ($Tenant=="") {
            $error='No Tenant Selected!';
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        try {
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $Total=$request->input('Total');

            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= Property::getNextMonthdate($month);
            $nextmonth= Property::getNextMonth($month,$monthdate);
            $housename=Property::getHouseName(Property::decryptText($hid));
            // $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;

            $monthdate= Property::getLastMonthdate($month);
            $lastmonth= Property::getLastMonth($month,$monthdate);
            $bal=Property::PaymentBal(Property::decryptText($Tenant),Property::decryptText($hid),$lastmonth);
            $Arrears=0.00;
            $Excess=0.00;
            if ($bal>0) {
                $Arrears=$bal;
            }
            elseif ($bal<0) {
                $Excess=abs($bal);
            }
            if($paymentid==''){
                $paymentsnew = new Payment;
                $paymentsnew->plot=Property::decryptText($pid);
                $paymentsnew->house=Property::decryptText($hid);
                $paymentsnew->tenant=Property::decryptText($Tenant);
                $paymentsnew->Month=$month;
                $paymentsnew->Rent=$Rent;
                $paymentsnew->Garbage=$Garbage;
                $paymentsnew->Excess=$Excess;
                $paymentsnew->Arrears=$Arrears;
                if($paymentsnew->save()){
                    return response()->json([
                        'status'=>200,
                        'message'=>'Rent & Garbage Saved',
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>'Rent & Garbage Not Saved',
                    ]);
                }
            }
            else{
                $paymentsnew =Payment::findOrFail($paymentid);
                $paymentsnew->plot=Property::decryptText($pid);
                $paymentsnew->house=Property::decryptText($hid);
                $paymentsnew->tenant=Property::decryptText($Tenant);
                $paymentsnew->Month=$month;
                $paymentsnew->Rent=$Rent;
                $paymentsnew->Garbage=$Garbage;
                if($paymentsnew->save()){
                    return response()->json([
                        'status'=>200,
                        'message'=>'Rent & Garbage Saved',
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>'Rent & Garbage Not Saved',
                    ]);
                }
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }


    public function updateRentGarbage(Request $request)
    {
        $hid=$request->input('hid');
        $paymentid=$request->input('paymentid');
        $pid=$request->input('pid');
        $Tenant=$request->input('Tenant');
        $month=$request->input('month');

        
        if ($Tenant=="") {
            $error='No Tenant Selected!';
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        try {
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $Total=$request->input('Total');

            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= Property::getNextMonthdate($month);
            $nextmonth= Property::getNextMonth($month,$monthdate);
            $housename=Property::getHouseName(Property::decryptText($hid));
            // $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;

        
            if($paymentid!=''){
                $paymentsnew =Payment::findOrFail($paymentid);
                $paymentsnew->plot=Property::decryptText($pid);
                $paymentsnew->house=Property::decryptText($hid);
                $paymentsnew->tenant=Property::decryptText($Tenant);
                $paymentsnew->Month=$month;
                $paymentsnew->Rent=$Rent;
                $paymentsnew->Garbage=$Garbage;
                if($paymentsnew->save()){
                    return response()->json([
                        'status'=>200,
                        'message'=>'Rent & Garbage Saved',
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>'Rent & Garbage Not Saved',
                    ]);
                }
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>'Please Use Add button',
                ]);
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    


public function saveMonthlyBillnew(Request $request)
    {
        $hid=$request->input('hid');
        $paymentid=$request->input('paymentid');
        $pid=$request->input('pid');
        $Tenant=$request->input('Tenant');
        $month=$request->input('month');

        
        if ($Tenant=="") {
            $error='No Tenant Selected!';
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        try {
            $Rent=$request->input('Rent');
            $Garbage=$request->input('Garbage');
            $Total=$request->input('Total');

            $DateTrans=date('Y-m-d');
            $explomonth=explode(' ', $month);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $monthdate= Property::getNextMonthdate($month);
            $nextmonth= Property::getNextMonth($month,$monthdate);
            $housename=Property::getHouseName(Property::decryptText($hid));
            // $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;

            $Arrears=$request->input('Arrears');
            $Excess=$request->input('Excess');
            $KPLC=$request->input('KPLC');
            $Water=$request->input('Water');
            $Lease=$request->input('Lease');
            $HseDeposit=$request->input('HseDeposit');
            // if ($bal>0) {
            //     $Arrears=$bal;
            // }
            // elseif ($bal<0) {
            //     $Excess=abs($bal);
            // }
            if($paymentid==''){
                $paymentsnew = new Payment;
                $paymentsnew->plot=Property::decryptText($pid);
                $paymentsnew->house=Property::decryptText($hid);
                $paymentsnew->tenant=Property::decryptText($Tenant);
                $paymentsnew->Month=$month;
                $paymentsnew->Rent=$Rent;
                $paymentsnew->Garbage=$Garbage;
                $paymentsnew->Excess=$Excess;
                $paymentsnew->Arrears=$Arrears;
                $paymentsnew->KPLC=$KPLC;
                $paymentsnew->HseDeposit=$HseDeposit;
                $paymentsnew->Water=$Water;
                $paymentsnew->Lease=$Lease;
                if($paymentsnew->save()){
                    return response()->json([
                        'status'=>200,
                        'message'=>'Monthly Bill Saved',
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>'Monthly Bill Not Saved',
                    ]);
                }
            }
            else{
                $paymentsnew =Payment::findOrFail($paymentid);
                $paymentsnew->Month=$month;
                $paymentsnew->Rent=$Rent;
                $paymentsnew->Garbage=$Garbage;
                $paymentsnew->Excess=$Excess;
                $paymentsnew->Arrears=$Arrears;
                $paymentsnew->KPLC=$KPLC;
                $paymentsnew->HseDeposit=$HseDeposit;
                $paymentsnew->Water=$Water;
                $paymentsnew->Lease=$Lease;
                if($paymentsnew->save()){
                    return response()->json([
                        'status'=>200,
                        'message'=>'Monthly Bill Saved',
                    ]);
                }
                else{
                    return response()->json([
                        'status'=>500,
                        'message'=>'Monthly Bill Not Saved',
                    ]);
                }
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }
    

    public function generateRentGarbageAll(Request $request){
        // $hid=$request->input('hid');
        // $paymentid=$request->input('paymentid');
        $pid=$request->input('savepid');
        // $Tenant=$request->input('Tenant');
        $month=$request->input('savemonth');

        
        if ($pid=="") {
            $error='No Property Selected!';
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }

        $msgerror="";
        $sentsuccess='';
        $successcount='';
        $senterror='';
        $errorcount=0;
        $savedcount=0;
        $savederror='';
        $vacantcount=0;
        $vacanterror='';
        $allcount=0;
        

        

        try {
            $houseinfo=House::where('Plot',Property::decryptText($pid))->get();
            foreach($houseinfo as $result){
                $Rent=$result->Rent;
                $Garbage=$result->Garbage;

                $hid=$result->id;
                $Tenant=Property::checkCurrentTenant(Property::decryptText($hid));
                
                
                if($Tenant==null || $Tenant=='null'){
                    $vacantcount++;
                    $vacanterror =$vacanterror.$housename.' '.$month.',';
                }
                else{
                    $paymentid=Property::PaymentId(Property::decryptText($Tenant),Property::decryptText($hid),$month);
                    $DateTrans=date('Y-m-d');
                    $explomonth=explode(' ', $month);
                    $years=$explomonth[0];
                    $months=$explomonth[1];
                    $monthdate= Property::getNextMonthdate($month);
                    $nextmonth= Property::getNextMonth($month,$monthdate);
                    $housename=Property::getHouseName(Property::decryptText($hid));
                    // $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;

                    $monthdate= Property::getLastMonthdate($month);
                    $lastmonth= Property::getLastMonth($month,$monthdate);
                    $bal=Property::PaymentBal(Property::decryptText($Tenant),Property::decryptText($hid),$lastmonth);
                    $Arrears=0.00;
                    $Excess=0.00;
                    if ($bal>0) {
                        $Arrears=$bal;
                    }
                    elseif ($bal<0) {
                        $Excess=abs($bal);
                    }
                    if($paymentid==''){
                        $paymentsnew = new Payment;
                        $paymentsnew->plot=Property::decryptText($pid);
                        $paymentsnew->house=Property::decryptText($hid);
                        $paymentsnew->tenant=Property::decryptText($Tenant);
                        $paymentsnew->Month=$month;
                        $paymentsnew->Rent=$Rent;
                        $paymentsnew->Garbage=$Garbage;
                        $paymentsnew->Excess=$Excess;
                        $paymentsnew->Arrears=$Arrears;
                        if($paymentsnew->save()){
                            $successcount++;
                            $sentsuccess =$sentsuccess.$housename.' '.$month.',';
                        }
                        else{
                            $errorcount++;
                            $senterror =$senterror.$housename.' '.$month.',';
                        }
                    }
                    else{
                        // return response()->json([
                        //     'status'=>500,
                        //     'message'=>$paymentid,
                        // ]);
                        
                        $paymentsnew =Payment::findOrFail($paymentid);
                        
                        $Waterbill=$paymentsnew->Waterbill;
                        $Rent1=$paymentsnew->Rent;
                        $Garbage1=$paymentsnew->Garbage;

                        $Total=$Rent+$Garbage;
                        $Total1=$Rent1+$Garbage1;

                        if($Waterbill >0){
                            if($Total1 != $Total){
                                $paymentsnew->Rent=$Rent;
                                $paymentsnew->Garbage=$Garbage;
                                $paymentsnew->Excess=$Excess;
                                $paymentsnew->Arrears=$Arrears;
                                if($paymentsnew->save()){
                                    $successcount++;
                                    $sentsuccess =$sentsuccess.$housename.' '.$month.',';
                                }
                                else{
                                    $errorcount++;
                                    $senterror =$senterror.$housename.' '.$month.',';
                                }
                            }
                            else{
                                $savedcount++;
                                $savederror =$savederror.$housename.' '.$month.',';
                            }
                        }
                        else{
                            if($Total1 != $Total){
                                $paymentsnew->Rent=$Rent;
                                $paymentsnew->Garbage=$Garbage;
                                $paymentsnew->Excess=$Excess;
                                $paymentsnew->Arrears=$Arrears;
                                if($paymentsnew->save()){
                                    $successcount++;
                                    $sentsuccess =$sentsuccess.$housename.' '.$month.',';
                                }
                                else{
                                    $errorcount++;
                                    $senterror =$senterror.$housename.' '.$month.',';
                                }
                            }
                            else{
                                $savedcount++;
                                $savederror =$savederror.$housename.' '.$month.',';
                            }
                        }
                    }
                }
            }

            $success='';
            $msgs='';
            if($sentsuccess!=''){
                $success=$success."Rent&Garbage Generated for ".$sentsuccess." /n";
                $msgs=$msgs."About ".$successcount." House's Rent & Garbage was Generated Successfully.\n";
            }
            if($senterror!=''){
                $success=$success."Rent&Garbage not Generated ".$senterror." /n";
                $msgs=$msgs."Rent & Garbage was not Generated for About ".$errorcount." Houses .\n";
            }
            if($vacanterror!=''){
                $success=$success."Vacant houses ".$vacanterror." /n";
                $msgs=$msgs."Rent&Garbage was not Generated for ".$vacantcount." Vacant Houses .\n";
            }
            if($savederror!=''){
                $success=$success."Rent&Garbage Already Generated ".$savederror." /n";
                $msgs=$msgs."Rent & Garbage already Generated for About ".$savedcount." Houses .\n";
            }

            Property::setUserLogs($success);

            return response()->json([
                'status'=>200,
                'message'=>$msgs,
            ]);
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public function generateRentGarbageSelected(Request $request){
        // $hid=$request->input('hid');
        // $paymentid=$request->input('paymentid');
        $pid=$request->input('savepid');
        $waterbillvaluesupdate=$request->input('waterbillvaluesupdate');
        $month=$request->input('savemonth');

        
        if ($pid=="") {
            $error='No Property Selected!';
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }

        $msgerror="";
        $sentsuccess='';
        $successcount='';
        $senterror='';
        $errorcount=0;
        $savedcount=0;
        $savederror='';
        $vacantcount=0;
        $vacanterror='';
        $allcount=0;
        

        try {
            $allmessages=implode(",", $waterbillvaluesupdate);
            $eachmessage=explode(",", $allmessages);
            
            foreach ($eachmessage as $eachmsg) {
                $mms=explode("?", $eachmsg);
                $hid=$mms[0];
                $housename=$mms[1];
                $tid=$mms[2];
                $tenantname=$mms[3];
                $pid=$mms[4];
                $paymentid=$mms[5];

                $houseinfo=House::findOrFail(Property::decryptText($hid));

                $Rent=$houseinfo->Rent;
                $Garbage=$houseinfo->Garbage;

                $Tenant=$tid;

                if($Tenant==null || $Tenant=='null'){
                    $vacantcount++;
                    $vacanterror =$vacanterror.$housename.' '.$month.',';
                }
                else{
                    $DateTrans=date('Y-m-d');
                    $explomonth=explode(' ', $month);
                    $years=$explomonth[0];
                    $months=$explomonth[1];
                    $monthdate= Property::getNextMonthdate($month);
                    $nextmonth= Property::getNextMonth($month,$monthdate);
                    $housename=Property::getHouseName(Property::decryptText($hid));
                    // $Description=$years.' Month '.$months.' '.$housename.' '.'Water :'.'Units '.$Units.'PerUnit'.$Cost.'Cur:'.$Current.'Previous:'.$Previous;

                    $monthdate= Property::getLastMonthdate($month);
                    $lastmonth= Property::getLastMonth($month,$monthdate);
                    $bal=Property::PaymentBal(Property::decryptText($Tenant),Property::decryptText($hid),$lastmonth);
                    $Arrears=0.00;
                    $Excess=0.00;
                    if ($bal>0) {
                        $Arrears=$bal;
                    }
                    elseif ($bal<0) {
                        $Excess=abs($bal);
                    }
                    if($paymentid==''){
                        $paymentsnew = new Payment;
                        $paymentsnew->plot=Property::decryptText($pid);
                        $paymentsnew->house=Property::decryptText($hid);
                        $paymentsnew->tenant=Property::decryptText($Tenant);
                        $paymentsnew->Month=$month;
                        $paymentsnew->Rent=$Rent;
                        $paymentsnew->Garbage=$Garbage;
                        $paymentsnew->Excess=$Excess;
                        $paymentsnew->Arrears=$Arrears;
                        if($paymentsnew->save()){
                            $successcount++;
                            $sentsuccess =$sentsuccess.$housename.' '.$month.',';
                        }
                        else{
                            $errorcount++;
                            $senterror =$senterror.$housename.' '.$month.',';
                        }
                    }
                    else{
                        $paymentsnew =Payment::findOrFail($paymentid);
                        
                        $Waterbill=$paymentsnew->Waterbill;
                        $Rent1=$paymentsnew->Rent;
                        $Garbage1=$paymentsnew->Garbage;

                        $Total=$Rent+$Garbage;
                        $Total1=$Rent1+$Garbage1;

                        if($Waterbill >0){
                            if($Total1 != $Total){
                                $paymentsnew->Rent=$Rent;
                                $paymentsnew->Garbage=$Garbage;
                                $paymentsnew->Excess=$Excess;
                                $paymentsnew->Arrears=$Arrears;
                                if($paymentsnew->save()){
                                    $successcount++;
                                    $sentsuccess =$sentsuccess.$housename.' '.$month.',';
                                }
                                else{
                                    $errorcount++;
                                    $senterror =$senterror.$housename.' '.$month.',';
                                }
                            }
                            else{
                                $savedcount++;
                                $savederror =$savederror.$housename.' '.$month.',';
                            }
                        }
                        else{
                            if($Total1 != $Total){
                                $paymentsnew->Rent=$Rent;
                                $paymentsnew->Garbage=$Garbage;
                                $paymentsnew->Excess=$Excess;
                                $paymentsnew->Arrears=$Arrears;
                                if($paymentsnew->save()){
                                    $successcount++;
                                    $sentsuccess =$sentsuccess.$housename.' '.$month.',';
                                }
                                else{
                                    $errorcount++;
                                    $senterror =$senterror.$housename.' '.$month.',';
                                }
                            }
                            else{
                                $savedcount++;
                                $savederror =$savederror.$housename.' '.$month.',';
                            }
                        }
                    }
                }
            }

            $success='';
            $msgs='';
            if($sentsuccess!=''){
                $success=$success."Rent&Garbage Generated for ".$sentsuccess." /n";
                $msgs=$msgs."About ".$successcount." House's Rent & Garbage was Generated Successfully.\n";
            }
            if($senterror!=''){
                $success=$success."Rent&Garbage not Generated ".$senterror." /n";
                $msgs=$msgs."Rent & Garbage was not Generated for About ".$errorcount." Houses .\n";
            }
            if($vacanterror!=''){
                $success=$success."Vacant houses ".$vacanterror." /n";
                $msgs=$msgs."Rent&Garbage was not Generated for ".$vacantcount." Vacant Houses .\n";
            }
            if($savederror!=''){
                $success=$success."Rent&Garbage Already Generated ".$savederror." /n";
                $msgs=$msgs."Rent & Garbage already Generated for About ".$savedcount." Houses .\n";
            }

            Property::setUserLogs($success);

            return response()->json([
                'status'=>200,
                'message'=>$msgs,
            ]);
            
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            return response()->json([
                'status'=>500,
                'message'=>$error,
            ]);
        }
    }

    public static function getUsers(){ 
        $users = User::all();

        return response()->json([
            'status'=>200,
            'users'=>$users,
            'message'=>'Retrieved users successfully',
        ]);
    }
    
    public static function getCurrentUser(){ 
        
        $user = User::orderByDesc('id')->where('id',auth()->user()->id)->get()->first();

        return response()->json([
            'status'=>200,
            'user'=>$user,
            'message'=>'Retrieved user successfully',
        ]);
    }

    public static function getSelectedUser($id){ 
        
        $user = User::orderByDesc('id')->where('id',$id)->get()->first();

        return response()->json([
            'status'=>200,
            'user'=>$user,
            'message'=>'Retrieved user successfully',
        ]);
    }

    public static function getSelectedUserLogs($id,$limit){ 
        $logs = UserLogs::orderByDesc('id')->where('user',$id)->limit($limit)->get();
        // $logs = UserLogs::all();

        return response()->json([
            'status'=>200,
            'userlogs'=>$logs,
            'message'=>'retrieved The logs successfully',
        ]);
    }

    

    public function getappdata(){
        // Set your app credentials
        $username   = Agency::getAfricasUsername();
        $apiKey     = Agency::getAfricasKey();
        $apiFrom    = Agency::getAfricasFrom();

        // Initialize the SDK
        $AT          = new AfricasTalking($username, $apiKey);
        // Get the application service
        
        try {
            $application = $AT->application();
            // Fetch the application data
            $data = $application->fetchApplicationData();
            $enjson=json_encode($data);
            $characters = json_decode($enjson,true);
            $message =$characters["data"]["UserData"]["balance"];
            return response()->json([
                'status'=>200,
                'totalbalance'=>$message,
            ]);
            // return $characters["data"]["UserData"]["balance"];
            // print_r($data);
        } 
        catch(\GuzzleHttp\Exception\ConnectException $e) {
            $error=$e->getMessage();
            // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
            
            $networkerror='cURL error 6:';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                // return "Net Error";
                return response()->json([
                    'status'=>500,
                    'message'=>"No Internet",
                ]);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>"Internal Error",
                ]);
            }
        }
        catch(\Exception $ex){ 
            $error=$ex->getMessage();
            // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
            return response()->json([
                'status'=>500,
                'message'=>"Other Error",
            ]);
        }
        catch(\Exception\ErrorException $ex){ 
            $error=$ex->getMessage();
            // Property::setUserLogs('Error Getting Airtime Bal::'.$error);
            return response()->json([
                'status'=>500,
                'message'=>"Other Error",
            ]);
        }
        
    }

    public function downloadWaterbillExcel($id,$month)
    {
        $file=new Spreadsheet();
        $sheetno=0;

        $styleArray=[
            'borders'=>[
                'outline'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $titlestyleArray=[
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $smallstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];

        $smallnumbersstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        
        if ($id=="All") {
            $watermonthdate =Property::getMonthDateFull($month);
            $propertyinfo = Property::where('Waterbill','Monthly')->get();
            foreach ($propertyinfo as $property) {
                $propertyid= $property->id;
                $propertyname= $property->Plotname;
                $propertycode= $property->Plotcode;
                $file->createSheet();
                $sheet=$file->getSheet($sheetno);

                $sheet->getStyle('A1:H3')->applyFromArray($styleArray);
                $sheet->getStyle('A4:H4')->applyFromArray($titlestyleArray);

                $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);

                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                
                $sheet->getPageMargins()->setTop(0.55);
                $sheet->getPageMargins()->setRight(0.75);
                $sheet->getPageMargins()->setLeft(0.75);
                $sheet->getPageMargins()->setBottom(0.55);

                $sheet->setCellValue('A1',$propertyname.' ('.$propertycode.')');
                $sheet->setCellValue('A2',$propertyname.' Water Bill for '.$watermonthdate);

                $sheet->setCellValue('A4','No');
                $sheet->setCellValue('B4','Hse/No');
                $sheet->setCellValue('C4','Tenant Name');
                $sheet->setCellValue('D4','Previous');
                $sheet->setCellValue('E4','Current');
                $sheet->setCellValue('F4','Unit Cost');
                $sheet->setCellValue('G4','Consumption');
                $sheet->setCellValue('H4','Total');

                $count=5;
                $sno=1;

                $houseinfo=House::where('Plot',Property::decryptText($propertyid))->get(['id','Plot','Housename','Rent']);
                $previousunits='';
                $currentunits='';
                $unitscost='';
                $units='';
                $totals='';

                $previousunitstotals=0;
                $currentunitstotals=0;
                $unitstotals=0;
                $totalsall=0;

                foreach($houseinfo as $result){
                    $hid= $result['id'];
                    $house=$result['Housename'];
                    $house=explode('-', $house);
                    $countname=count($house);
                    if($countname==1){
                        $housename=$house[0];
                    }
                    else{
                        $housename=$house[1];
                    }
                    $rent= $result['Rent'];
                    $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames(Property::decryptText($tid));
                    }else{
                        $TenantNames=($rent==0)?'Caretaker':'Vacant';
                        $tid="Vacant";
                    }
                    
                    if($waterbills=Water::where('House',Property::decryptText($hid))->where('Month',$month)->get()->first()){
                        $previousunits=$waterbills->Previous;
                        $currentunits=$waterbills->Current;
                        $unitscost=$waterbills->Cost;
                        $units=$waterbills->Units;
                        $totals=$waterbills->Total;
                        $previousunits=($previousunits=='')?0:$previousunits;
                        $currentunits=($currentunits=='')?0:$currentunits;
                        $previousunitstotals = $previousunitstotals + $previousunits;
                        $currentunitstotals  = $currentunitstotals + $currentunits;
                        $unitstotals=$unitstotals + $units;
                        $totalsall=$totalsall + $totals;
                        $tenantid=$waterbills->tenant;
                        $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                    }
                    else{
                        if(date('Y n')==$month){
                            $monthdate= Property::getLastMonthdate($month);
                            $previousmonth= Property::getLastMonth($month,$monthdate);
                            
                            if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Month',$previousmonth)->get()->first()){
                                $previousunits=$prevwaterbills->Current;
                                $previousunits=($previousunits=='')?0:$previousunits;
                                $previousunitstotals = $previousunitstotals + $previousunits;
                                $tenantid=$prevwaterbills->tenant;
                                $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                            }
                            else{
                                $previousunits='';
                                $currentunits='';
                                $unitscost='';
                                $units='';
                                $totals='';
                            }
                        }
                        else{
                            $previousunits='';
                            $currentunits='';
                            $unitscost='';
                            $units='';
                            $totals='';
                        }
                    }
                    
                    $TenantNames=strtoupper($TenantNames);

                    $sheet->setCellValue('A'.$count,$sno);
                    $sheet->setCellValue('B'.$count,$housename);
                    $sheet->setCellValue('C'.$count,$TenantNames);
                    $sheet->setCellValue('D'.$count,$previousunits);
                    $sheet->setCellValue('E'.$count,$currentunits);
                    $sheet->setCellValue('F'.$count,$unitscost);
                    $sheet->setCellValue('G'.$count,$units);
                    $sheet->setCellValue('H'.$count,$totals);

                    $sheet->getStyle('A'.$count.':H'.$count)->applyFromArray($smallstyleArray);
                    $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($smallnumbersstyleArray);

                    $count++;
                    $sno++;
                    // echo $sheetno.' '.$propertyname.' '.$housename.'<br/>';
                }

                $sheet->setCellValue('D'.$count,$previousunitstotals);
                $sheet->setCellValue('E'.$count,$currentunitstotals);
                $sheet->setCellValue('F'.$count,$unitscost);
                $sheet->setCellValue('G'.$count,$unitstotals);
                $sheet->setCellValue('H'.$count,$totalsall);
                    
                $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($smallnumbersstyleArray);
                $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($titlestyleArray);

                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                $sheet->setTitle($propertycode);

                // echo $sheetno.'<br/>';
                $sheetno++;
            }

            $writer = new Xlsx($file);

            // Create a streamed response for download
            $response = new StreamedResponse(
                function () use ($writer, $file) {
                    $writer->save('php://output');
                }
            );

            $logMessage="All Properties Waterbill for the Month of ".$watermonthdate." Has been Generated and Downloaded.";
            Property::setUserLogs($logMessage);

            // Set headers for file download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        }  
        else if ($id=="Summary"){
            $watermonthdate =Property::getMonthDateFull($month);
            $propertyinfo = Property::where('Waterbill','Monthly')->get();

            $propertyname= Property::getPropertyName(Property::decryptText($id));
            $propertycode= Property::getPropertyCode(Property::decryptText($id));
            
            $sheet=$file->getSheet($sheetno);

            $sheet->getStyle('A1:M3')->applyFromArray($styleArray);
            $sheet->getStyle('A4:M4')->applyFromArray($titlestyleArray);

            $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()->setTop(0.55);
            $sheet->getPageMargins()->setRight(0.75);
            $sheet->getPageMargins()->setLeft(0.75);
            $sheet->getPageMargins()->setBottom(0.55);

            $sheet->setCellValue('A1','WATERBILL SUMMARY');
            $sheet->setCellValue('A2','Water Bill Summary for '.$watermonthdate);

            // $lastmonthdate= Property::getLastMonthdate($month);
            $monthdate= Property::getLastMonthdate($month);
            $lastmonth= Property::getLastMonth($month,$monthdate);
            $lastmonthname=Property::dateToMonthNameMonth($lastmonth);

            $sheet->setCellValue('A4','No');
            $sheet->setCellValue('B4','Code');
            $sheet->setCellValue('C4','Property Name');
            $sheet->setCellValue('D4','Billed');
            $sheet->setCellValue('E4','Houses');
            $sheet->setCellValue('F4','Units');
            $sheet->setCellValue('G4','Total Bill');
            $sheet->setCellValue('H4','Units('.$lastmonthname.')');
            $sheet->setCellValue('I4','Total('.$lastmonthname.')');
            $sheet->setCellValue('J4','Msg Sent');
            $sheet->setCellValue('K4','Sent *1');
            $sheet->setCellValue('L4','Sent *2');
            $sheet->setCellValue('M4','Sent *3');

            $count=5;
            $sno=1;

            $totals='';
            $thisbilltotals='';
            $totalunits='';
            $thistotalunits='';
            $totalbillshse='';
            $totalhouseshse='';
            $totalbillsmsghse='';
            $totalbillsmsghseonce='';
            $totalbillsmsghsetwice='';
            $totalbillsmsghsethrice='';
                

            $totalssummary=0;
            $thisbilltotalssummary=0;
            $totalunitssummary=0;
            $thistotalunitssummary=0;
            $totalbillshsesummary=0;
            $totalhouseshsesummary=0;
            $totalbillsmsghsesummary=0;
            $totalbillsmsghseoncesummary=0;
            $totalbillsmsghsetwicesummary=0;
            $totalbillsmsghsethricesummary=0;

            foreach ($propertyinfo as $properties) { 
                $propertyid= $properties->id;
                $propertyname= $properties->Plotname;
                $propertycode= $properties->Plotcode;

                $houseinfo=House::where('Plot',Property::decryptText($propertyid))->get(['id','Plot','Housename','Rent']);
                $totals='';
                $thisbilltotals='';
                $totalunits='';
                $thistotalunits='';
                $totalbillshse='';
                $totalhouseshse='';
                $totalbillsmsghse='';
                $totalbillsmsghseonce='';
                $totalbillsmsghsetwice='';
                $totalbillsmsghsethrice='';
                    

                $totals=(Property::getTotalTotal(Property::decryptText($properties->id),$month))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$month));
                $thisbilltotals=(Property::getTotalTotal(Property::decryptText($properties->id),$lastmonth))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$lastmonth));

                $totalunits = Property::getTotalUnits(Property::decryptText($properties->id),$month);
                $thistotalunits = Property::getTotalUnits(Property::decryptText($properties->id),$lastmonth);
                
                $totalbillshse   = Property::getTotalWaterBillsHse(Property::decryptText($properties->id),$month);
                $totalhouseshse  = Property::getTotalHousesHse(Property::decryptText($properties->id));
                        
                $totalbillsmsghse = Property::getTotalWaterMsgHse(Property::decryptText($properties->id),$month);
                $totalbillsmsghseonce =Property::getTotalWaterMsgSentOnceHse(Property::decryptText($properties->id),$month);
                $totalbillsmsghsetwice =Property::getTotalWaterMsgSentTwiceHse(Property::decryptText($properties->id),$month);
                $totalbillsmsghsethrice =Property::getTotalWaterMsgSentThriceHse(Property::decryptText($properties->id),$month);
                
                $thismonthname=Property::dateToMonthNameMonth($month);
    
                // $nextmonthdate=Property::getNextMonthdate($month);
                // $nextmonth=Property::getNextMonths($nextmonthdate);
                // $nextmonthname=Property::dateToMonthNameMonth($nextmonth);
                //     $waterbill[] = array(
                //         'sno'=>$sno,
                //         'id' => $properties->id,
                //         'plotcode' => $properties->Plotcode,
                //         'plotname' => $properties->Plotname,
                //         'totalbillshse' => Property::getTotalBillsHse(Property::decryptText($properties->id),$month),
                //         'totalhouseshse' =>Property::getTotalHousesHse(Property::decryptText($properties->id)),
                //         'totals' =>$totals,
                //         'thismonthname' =>$thismonthname,
                //         'nextmonthname' =>$nextmonthname,
                //         'thisbilltotals' => $thisbilltotals,
                //         'totalunits' => Property::getTotalUnits(Property::decryptText($properties->id),$month),
                //         'thistotalunits' => Property::getTotalUnits(Property::decryptText($properties->id),$lastmonth),
                //         'totalbillsmsghseonce' =>Property::getTotalWaterMsgSentOnceHse(Property::decryptText($properties->id),$month),
                //         'totalbillsmsghsetwice' =>Property::getTotalWaterMsgSentTwiceHse(Property::decryptText($properties->id),$month),
                //         'totalbillsmsghsethrice' =>Property::getTotalWaterMsgSentThriceHse(Property::decryptText($properties->id),$month),
                //         'totalbillsmsghse' => Property::getTotalWaterMsgHse(Property::decryptText($properties->id),$month),
                //         'month' => $month
                //     );
                //     $sno++;
                // }

                $totalssummary=$totalssummary+$totals;
                $thisbilltotalssummary=$thisbilltotalssummary+$thisbilltotals;
                $totalunitssummary=$totalunitssummary+$totalunits;
                $thistotalunitssummary=$thistotalunitssummary+$thistotalunits;
                $totalbillshsesummary=$totalbillshsesummary+$totalbillshse;
                $totalhouseshsesummary=$totalhouseshsesummary+$totalhouseshse;
                $totalbillsmsghsesummary=$totalbillsmsghsesummary+$totalbillsmsghse;
                $totalbillsmsghseoncesummary=$totalbillsmsghseoncesummary+$totalbillsmsghseonce;
                $totalbillsmsghsetwicesummary=$totalbillsmsghsetwicesummary+$totalbillsmsghsetwice;
                $totalbillsmsghsethricesummary=$totalbillsmsghsethricesummary+$totalbillsmsghsethrice;


                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$propertycode);
                $sheet->setCellValue('C'.$count,$propertyname);
                $sheet->setCellValue('D'.$count,$totalbillshse);
                $sheet->setCellValue('E'.$count,$totalhouseshse);
                $sheet->setCellValue('F'.$count,$totalunits);
                $sheet->setCellValue('G'.$count,$totals);
                $sheet->setCellValue('H'.$count,$thistotalunits);
                $sheet->setCellValue('I'.$count,$thisbilltotals);
                $sheet->setCellValue('J'.$count,$totalbillsmsghse);
                $sheet->setCellValue('K'.$count,$totalbillsmsghseonce);
                $sheet->setCellValue('L'.$count,$totalbillsmsghsetwice);
                $sheet->setCellValue('M'.$count,$totalbillsmsghsethrice);

                $sheet->getStyle('A'.$count.':M'.$count)->applyFromArray($smallstyleArray);
                $sheet->getStyle('D'.$count.':M'.$count)->applyFromArray($smallnumbersstyleArray);


                $count++;
                $sno++;
            }

            $sheet->setCellValue('D'.$count,$totalbillshsesummary);
            $sheet->setCellValue('E'.$count,$totalhouseshsesummary);
            $sheet->setCellValue('F'.$count,$totalunitssummary);
            $sheet->setCellValue('G'.$count,$totalssummary);
            $sheet->setCellValue('H'.$count,$thistotalunitssummary);
            $sheet->setCellValue('I'.$count,$thisbilltotalssummary);
            $sheet->setCellValue('J'.$count,$totalbillsmsghsesummary);
            $sheet->setCellValue('K'.$count,$totalbillsmsghseoncesummary);
            $sheet->setCellValue('L'.$count,$totalbillsmsghsetwicesummary);
            $sheet->setCellValue('M'.$count,$totalbillsmsghsethricesummary);
                
            $sheet->getStyle('D'.$count.':M'.$count)->applyFromArray($smallnumbersstyleArray);
            $sheet->getStyle('D'.$count.':M'.$count)->applyFromArray($titlestyleArray);

            $sheet->mergeCells('A1:M1');
            $sheet->mergeCells('A2:M2');
            $sheet->setTitle('Waterbill for '.$watermonthdate);
                

            // $sheetno++;

            $writer = new Xlsx($file);

            // Create a streamed response for download
            $response = new StreamedResponse(
                function () use ($writer, $file) {
                    $writer->save('php://output');
                }
            );

            $logMessage="A Summary of all Properties Waterbill for the Month of ".$watermonthdate." Has been Generated and Downloaded.";
            Property::setUserLogs($logMessage);

            // Set headers for file download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        }
        else{
            $watermonthdate =Property::getMonthDateFull($month);
            $propertyname= Property::getPropertyName(Property::decryptText($id));
            $propertycode= Property::getPropertyCode(Property::decryptText($id));
            
            $sheet=$file->getSheet($sheetno);

            $sheet->getStyle('A1:H3')->applyFromArray($styleArray);
            $sheet->getStyle('A4:H4')->applyFromArray($titlestyleArray);

            $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()->setTop(0.55);
            $sheet->getPageMargins()->setRight(0.75);
            $sheet->getPageMargins()->setLeft(0.75);
            $sheet->getPageMargins()->setBottom(0.55);

            $sheet->setCellValue('A1',$propertyname.' ('.$propertycode.')');
            $sheet->setCellValue('A2',$propertyname.' Water Bill for '.$watermonthdate);

            $sheet->setCellValue('A4','No');
            $sheet->setCellValue('B4','Hse/No');
            $sheet->setCellValue('C4','Tenant Name');
            $sheet->setCellValue('D4','Previous');
            $sheet->setCellValue('E4','Current');
            $sheet->setCellValue('F4','Unit Cost');
            $sheet->setCellValue('G4','Consumption');
            $sheet->setCellValue('H4','Total');

            $count=5;
            $sno=1;

            $houseinfo=House::where('Plot',Property::decryptText($id))->get(['id','Plot','Housename','Rent']);
            $previousunits='';
            $currentunits='';
            $unitscost='';
            $units='';
            $totals='';

            $previousunitstotals=0;
            $currentunitstotals=0;
            $unitstotals=0;
            $totalsall=0;

            foreach($houseinfo as $result){
                $hid= $result['id'];
                $house=$result['Housename'];
                $house=explode('-', $house);
                $countname=count($house);
                if($countname==1){
                    $housename=$house[0];
                }
                else{
                    $housename=$house[1];
                }
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                if ($tid!="") {
                    $TenantNames=Property::TenantNames(Property::decryptText($tid));
                    

                }else{
                    $TenantNames=($rent==0)?'Caretaker':'Vacant';
                    $tid="Vacant";
                }
                
                if($waterbills=Water::where('House',Property::decryptText($hid))->where('Month',$month)->get()->first()){
                    $previousunits=$waterbills->Previous;
                    $currentunits=$waterbills->Current;
                    $unitscost=$waterbills->Cost;
                    $units=$waterbills->Units;
                    $totals=$waterbills->Total;
                    $previousunits=($previousunits=='')?0:$previousunits;
                    $currentunits=($currentunits=='')?0:$currentunits;
                    $previousunitstotals = $previousunitstotals + $previousunits;
                    $currentunitstotals  = $currentunitstotals + $currentunits;
                    $unitstotals=$unitstotals + $units;
                    $totalsall=$totalsall + $totals;
                    $tenantid=$waterbills->tenant;
                    $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                }
                else{
                    if(date('Y n')==$month){
                        $monthdate= Property::getLastMonthdate($month);
                        $previousmonth= Property::getLastMonth($month,$monthdate);
                        
                        if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Month',$previousmonth)->get()->first()){
                            $previousunits=$prevwaterbills->Current;
                            $previousunits=($previousunits=='')?0:$previousunits;
                            $previousunitstotals = $previousunitstotals + $previousunits;
                            $tenantid=$prevwaterbills->tenant;
                            $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                        }
                        else{
                            $previousunits='';
                            $currentunits='';
                            $unitscost='';
                            $units='';
                            $totals='';
                        }
                    }
                    else{

                        //try to get previous month
                        $monthdate= Property::getLastMonthdate($month);
                        $previousmonth= Property::getLastMonth($month,$monthdate);

                        if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Month',$previousmonth)->get()->first()){
                            $previousunits=$prevwaterbills->Current;
                            $previousunits=($previousunits=='')?0:$previousunits;
                            $previousunitstotals = $previousunitstotals + $previousunits;
                            $tenantid=$prevwaterbills->tenant;
                            $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                        }
                        else{
                            $previousunits='';
                            $currentunits='';
                            $unitscost='';
                            $units='';
                            $totals='';
                        }
                        
                        // $previousunits='';
                        // $currentunits='';
                        // $unitscost='';
                        // $units='';
                        // $totals='';
                    }
                }
                
                $TenantNames=strtoupper($TenantNames);

                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$housename);
                $sheet->setCellValue('C'.$count,$TenantNames);
                $sheet->setCellValue('D'.$count,$previousunits);
                $sheet->setCellValue('E'.$count,$currentunits);
                $sheet->setCellValue('F'.$count,$unitscost);
                $sheet->setCellValue('G'.$count,$units);
                $sheet->setCellValue('H'.$count,$totals);

                $sheet->getStyle('A'.$count.':H'.$count)->applyFromArray($smallstyleArray);
                $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($smallnumbersstyleArray);


                $count++;
                $sno++;

            }

            $sheet->setCellValue('D'.$count,$previousunitstotals);
            $sheet->setCellValue('E'.$count,$currentunitstotals);
            $sheet->setCellValue('F'.$count,$unitscost);
            $sheet->setCellValue('G'.$count,$unitstotals);
            $sheet->setCellValue('H'.$count,$totalsall);
                
            $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($smallnumbersstyleArray);
            $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($titlestyleArray);

            $sheet->mergeCells('A1:H1');
            $sheet->mergeCells('A2:H2');
            $sheet->setTitle($propertycode);
                

            // $sheetno++;

            $writer = new Xlsx($file);

            // Create a streamed response for download
            $response = new StreamedResponse(
                function () use ($writer, $file) {
                    $writer->save('php://output');
                }
            );

            $logMessage=$propertyname." Waterbill for the Month of ".$watermonthdate." Has been Generated and Downloaded.";
            Property::setUserLogs($logMessage);

            // Set headers for file download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
            
        }

    }

    public function downloadWaterbillPerYearExcel($id,$year,$month)
    {
        $file=new Spreadsheet();
        $sheetno=0;

        $styleArray=[
            'borders'=>[
                'outline'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $titlestyleArray=[
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $smallstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];

        $smallnumbersstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        
        //start of per year
        
        $startyear=date('Y');
        $startmonth=1;
        $endmonth=date('n');
        $propertyname= Property::getPropertyName(Property::decryptText($id));
        $propertycode= Property::getPropertyCode(Property::decryptText($id));

        // $startyear=date('Y')-1;
        // $startmonth=1;
        // $endmonth=12;


        if($month==$startyear){
            $startyear=date('Y');
            $startmonth=1;
            $endmonth=date('n');
        }
        else{
            $startyear=$month;
            $startmonth=1;
            $endmonth=12;
        }

        for ($m=$startmonth; $m <=$endmonth ; $m++) { 
            $thismonth= $startyear.' '.$m;
            $monthname=Property::getMonthDateAddWater($thismonth);
            $watermonthdate =Property::getMonthDateFull($thismonth);

            $file->createSheet();
            $sheet=$file->getSheet($sheetno);

            $sheet->getStyle('A1:H3')->applyFromArray($styleArray);
            $sheet->getStyle('A4:H4')->applyFromArray($titlestyleArray);

            $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()->setTop(0.55);
            $sheet->getPageMargins()->setRight(0.75);
            $sheet->getPageMargins()->setLeft(0.75);
            $sheet->getPageMargins()->setBottom(0.55);
            
            $sheet->setCellValue('A1',$propertyname.' ('.$propertycode.')');
            $sheet->setCellValue('A2',$propertyname.' Water Bill for '.$watermonthdate);

            $sheet->setCellValue('A4','No');
            $sheet->setCellValue('B4','Hse/No');
            $sheet->setCellValue('C4','Tenant Name');
            $sheet->setCellValue('D4','Previous');
            $sheet->setCellValue('E4','Current');
            $sheet->setCellValue('F4','Unit Cost');
            $sheet->setCellValue('G4','Consumption');
            $sheet->setCellValue('H4','Total');

            $count=5;
            $sno=1;

            $houseinfo=House::where('Plot',Property::decryptText($id))->get(['id','Plot','Housename','Rent']);
            $previousunits='';
            $currentunits='';
            $unitscost='';
            $units='';
            $totals='';

            $previousunitstotals=0;
            $currentunitstotals=0;
            $unitstotals=0;
            $totalsall=0;

            foreach($houseinfo as $result){
                $hid= $result['id'];
                $house=$result['Housename'];
                $house=explode('-', $house);
                $countname=count($house);
                if($countname==1){
                    $housename=$house[0];
                }
                else{
                    $housename=$house[1];
                }
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                if ($tid!="") {
                    $TenantNames=Property::TenantNames(Property::decryptText($tid));
                }else{
                    $TenantNames=($rent==0)?'Caretaker':'Vacant';
                    $tid="Vacant";
                }
                
                if($waterbills=Water::where('House',Property::decryptText($hid))->where('Month',$thismonth)->get()->first()){
                    $previousunits=$waterbills->Previous;
                    $currentunits=$waterbills->Current;
                    $unitscost=$waterbills->Cost;
                    $units=$waterbills->Units;
                    $totals=$waterbills->Total;
                    $previousunits=($previousunits=='')?0:$previousunits;
                    $currentunits=($currentunits=='')?0:$currentunits;
                    $previousunitstotals = $previousunitstotals + $previousunits;
                    $currentunitstotals  = $currentunitstotals + $currentunits;
                    $unitstotals=$unitstotals + $units;
                    $totalsall=$totalsall + $totals;
                    $tenantid=$waterbills->tenant;
                    $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                }
                else{
                    if(date('Y n')==$thismonth){
                        $monthdate= Property::getLastMonthdate($thismonth);
                        $previousmonth= Property::getLastMonth($thismonth,$monthdate);
                        
                        if($prevwaterbills=Water::where('House',Property::decryptText($hid))->where('Month',$previousmonth)->get()->first()){
                            $previousunits=$prevwaterbills->Current;
                            $previousunits=($previousunits=='')?0:$previousunits;
                            $previousunitstotals = $previousunitstotals + $previousunits;
                            $tenantid=$prevwaterbills->tenant;
                            $TenantNames=Property::TenantNames(Property::decryptText($tenantid));
                        }
                        else{
                            $previousunits='';
                            $currentunits='';
                            $unitscost='';
                            $units='';
                            $totals='';
                        }
                    }
                    else{
                        $previousunits='';
                        $currentunits='';
                        $unitscost='';
                        $units='';
                        $totals='';
                    }
                    
                }
                
                $TenantNames=strtoupper($TenantNames);

                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$housename);
                $sheet->setCellValue('C'.$count,$TenantNames);
                $sheet->setCellValue('D'.$count,$previousunits);
                $sheet->setCellValue('E'.$count,$currentunits);
                $sheet->setCellValue('F'.$count,$unitscost);
                $sheet->setCellValue('G'.$count,$units);
                $sheet->setCellValue('H'.$count,$totals);

                $sheet->getStyle('A'.$count.':H'.$count)->applyFromArray($smallstyleArray);
                $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($smallnumbersstyleArray);


                $count++;
                $sno++;

            }

            $sheet->setCellValue('D'.$count,$previousunitstotals);
            $sheet->setCellValue('E'.$count,$currentunitstotals);
            $sheet->setCellValue('F'.$count,$unitscost);
            $sheet->setCellValue('G'.$count,$unitstotals);
            $sheet->setCellValue('H'.$count,$totalsall);
            
            $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($smallnumbersstyleArray);
            $sheet->getStyle('D'.$count.':H'.$count)->applyFromArray($titlestyleArray);

            $sheet->mergeCells('A1:H1');
            $sheet->mergeCells('A2:H2');
            $sheet->setTitle($propertycode.'('.$monthname.')');

            $sheetno++;
            
        }


        $writer = new Xlsx($file);

        // Create a streamed response for download
        $response = new StreamedResponse(
            function () use ($writer, $file) {
                $writer->save('php://output');
            }
        );

        $logMessage=$propertyname." Waterbill for the Year ".$startyear." Has been Generated and Downloaded.";
        Property::setUserLogs($logMessage);

        // Set headers for file download
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

    }

    public function downloadPaymentsExcel($id,$month)
    {
        $file=new Spreadsheet();
        $sheetno=0;

        $styleArray=[
            'borders'=>[
                'outline'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $titlestyleArray=[
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $smallstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];

        $smallnumbersstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];

        
        if ($id=="All") {
            $watermonthdate =Property::getMonthDateFull($month);
            $propertyinfo = Property::all();
            foreach ($propertyinfo as $property) {
                $propertyid= $property->id;
                $propertyname= $property->Plotname;
                $propertycode= $property->Plotcode;
                $file->createSheet();
                // $sheet=$file->getSheet(0);
                
                $sheet=$file->getSheet($sheetno);

                

                $sheet->getStyle('A1:U3')->applyFromArray($styleArray);
                $sheet->getStyle('A4:U4')->applyFromArray($titlestyleArray);

                $sheet->setCellValue('A1',$propertyname.' ('.$propertycode.')');
                $sheet->setCellValue('A2',$propertyname.' Payments for '.$watermonthdate);

                
                $sheet->getStyle('A1:U1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2:U2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('S')->setAutoSize(true);


                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                
                $sheet->getPageMargins()->setTop(1);
                $sheet->getPageMargins()->setRight(0.75);
                $sheet->getPageMargins()->setLeft(0.75);
                $sheet->getPageMargins()->setBottom(1);
                
                $sheet->setCellValue('A4','No');
                $sheet->setCellValue('B4','Hse/No');
                $sheet->setCellValue('C4','Tenant Name');
                $sheet->setCellValue('D4','Excess');
                $sheet->setCellValue('E4','Arrears');
                $sheet->setCellValue('F4','Rent');
                $sheet->setCellValue('G4','Bin');
                $sheet->setCellValue('H4','Waterbill');
                $sheet->setCellValue('I4','Rent D');
                $sheet->setCellValue('J4','Water D');
                $sheet->setCellValue('K4','KPLC D');
                $sheet->setCellValue('L4','Lease');
                $sheet->setCellValue('M4','Penalty');
                $sheet->setCellValue('N4','Totals');
                $sheet->setCellValue('O4','Coop');
                $sheet->setCellValue('P4','Equity');
                $sheet->setCellValue('Q4','Others');
                $sheet->setCellValue('R4','Uploaded');
                $sheet->setCellValue('S4','Dates');
                $sheet->setCellValue('T4','Paid');
                $sheet->setCellValue('U4','Bal');

                $count=5;
                $sno=1;

                $houseinfo=House::where('Plot',Property::decryptText($propertyid))->get(['id','Plot','Housename','Rent']);
                

                $allRent=0;$allWater=0;$allGarbage=0;$allLease=0;$allHseDeposit=0;$allKPLC=0;$allWaterbill=0;$allArrears=0;
                $allExcess=0;$allEquity=0;$allCooperative=0;$allOthers=0;$allPaidUploaded=0;$allPenalty=0;

                foreach($houseinfo as $result){
                    $hid= $result['id'];
                    $house=$result['Housename'];
                    $house=explode('-', $house);
                    $countname=count($house);
                    if($countname==1){
                        $housename=$house[0];
                    }
                    else{
                        $housename=$house[1];
                    }

                    // $housename= $result['Housename'];
                    $rent= $result['Rent'];
                    $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames(Property::decryptText($tid));
                    }else{
                        $TenantNames=($rent==0)?'Caretaker':'Vacant';
                        $tid="Vacant";
                    }
                    $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                    $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                    $paymentdates='';
                    if($allpayments=Payment::where('Plot',Property::decryptText($propertyid))->where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
                        $Rent=$allpayments->Rent;$Water=$allpayments->Water;$Garbage=$allpayments->Garbage;
                        $Lease=$allpayments->Lease;$HseDeposit=$allpayments->HseDeposit;$KPLC=$allpayments->KPLC;
                        $Waterbill=$allpayments->Waterbill;$Arrears=$allpayments->Arrears;
                        $Excess=$allpayments->Excess;$Equity=$allpayments->Equity;$Cooperative=$allpayments->Cooperative;
                        $Others=$allpayments->Others;$PaidUploaded=$allpayments->PaidUploaded;$Penalty=$allpayments->Penalty;

                        $allRent=$allRent+$Rent;
                        $allWater=$allWater+$Water;
                        $allGarbage=$allGarbage+$Garbage;
                        $allLease=$allLease+$Lease;
                        $allHseDeposit=$allHseDeposit+$HseDeposit;
                        $allKPLC=$allKPLC+$KPLC;
                        $allWaterbill=$allWaterbill+$Waterbill;
                        $allArrears=$allArrears+$Arrears;
                        $allExcess=$allExcess+$Excess;
                        $allEquity=$allEquity+$Equity;
                        $allCooperative=$allCooperative+$Cooperative;
                        $allOthers=$allOthers+$Others;
                        $allPaidUploaded=$allPaidUploaded+$PaidUploaded;
                        $allPenalty=$allPenalty+$Penalty;

                        $paymentid=$allpayments->id;
                        $thispayment=PaymentDate::where('PId',$paymentid)->get();
                        $i=0;
                        foreach($thispayment as $payment){
                            $i++;
                            $Amount=$payment->Amount;
                            $Date_Transacted=$payment->Date_Transacted;
                            $Through=$payment->Through;
                            $paymentdateshort =Property::getMonthPaymentDateShort($Date_Transacted);
                            if($i==1){
                                $paymentdates=$paymentdates.$Amount.'('.$paymentdateshort.')';
                            }
                            else{
                                $paymentdates=$paymentdates.', '.$Amount.'('.$paymentdateshort.')';
                            }
                            
                        }

                    }

                    $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears+$Penalty;
                    $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                    $Balance=$TotalUsed-$TotalPaid;
                    
                    $TenantNames=strtoupper($TenantNames);

                    
                    $sheet->setCellValue('A'.$count,$sno);
                    $sheet->setCellValue('B'.$count,$housename);
                    $sheet->setCellValue('C'.$count,$TenantNames);
                    $sheet->setCellValue('D'.$count,$Excess);
                    $sheet->setCellValue('E'.$count,$Arrears);
                    $sheet->setCellValue('F'.$count,$Rent);
                    $sheet->setCellValue('G'.$count,$Garbage);
                    $sheet->setCellValue('H'.$count,$Waterbill);
                    $sheet->setCellValue('I'.$count,$HseDeposit);
                    $sheet->setCellValue('J'.$count,$Water);
                    $sheet->setCellValue('K'.$count,$KPLC);
                    $sheet->setCellValue('L'.$count,$Lease);
                    $sheet->setCellValue('M'.$count,$Penalty);
                    $sheet->setCellValue('N'.$count,$TotalUsed);
                    $sheet->setCellValue('O'.$count,$Cooperative);
                    $sheet->setCellValue('P'.$count,$Equity);
                    $sheet->setCellValue('Q'.$count,$Others);
                    $sheet->setCellValue('R'.$count,$PaidUploaded);
                    $sheet->setCellValue('S'.$count,$paymentdates);
                    $sheet->setCellValue('T'.$count,$TotalPaid);
                    $sheet->setCellValue('U'.$count,$Balance);

                    $sheet->getStyle('A'.$count.':U'.$count)->applyFromArray($smallstyleArray);
                    $sheet->getStyle('D'.$count.':R'.$count)->applyFromArray($smallnumbersstyleArray);
                    
                    $sheet->getStyle('T'.$count.':U'.$count)->applyFromArray($smallnumbersstyleArray);

                    // $sheet->getStyle('S'.$count)->getAlignment()->setWrapText(true);

                    $count++;
                    $sno++;
                    // echo $sheetno.' '.$propertyname.' '.$housename.'<br/>';
                }

                $allTotalUsed=$allRent+$allWater+$allGarbage+$allLease+$allHseDeposit+$allKPLC+$allWaterbill+$allArrears+$allPenalty;
                $allTotalPaid=$allExcess+$allEquity+$allCooperative+$allOthers+$allPaidUploaded;
                $allBalance=$allTotalUsed-$allTotalPaid;
                
                $sheet->setCellValue('D'.$count,$allExcess);
                $sheet->setCellValue('E'.$count,$allArrears);
                $sheet->setCellValue('F'.$count,$allRent);
                $sheet->setCellValue('G'.$count,$allGarbage);
                $sheet->setCellValue('H'.$count,$allWaterbill);
                $sheet->setCellValue('I'.$count,$allHseDeposit);
                $sheet->setCellValue('J'.$count,$allWater);
                $sheet->setCellValue('K'.$count,$allKPLC);
                $sheet->setCellValue('L'.$count,$allLease);
                $sheet->setCellValue('M'.$count,$allPenalty);
                $sheet->setCellValue('N'.$count,$allTotalUsed);
                $sheet->setCellValue('O'.$count,$allCooperative);
                $sheet->setCellValue('P'.$count,$allEquity);
                $sheet->setCellValue('Q'.$count,$allOthers);
                $sheet->setCellValue('R'.$count,$allPaidUploaded);
                $sheet->setCellValue('S'.$count,'');
                $sheet->setCellValue('T'.$count,$allTotalPaid);
                $sheet->setCellValue('U'.$count,$allBalance);

                
                // $sheet->getStyle('D'.$count.':U'.$count)->applyFromArray($styleArray);
                $sheet->getStyle('D'.$count.':U'.$count)->applyFromArray($titlestyleArray);
                

                $sheet->mergeCells('A1:U1');
                $sheet->mergeCells('A2:U2');
                $sheet->setTitle($propertycode);

                $sheetno++;
            }

            $writer = new Xlsx($file);

            // Create a streamed response for download
            $response = new StreamedResponse(
                function () use ($writer, $file) {
                    $writer->save('php://output');
                }
            );

            $logMessage="All Properties Payments for the Month of ".$watermonthdate." Has been Generated and Downloaded.";
            Property::setUserLogs($logMessage);
    
            // Set headers for file download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
    
            return $response;
        }  
        else if ($id=="Summary"){
            $watermonthdate =Property::getMonthDateFull($month);
            $propertyinfo = Property::all();
            
            $sheet=$file->getSheet($sheetno);

            $sheet->getStyle('A1:Y3')->applyFromArray($styleArray);
            $sheet->getStyle('A4:Y4')->applyFromArray($titlestyleArray);

            $sheet->getStyle('A1:Y1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:Y2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()->setTop(0.55);
            $sheet->getPageMargins()->setRight(0.75);
            $sheet->getPageMargins()->setLeft(0.75);
            $sheet->getPageMargins()->setBottom(0.55);

            $sheet->setCellValue('A1','PAYMENT AND BILLING SUMMARY');
            $sheet->setCellValue('A2','Payment Summary for '.$watermonthdate);

            // $lastmonthdate= Property::getLastMonthdate($month);
            $monthdate= Property::getLastMonthdate($month);
            $lastmonth= Property::getLastMonth($month,$monthdate);
            $lastmonthname=Property::dateToMonthNameMonth($lastmonth);

            $sheet->setCellValue('A4','No');
            $sheet->setCellValue('B4','Hse/No');
            $sheet->setCellValue('C4','Tenant Name');
            $sheet->setCellValue('D4','Excess');
            $sheet->setCellValue('E4','Arrears');
            $sheet->setCellValue('F4','Rent');
            $sheet->setCellValue('G4','Bin');
            $sheet->setCellValue('H4','Waterbill');
            $sheet->setCellValue('I4','Rent D');
            $sheet->setCellValue('J4','Water D');
            $sheet->setCellValue('K4','KPLC D');
            $sheet->setCellValue('L4','Lease');
            $sheet->setCellValue('M4','Penalty');
            $sheet->setCellValue('N4','Totals');
            $sheet->setCellValue('O4','Coop');
            $sheet->setCellValue('P4','Equity');
            $sheet->setCellValue('Q4','KCB');
            $sheet->setCellValue('R4','MPesa');
            $sheet->setCellValue('S4','Cash');
            $sheet->setCellValue('T4','Cheque');
            $sheet->setCellValue('U4','Others');
            $sheet->setCellValue('V4','Uploaded');
            $sheet->setCellValue('W4','Billed');
            $sheet->setCellValue('X4','Paid');
            $sheet->setCellValue('Y4','Bal');

            $count=5;
            $sno=1;

            $allRent=0;
            $allWater=0;
            $allGarbage=0;
            $allLease=0;
            $allHseDeposit=0;
            $allKPLC=0;
            $allWaterbill=0;
            $allArrears=0;
            $allExcess=0;
            $allEquity=0;
            $allCooperative=0;
            $allKCB=0;
            $allMPesa=0;
            $allCash=0;
            $allCheque=0;
            $allOthers=0;
            $allPaidUploaded=0;
            $allPenalty=0;
            $alltotalbillshse=0;
            $allTotalUsed  =0;
            $allTotalPaid  =0;
            $allBalance  =0;

            foreach ($propertyinfo as $properties) { 
                $propertyid= $properties->id;
                $propertyname= $properties->Plotname;
                $propertycode= $properties->Plotcode;
                    

                // $totals=(Property::getTotalTotal(Property::decryptText($properties->id),$month))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$month));
                // $thisbilltotals=(Property::getTotalTotal(Property::decryptText($properties->id),$lastmonth))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$lastmonth));

                // $totalunits = Property::getTotalUnits(Property::decryptText($properties->id),$month);
                // $thistotalunits = Property::getTotalUnits(Property::decryptText($properties->id),$lastmonth);
                
                $totalbillshse   = Property::getTotalBillsHse(Property::decryptText($properties->id),$month);
                $totalhouseshse  = Property::getTotalHousesHse(Property::decryptText($properties->id));
               
                // $totals=(Property::getTotalTotal(Property::decryptText($properties->id),$month))+(Property::getTotalTotal_OS(Property::decryptText($properties->id),$month));
                $Rent=Property::MonthlyRent(Property::decryptText($properties->id),$month);
                $Water=Property::MonthlyWater(Property::decryptText($properties->id),$month);
                $Garbage=Property::MonthlyGarbage(Property::decryptText($properties->id),$month);
                $Lease=Property::MonthlyLease(Property::decryptText($properties->id),$month);
                $HseDeposit=Property::MonthlyHseDeposit(Property::decryptText($properties->id),$month);
                $KPLC=Property::MonthlyKPLC(Property::decryptText($properties->id),$month);
                $Waterbill=Property::MonthlyWaterbill(Property::decryptText($properties->id),$month);
                $Arrears=Property::MonthlyArrears(Property::decryptText($properties->id),$month);
                $Excess=Property::MonthlyExcess(Property::decryptText($properties->id),$month);
                $Equity=Property::MonthlyPaidEquity(Property::decryptText($properties->id),$month);
                $Cooperative=Property::MonthlyPaidCoop(Property::decryptText($properties->id),$month);
                $Others=Property::MonthlyPaidOthers(Property::decryptText($properties->id),$month);
                $PaidUploaded=Property::MonthlyPaidUploaded(Property::decryptText($properties->id),$month);
                
                $KCB=Property::MonthlyPaidKCB(Property::decryptText($properties->id),$month);
                $MPesa=Property::MonthlyPaidMPesa(Property::decryptText($properties->id),$month);
                $Cash=Property::MonthlyPaidCash(Property::decryptText($properties->id),$month);
                $Cheque=Property::MonthlyPaidCheque(Property::decryptText($properties->id),$month);
                $Penalty=Property::MonthlyPaidPenalty(Property::decryptText($properties->id),$month);

                $TotalUsed  =$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Penalty;
                $TotalPaid  =$Equity+$Cooperative+$Cash+$Cheque+$MPesa+$KCB+$Others+$PaidUploaded;
                
                

                $CarriedForward=($Arrears-$Excess);

                $Balance    =($TotalUsed-$TotalPaid)+$CarriedForward;

                $totalbillsmsghse = Property::getTotalBillsMsgHse(Property::decryptText($properties->id),$month);
                $totalbillsmsghseonce =Property::getTotalBillsMsgSentOnceHse(Property::decryptText($properties->id),$month);
                $totalbillsmsghsetwice =Property::getTotalBillsMsgSentTwiceHse(Property::decryptText($properties->id),$month);
                $totalbillsmsghsethrice =Property::getTotalBillsMsgSentThriceHse(Property::decryptText($properties->id),$month);
                

                $thismonthname=Property::dateToMonthNameMonth($month);

                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$propertycode);
                $sheet->setCellValue('C'.$count,$propertyname);
                $sheet->setCellValue('D'.$count,$Excess);
                $sheet->setCellValue('E'.$count,$Arrears);
                $sheet->setCellValue('F'.$count,$Rent);
                $sheet->setCellValue('G'.$count,$Garbage);
                $sheet->setCellValue('H'.$count,$Waterbill);
                $sheet->setCellValue('I'.$count,$HseDeposit);
                $sheet->setCellValue('J'.$count,$Water);
                $sheet->setCellValue('K'.$count,$KPLC);
                $sheet->setCellValue('L'.$count,$Lease);
                $sheet->setCellValue('M'.$count,$Penalty);
                $sheet->setCellValue('N'.$count,$TotalUsed);
                $sheet->setCellValue('O'.$count,$Cooperative);
                $sheet->setCellValue('P'.$count,$Equity);
                $sheet->setCellValue('Q'.$count,$KCB);
                $sheet->setCellValue('R'.$count,$MPesa);
                $sheet->setCellValue('S'.$count,$Cash);
                $sheet->setCellValue('T'.$count,$Cheque);
                $sheet->setCellValue('U'.$count,$Others);
                $sheet->setCellValue('V'.$count,$PaidUploaded);
                $sheet->setCellValue('W'.$count,$totalbillshse);
                $sheet->setCellValue('X'.$count,$TotalPaid);
                $sheet->setCellValue('Y'.$count,$Balance);

                $allRent=$allRent+$Rent;
                $allWater=$allWater+$Water;
                $allGarbage=$allGarbage+$Garbage;
                $allLease=$allLease+$Lease;
                $allHseDeposit=$allHseDeposit+$HseDeposit;
                $allKPLC=$allKPLC+$KPLC;
                $allWaterbill=$allWaterbill+$Waterbill;
                $allArrears=$allArrears+$Arrears;
                $allExcess=$allExcess+$Excess;
                $allEquity=$allEquity+$Equity;
                $allCooperative=$allCooperative+$Cooperative;
                $allKCB=$allKCB+$KCB;
                $allMPesa=$allMPesa+$MPesa;
                $allCash=$allCash+$Cash;
                $allCheque=$allCheque+$Cheque;
                $allOthers=$allOthers+$Others;
                $allPaidUploaded=$allPaidUploaded+$PaidUploaded;
                $allPenalty=$allPenalty+$Penalty;
                $alltotalbillshse=$alltotalbillshse+$totalbillshse;

                $sheet->getStyle('A'.$count.':Y'.$count)->applyFromArray($smallstyleArray);
                $sheet->getStyle('D'.$count.':Y'.$count)->applyFromArray($smallnumbersstyleArray);


                $count++;
                $sno++;
            }

            $sheet->setCellValue('D'.$count,$allExcess);
            $sheet->setCellValue('E'.$count,$allArrears);
            $sheet->setCellValue('F'.$count,$allRent);
            $sheet->setCellValue('G'.$count,$allGarbage);
            $sheet->setCellValue('H'.$count,$allWaterbill);
            $sheet->setCellValue('I'.$count,$allHseDeposit);
            $sheet->setCellValue('J'.$count,$allWater);
            $sheet->setCellValue('K'.$count,$allKPLC);
            $sheet->setCellValue('L'.$count,$allLease);
            $sheet->setCellValue('M'.$count,$allPenalty);
            $sheet->setCellValue('N'.$count,$allTotalUsed);
            $sheet->setCellValue('O'.$count,$allCooperative);
            $sheet->setCellValue('P'.$count,$allEquity);
            $sheet->setCellValue('Q'.$count,$allKCB);
            $sheet->setCellValue('R'.$count,$allMPesa);
            $sheet->setCellValue('S'.$count,$allCash);
            $sheet->setCellValue('T'.$count,$allCheque);
            $sheet->setCellValue('U'.$count,$allOthers);
            $sheet->setCellValue('V'.$count,$allPaidUploaded);
            $sheet->setCellValue('W'.$count,$alltotalbillshse);
            $sheet->setCellValue('X'.$count,$allTotalPaid);
            $sheet->setCellValue('Y'.$count,$allBalance);
            
            $sheet->getStyle('D'.$count.':Y'.$count)->applyFromArray($smallnumbersstyleArray);
            $sheet->getStyle('D'.$count.':Y'.$count)->applyFromArray($titlestyleArray);

            $sheet->mergeCells('A1:Y1');
            $sheet->mergeCells('A2:Y2');
            $sheet->setTitle('Payments for '.$watermonthdate);
                

            // $sheetno++;

            $writer = new Xlsx($file);

            // Create a streamed response for download
            $response = new StreamedResponse(
                function () use ($writer, $file) {
                    $writer->save('php://output');
                }
            );

            $logMessage="All Properties Payments Summary for the Month of ".$watermonthdate." Has been Generated and Downloaded.";
            Property::setUserLogs($logMessage);

            // Set headers for file download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        }
        else{
            
            $watermonthdate =Property::getMonthDateFull($month);
            $propertyname= Property::getPropertyName(Property::decryptText($id));
            $propertycode= Property::getPropertyCode(Property::decryptText($id));
            

            $file->createSheet();
            $sheet=$file->getSheet($sheetno);


            $sheet->getStyle('A1:Y3')->applyFromArray($styleArray);
            $sheet->getStyle('A4:Y4')->applyFromArray($titlestyleArray);

            $sheet->setCellValue('A1',$propertyname.' ('.$propertycode.')');
            $sheet->setCellValue('A2',$propertyname.' Payments for '.$watermonthdate);

            
            $sheet->getStyle('A1:Y1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:Y2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('S')->setAutoSize(true);

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()->setTop(1);
            $sheet->getPageMargins()->setRight(0.75);
            $sheet->getPageMargins()->setLeft(0.75);
            $sheet->getPageMargins()->setBottom(1);
            
            $sheet->setCellValue('A4','No');
            $sheet->setCellValue('B4','Hse/No');
            $sheet->setCellValue('C4','Tenant Name');
            $sheet->setCellValue('D4','Excess');
            $sheet->setCellValue('E4','Arrears');
            $sheet->setCellValue('F4','Rent');
            $sheet->setCellValue('G4','Bin');
            $sheet->setCellValue('H4','Waterbill');
            $sheet->setCellValue('I4','Rent D');
            $sheet->setCellValue('J4','Water D');
            $sheet->setCellValue('K4','KPLC D');
            $sheet->setCellValue('L4','Lease');
            $sheet->setCellValue('M4','Penalty');
            $sheet->setCellValue('N4','Totals');
            $sheet->setCellValue('O4','Coop');
            $sheet->setCellValue('P4','Equity');
            $sheet->setCellValue('Q4','KCB');
            $sheet->setCellValue('R4','MPesa');
            $sheet->setCellValue('S4','Cash');
            $sheet->setCellValue('T4','Cheque');
            $sheet->setCellValue('U4','Others');
            $sheet->setCellValue('V4','Uploaded');
            $sheet->setCellValue('W4','Dates');
            $sheet->setCellValue('X4','Paid');
            $sheet->setCellValue('Y4','Bal');

            $count=5;
            $sno=1;

            $houseinfo=House::where('Plot',Property::decryptText($id))->get(['id','Plot','Housename','Rent']);
            

            $allRent=0;$allWater=0;$allGarbage=0;$allLease=0;$allHseDeposit=0;$allKPLC=0;$allWaterbill=0;$allArrears=0;
            $allKCB=0;
            $allMPesa=0;
            $allCash=0;
            $allCheque=0;
            $allExcess=0;$allEquity=0;$allCooperative=0;$allOthers=0;$allPaidUploaded=0;$allPenalty=0;

            foreach($houseinfo as $result){
                $hid= $result['id'];
                $house=$result['Housename'];
                $house=explode('-', $house);
                $countname=count($house);
                if($countname==1){
                    $housename=$house[0];
                }
                else{
                    $housename=$house[1];
                }

                // $housename= $result['Housename'];
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                if ($tid!="") {
                    $TenantNames=Property::TenantNames(Property::decryptText($tid));
                }else{
                    $TenantNames=($rent==0)?'Caretaker':'Vacant';
                    $tid="Vacant";
                }
                $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                $paymentdates='';
                if($allpayments=Payment::where('Plot',Property::decryptText($id))->where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$month)->get()->first()){
                    $Rent=$allpayments->Rent;$Water=$allpayments->Water;$Garbage=$allpayments->Garbage;
                    $Lease=$allpayments->Lease;$HseDeposit=$allpayments->HseDeposit;$KPLC=$allpayments->KPLC;
                    $Waterbill=$allpayments->Waterbill;$Arrears=$allpayments->Arrears;
                    $Excess=$allpayments->Excess;$Equity=$allpayments->Equity;$Cooperative=$allpayments->Cooperative;
                    $KCB=$allpayments->KCB;$MPesa=$allpayments->MPesa;$Cheque=$allpayments->Cheque;$Cash=$allpayments->Cash;
                    $Others=$allpayments->Others;$PaidUploaded=$allpayments->PaidUploaded;$Penalty=$allpayments->Penalty;

                    $allRent=$allRent+$Rent;
                    $allWater=$allWater+$Water;
                    $allGarbage=$allGarbage+$Garbage;
                    $allLease=$allLease+$Lease;
                    $allHseDeposit=$allHseDeposit+$HseDeposit;
                    $allKPLC=$allKPLC+$KPLC;
                    $allWaterbill=$allWaterbill+$Waterbill;
                    $allArrears=$allArrears+$Arrears;
                    $allExcess=$allExcess+$Excess;
                    $allEquity=$allEquity+$Equity;
                    $allCooperative=$allCooperative+$Cooperative;
                    $allKCB=$allKCB+$KCB;
                    $allMPesa=$allMPesa+$MPesa;
                    $allCash=$allCash+$Cash;
                    $allCheque=$allCheque+$Cheque;
                    $allOthers=$allOthers+$Others;
                    $allPaidUploaded=$allPaidUploaded+$PaidUploaded;
                    $allPenalty=$allPenalty+$Penalty;
                    // $alltotalbillshse=$alltotalbillshse+$totalbillshse;

                    $paymentid=$allpayments->id;
                    $thispayment=PaymentDate::where('PId',$paymentid)->get();
                    $i=0;
                    foreach($thispayment as $payment){
                        $i++;
                        $Amount=$payment->Amount;
                        $Date_Transacted=$payment->Date_Transacted;
                        $Through=$payment->Through;
                        $paymentdateshort =Property::getMonthPaymentDateShort($Date_Transacted);
                        if($i==1){
                            $paymentdates=$paymentdates.$Amount.'('.$paymentdateshort.')';
                        }
                        else{
                            $paymentdates=$paymentdates.', '.$Amount.'('.$paymentdateshort.')';
                        }
                        
                    }

                }

                $TotalUsed  =$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Penalty;
                $TotalPaid  =$Equity+$Cooperative+$Cash+$Cheque+$MPesa+$KCB+$Others+$PaidUploaded;
                
                

                $CarriedForward=($Arrears-$Excess);

                $Balance    =($TotalUsed-$TotalPaid)+$CarriedForward;
                
                $TenantNames=strtoupper($TenantNames);

                
                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$housename);
                $sheet->setCellValue('C'.$count,$TenantNames);
                $sheet->setCellValue('D'.$count,$Excess);
                $sheet->setCellValue('E'.$count,$Arrears);
                $sheet->setCellValue('F'.$count,$Rent);
                $sheet->setCellValue('G'.$count,$Garbage);
                $sheet->setCellValue('H'.$count,$Waterbill);
                $sheet->setCellValue('I'.$count,$HseDeposit);
                $sheet->setCellValue('J'.$count,$Water);
                $sheet->setCellValue('K'.$count,$KPLC);
                $sheet->setCellValue('L'.$count,$Lease);
                $sheet->setCellValue('M'.$count,$Penalty);
                $sheet->setCellValue('N'.$count,$TotalUsed);
                $sheet->setCellValue('O'.$count,$Cooperative);
                $sheet->setCellValue('P'.$count,$Equity);
                $sheet->setCellValue('Q'.$count,$KCB);
                $sheet->setCellValue('R'.$count,$MPesa);
                $sheet->setCellValue('S'.$count,$Cash);
                $sheet->setCellValue('T'.$count,$Cheque);
                $sheet->setCellValue('U'.$count,$Others);
                $sheet->setCellValue('V'.$count,$PaidUploaded);
                $sheet->setCellValue('W'.$count,$paymentdates);
                $sheet->setCellValue('X'.$count,$TotalPaid);
                $sheet->setCellValue('Y'.$count,$Balance);

                $sheet->getStyle('A'.$count.':Y'.$count)->applyFromArray($smallstyleArray);
                $sheet->getStyle('D'.$count.':R'.$count)->applyFromArray($smallnumbersstyleArray);
                
                $sheet->getStyle('T'.$count.':Y'.$count)->applyFromArray($smallnumbersstyleArray);

                // $sheet->getStyle('S'.$count)->getAlignment()->setWrapText(true);

                $count++;
                $sno++;
                // echo $sheetno.' '.$propertyname.' '.$housename.'<br/>';
            }

            $allTotalUsed=$allRent+$allWater+$allGarbage+$allLease+$allHseDeposit+$allKPLC+$allWaterbill+$allArrears+$allPenalty;
            $allTotalPaid=$allExcess+$allEquity+$allCooperative+$allOthers+$allPaidUploaded;
            
            $allKCB=$allKCB+$KCB;
            $allMPesa=$allMPesa+$MPesa;
            $allCash=$allCash+$Cash;
            $allCheque=$allCheque+$Cheque;
            $allPenalty=$allPenalty+$Penalty;
            
            $allBalance=$allTotalUsed-$allTotalPaid;
            
            
            $sheet->setCellValue('D'.$count,$allExcess);
            $sheet->setCellValue('E'.$count,$allArrears);
            $sheet->setCellValue('F'.$count,$allRent);
            $sheet->setCellValue('G'.$count,$allGarbage);
            $sheet->setCellValue('H'.$count,$allWaterbill);
            $sheet->setCellValue('I'.$count,$allHseDeposit);
            $sheet->setCellValue('J'.$count,$allWater);
            $sheet->setCellValue('K'.$count,$allKPLC);
            $sheet->setCellValue('L'.$count,$allLease);
            $sheet->setCellValue('M'.$count,$allPenalty);
            $sheet->setCellValue('N'.$count,$allTotalUsed);
            $sheet->setCellValue('O'.$count,$allCooperative);
            $sheet->setCellValue('P'.$count,$allEquity);
            $sheet->setCellValue('Q'.$count,$allKCB);
            $sheet->setCellValue('R'.$count,$allMPesa);
            $sheet->setCellValue('S'.$count,$allCash);
            $sheet->setCellValue('T'.$count,$allCheque);
            $sheet->setCellValue('U'.$count,$allOthers);
            $sheet->setCellValue('V'.$count,$allPaidUploaded);
            $sheet->setCellValue('W'.$count,'');
            $sheet->setCellValue('X'.$count,$allTotalPaid);
            $sheet->setCellValue('Y'.$count,$allBalance);

            
            // $sheet->getStyle('D'.$count.':U'.$count)->applyFromArray($styleArray);
            $sheet->getStyle('D'.$count.':Y'.$count)->applyFromArray($titlestyleArray);
            

            $sheet->mergeCells('A1:Y1');
            $sheet->mergeCells('A2:Y2');
            $sheet->setTitle($propertycode);

            // $sheetno++;

            $writer = new Xlsx($file);

            // Create a streamed response for download
            $response = new StreamedResponse(
                function () use ($writer, $file) {
                    $writer->save('php://output');
                }
            );

            $logMessage=$propertyname." Payments for the Month of ".$watermonthdate." Has been Generated and Downloaded.";
            Property::setUserLogs($logMessage);
    
            // Set headers for file download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
    
            return $response;
            
            
        }

    }

    public function downloadPaymentPerYearExcel($id,$year,$month)
    {
        $file=new Spreadsheet();
        $sheetno=0;

        $styleArray=[
            'borders'=>[
                'outline'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $titlestyleArray=[
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        $smallstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];

        $smallnumbersstyleArray=[
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders'=>[
                'allBorders'=>[
                    'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'=>['argb'=>'0000FF'],
                ],
            ],
        ];
        
        //start of per year
        
        $startyear=date('Y');
        $startmonth=1;
        $endmonth=date('n');
        $propertyname= Property::getPropertyName(Property::decryptText($id));
        $propertycode= Property::getPropertyCode(Property::decryptText($id));


        if($month==$startyear){
            $startyear=date('Y');
            $startmonth=1;
            $endmonth=date('n');
        }
        else{
            $startyear=$month;
            $startmonth=1;
            $endmonth=12;
        }

        for ($m=$startmonth; $m <=$endmonth ; $m++) { 
            $thismonth= $startyear.' '.$m;
            $monthname=Property::getMonthDateAddWater($thismonth);
            $watermonthdate =Property::getMonthDateFull($thismonth);

            $file->createSheet();
            $sheet=$file->getSheet($sheetno);


            $sheet->getStyle('A1:Y3')->applyFromArray($styleArray);
            $sheet->getStyle('A4:Y4')->applyFromArray($titlestyleArray);

            $sheet->setCellValue('A1',$propertyname.' ('.$propertycode.')');
            $sheet->setCellValue('A2',$propertyname.' Payments for '.$watermonthdate);

            
            $sheet->getStyle('A1:Y1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:Y2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('S')->setAutoSize(true);

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()->setTop(1);
            $sheet->getPageMargins()->setRight(0.75);
            $sheet->getPageMargins()->setLeft(0.75);
            $sheet->getPageMargins()->setBottom(1);
            
            $sheet->setCellValue('A4','No');
            $sheet->setCellValue('B4','Hse/No');
            $sheet->setCellValue('C4','Tenant Name');
            $sheet->setCellValue('D4','Excess');
            $sheet->setCellValue('E4','Arrears');
            $sheet->setCellValue('F4','Rent');
            $sheet->setCellValue('G4','Bin');
            $sheet->setCellValue('H4','Waterbill');
            $sheet->setCellValue('I4','Rent D');
            $sheet->setCellValue('J4','Water D');
            $sheet->setCellValue('K4','KPLC D');
            $sheet->setCellValue('L4','Lease');
            $sheet->setCellValue('M4','Penalty');
            $sheet->setCellValue('N4','Totals');
            $sheet->setCellValue('O4','Coop');
            $sheet->setCellValue('P4','Equity');
            $sheet->setCellValue('Q4','KCB');
            $sheet->setCellValue('R4','MPesa');
            $sheet->setCellValue('S4','Cash');
            $sheet->setCellValue('T4','Cheque');
            $sheet->setCellValue('U4','Others');
            $sheet->setCellValue('V4','Uploaded');
            $sheet->setCellValue('W4','Dates');
            $sheet->setCellValue('X4','Paid');
            $sheet->setCellValue('Y4','Bal');

            $count=5;
            $sno=1;

            $houseinfo=House::where('Plot',Property::decryptText($id))->get(['id','Plot','Housename','Rent']);
            

            $allRent=0;
            $allWater=0;
            $allGarbage=0;
            $allLease=0;
            $allHseDeposit=0;
            $allKPLC=0;
            $allWaterbill=0;
            $allArrears=0;
            $allExcess=0;
            $allEquity=0;
            $allCooperative=0;
            $allKCB=0;
            $allMPesa=0;
            $allCash=0;
            $allCheque=0;
            $allPenality=0;
            $allOthers=0;
            $allPaidUploaded=0;
            $allPenalty=0;
            $alltotalbillshse=0;
            $allTotalUsed  =0;
            $allTotalPaid  =0;
            $allBalance  =0;

            foreach($houseinfo as $result){
                $hid= $result['id'];
                $house=$result['Housename'];
                $house=explode('-', $house);
                $countname=count($house);
                if($countname==1){
                    $housename=$house[0];
                }
                else{
                    $housename=$house[1];
                }
                $rent= $result['Rent'];
                $tid=Property::checkCurrentTenant(Property::decryptText($hid));
                if ($tid!="") {
                    $TenantNames=Property::TenantNames(Property::decryptText($tid));
                }else{
                    $TenantNames=($rent==0)?'Caretaker':'Vacant';
                    $tid="Vacant";
                }
                $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                $KCB=0;$MPesa=0;$Cash=0;$Cheque=0;
                $paymentdates='';
                if($allpayments=Payment::where('Plot',Property::decryptText($id))->where('House',Property::decryptText($hid))->where('Tenant',Property::decryptText($tid))->where('Month',$thismonth)->get()->first()){
                    $Rent=$allpayments->Rent;$Water=$allpayments->Water;$Garbage=$allpayments->Garbage;
                    $Lease=$allpayments->Lease;$HseDeposit=$allpayments->HseDeposit;$KPLC=$allpayments->KPLC;
                    $Waterbill=$allpayments->Waterbill;$Arrears=$allpayments->Arrears;
                    $Excess=$allpayments->Excess;$Equity=$allpayments->Equity;$Cooperative=$allpayments->Cooperative;
                    $KCB=$allpayments->KCB;$MPesa=$allpayments->MPesa;$Cheque=$allpayments->Cheque;$Cash=$allpayments->Cash;
                    $Others=$allpayments->Others;$PaidUploaded=$allpayments->PaidUploaded;$Penalty=$allpayments->Penalty;

                    $allRent=$allRent+$Rent;
                    $allWater=$allWater+$Water;
                    $allGarbage=$allGarbage+$Garbage;
                    $allLease=$allLease+$Lease;
                    $allHseDeposit=$allHseDeposit+$HseDeposit;
                    $allKPLC=$allKPLC+$KPLC;
                    $allWaterbill=$allWaterbill+$Waterbill;
                    $allArrears=$allArrears+$Arrears;
                    $allExcess=$allExcess+$Excess;
                    $allEquity=$allEquity+$Equity;
                    $allCooperative=$allCooperative+$Cooperative;
                    $allOthers=$allOthers+$Others;
                    $allPaidUploaded=$allPaidUploaded+$PaidUploaded;
                    $allKCB=$allKCB+$KCB;
                    $allMPesa=$allMPesa+$MPesa;
                    $allCash=$allCash+$Cash;
                    $allCheque=$allCheque+$Cheque;
                    $allPenalty=$allPenalty+$Penalty;

                    $paymentid=$allpayments->id;
                    $thispayment=PaymentDate::where('PId',$paymentid)->get();
                    $i=0;
                    foreach($thispayment as $payment){
                        $i++;
                        $Amount=$payment->Amount;
                        $Date_Transacted=$payment->Date_Transacted;
                        $Through=$payment->Through;
                        $paymentdateshort =Property::getMonthPaymentDateShort($Date_Transacted);
                        if($i==1){
                            $paymentdates=$paymentdates.$Amount.'('.$paymentdateshort.')';
                        }
                        else{
                            $paymentdates=$paymentdates.', '.$Amount.'('.$paymentdateshort.')';
                        }
                    }
                }

                $TotalUsed  =$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Penalty;
                $TotalPaid  =$Equity+$Cooperative+$Cash+$Cheque+$MPesa+$KCB+$Others+$PaidUploaded;
                
                

                $CarriedForward=($Arrears-$Excess);

                $Balance    =($TotalUsed-$TotalPaid)+$CarriedForward;


                // $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears+$Penalty;
                // $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                // $Balance=$TotalUsed-$TotalPaid;
                
                $TenantNames=strtoupper($TenantNames);
                
                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$housename);
                $sheet->setCellValue('C'.$count,$TenantNames);
                $sheet->setCellValue('D'.$count,$Excess);
                $sheet->setCellValue('E'.$count,$Arrears);
                $sheet->setCellValue('F'.$count,$Rent);
                $sheet->setCellValue('G'.$count,$Garbage);
                $sheet->setCellValue('H'.$count,$Waterbill);
                $sheet->setCellValue('I'.$count,$HseDeposit);
                $sheet->setCellValue('J'.$count,$Water);
                $sheet->setCellValue('K'.$count,$KPLC);
                $sheet->setCellValue('L'.$count,$Lease);
                $sheet->setCellValue('M'.$count,$Penalty);
                $sheet->setCellValue('N'.$count,$TotalUsed);
                $sheet->setCellValue('O'.$count,$Cooperative);
                $sheet->setCellValue('P'.$count,$Equity);
                $sheet->setCellValue('Q'.$count,$KCB);
                $sheet->setCellValue('R'.$count,$MPesa);
                $sheet->setCellValue('S'.$count,$Cash);
                $sheet->setCellValue('T'.$count,$Cheque);
                $sheet->setCellValue('U'.$count,$Others);
                $sheet->setCellValue('V'.$count,$PaidUploaded);
                $sheet->setCellValue('W'.$count,$paymentdates);
                $sheet->setCellValue('X'.$count,$TotalPaid);
                $sheet->setCellValue('Y'.$count,$Balance);

                $sheet->getStyle('A'.$count.':Y'.$count)->applyFromArray($smallstyleArray);
                $sheet->getStyle('D'.$count.':R'.$count)->applyFromArray($smallnumbersstyleArray);
                $sheet->getStyle('T'.$count.':Y'.$count)->applyFromArray($smallnumbersstyleArray);

                $count++;
                $sno++;
            }

            $allTotalUsed=$allRent+$allWater+$allGarbage+$allLease+$allHseDeposit+$allKPLC+$allWaterbill+$allArrears+$allPenalty;
            $allTotalPaid=$allExcess+$allEquity+$allCooperative+$allOthers+$allPaidUploaded;
            $allBalance=$allTotalUsed-$allTotalPaid;
            
            $sheet->setCellValue('D'.$count,$allExcess);
            $sheet->setCellValue('E'.$count,$allArrears);
            $sheet->setCellValue('F'.$count,$allRent);
            $sheet->setCellValue('G'.$count,$allGarbage);
            $sheet->setCellValue('H'.$count,$allWaterbill);
            $sheet->setCellValue('I'.$count,$allHseDeposit);
            $sheet->setCellValue('J'.$count,$allWater);
            $sheet->setCellValue('K'.$count,$allKPLC);
            $sheet->setCellValue('L'.$count,$allLease);
            $sheet->setCellValue('M'.$count,$allPenalty);
            $sheet->setCellValue('N'.$count,$allTotalUsed);
            $sheet->setCellValue('O'.$count,$allCooperative);
            $sheet->setCellValue('P'.$count,$allEquity);
            $sheet->setCellValue('Q'.$count,$allKCB);
            $sheet->setCellValue('R'.$count,$allMPesa);
            $sheet->setCellValue('S'.$count,$allCash);
            $sheet->setCellValue('T'.$count,$allCheque);
            $sheet->setCellValue('U'.$count,$allOthers);
            $sheet->setCellValue('V'.$count,$allPaidUploaded);
            $sheet->setCellValue('W'.$count,'');
            $sheet->setCellValue('X'.$count,$allTotalPaid);
            $sheet->setCellValue('Y'.$count,$allBalance);

            $sheet->getStyle('D'.$count.':Y'.$count)->applyFromArray($titlestyleArray);
            
            $sheet->mergeCells('A1:Y1');
            $sheet->mergeCells('A2:Y2');
            $sheet->setTitle($propertycode.'('.$monthname.')');

            $sheetno++;
            
        }


        $writer = new Xlsx($file);

        // Create a streamed response for download
        $response = new StreamedResponse(
            function () use ($writer, $file) {
                $writer->save('php://output');
            }
        );

        $logMessage=$propertyname." Payments for the Year ".$startyear." Has been Generated and Downloaded.";
        Property::setUserLogs($logMessage);

        // Set headers for file download
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="data.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

    }

    public function getSiteData(Request $request){
        try{
        $agencydetail = Agency::first();
        // $sitedetails= array();
        // $sno=0;
        // $sno1=0;
        // foreach ($agencydetail as $property) { 
        //     $sitedetails[] = array(
        //         'sno'=>$sno,
                // 'id' => $property->id,
                // 'Names' => $property->Names,
                // 'Address' => $property->Address,
                // 'Town' => $property->Town,
                // 'Phone' => $property->Phone,
                // 'Email' => $property->Email,
                // 'islive' => $property->islive,
        //         'isuserslocked'=>$property->isuserslocked,
        //     );
        //     $sno++;
        // }

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
            'agency'=>$agency,
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
                'message'=>'Error'.$error,
            ]);
        }
    }


    public function getSiteMsgData(Request $request){
        try{
            $agencydetail = Agency::first();

            $userrole=Auth::user()->Userrole;
            
            if($userrole!='Dev'){
                $error="You do not have rights to Update Api Keys.";
                return response()->json([
                    'status'=>500,
                    'message'=>$error,
                ]);
            }

            $msg=[
                'id' => $agencydetail->id,
                'Names' => $agencydetail->Names,
                'islive' => $agencydetail->islive,
                'apiKey' => $agencydetail->apikey,
                'username' => $agencydetail->username,
                'sendfrom' => $agencydetail->sendfrom,
                'sandapiKey' => Property::decryptText($agencydetail->sandapikey),
                'sandusername' => Property::decryptText($agencydetail->sandusername),
                'sandsendfrom' => Property::decryptText($agencydetail->sandsendfrom),
            ];

            return response()->json([
                'status'=>200,
                'msg'=>$msg,
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
                'message'=>'Error'.$error,
            ]);
        }
    }


    public function getSiteMsgDataEdit(Request $request){
        try{
            $agencydetail = Agency::first();

            $msg=[
                'id' => $agencydetail->id,
                'Names' => $agencydetail->Names,
                'islive' => $agencydetail->islive,
                'apiKey' => Property::decryptText($agencydetail->apikey),
                'username' => Property::decryptText($agencydetail->username),
                'sendfrom' => Property::decryptText($agencydetail->sendfrom),
                'sandapiKey' => Property::decryptText($agencydetail->sandapikey),
                'sandusername' => Property::decryptText($agencydetail->sandusername),
                'sandsendfrom' => Property::decryptText($agencydetail->sandsendfrom),
            ];

            return response()->json([
                'status'=>200,
                'msg'=>$msg,
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
                'message'=>'Error'.$error,
            ]);
        }
    }
    

    public function saveAgency(Request $request){
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'EmailAddress' => ['required', 'string', 'max:255'],
            'Names' => ['required', 'string', 'max:150'],
            'Address' => ['required', 'string', 'max:150'],   
            'Town' => ['required', 'string', 'max:150'],   
            'islive' => ['required', 'string', 'max:150'],   
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
                    $error="No Agency Selected \n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
                else{
                    $newuser = Agency::findOrFail($request->input('id'));
                    $newuser->Email =$request->input('EmailAddress');
                    $newuser->Names =$request->input('Names');
                    $newuser->Town =$request->input('Town');
                    $newuser->Phone =$request->input('Phone');
                    $newuser->Address =$request->input('Address');
                    $newuser->islive =$request->input('islive');

                    if($newuser->save()){
                        \DB::commit();
                        $success="Agency has been updated successfully!.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not update a Agency \n";
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
                    $error="Agency With Email Already exists.\n";
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

    public function saveAgencyMsg(Request $request){
        \DB::beginTransaction();    
        $validator=Validator::make($request->all(),[
            'apiKey' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'sendfrom' => ['required', 'string', 'max:255'],   
            'sandapiKey' => ['required', 'string', 'max:255'],
            'sandusername' => ['required', 'string', 'max:255'],
            'sandsendfrom' => ['required', 'string', 'max:255'],   
        ]);


        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            try { 
                $userrole=Auth::user()->Userrole;
                if($userrole!='Dev'){
                    $error="You do not have rights to Update Api Keys.";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
                if($request->input('id') ==''){
                    $error="No Agency Selected \n";
                    return response()->json([
                        'status'=>500,
                        'message'=>$error,
                    ]);
                }
                else{
                    $newuser = Agency::findOrFail($request->input('id'));
                    $newuser->apikey =Property::encryptText($request->input('apiKey'));
                    $newuser->username =Property::encryptText($request->input('username'));
                    $newuser->sendfrom =Property::encryptText($request->input('sendfrom'));
                    $newuser->sandapikey =Property::encryptText($request->input('sandapiKey'));
                    $newuser->sandusername =Property::encryptText($request->input('sandusername'));
                    $newuser->sandsendfrom =Property::encryptText($request->input('sandsendfrom'));

                    // return $newuser;

                    if($newuser->save()){
                        \DB::commit();
                        $success="Agency Messaging Info has been updated successfully!.\n";
                        return response()->json([
                            'status'=>200,
                            'message'=>$success,
                        ]);
                    }
                    else{
                        \DB::rollback();
                        $error="Could not update a Agency Messaging Info \n";
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
                    $error="Agency With Email Already exists.\n";
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
    

    public function jengaTEST(){

        $plainText  = "0011547896523KE2018-08-09";
        $privateKey = openssl_pkey_get_private(("file://path/to/privatekey.pem"));
        $token      = "QNg9X7cLJSpZVOpaJJ33wX0AbcRF";

        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);


        $curl        = curl_init();
        $data_string = '{
            "countryCode":"KE",
            "accountId":"0011547896523",
            "date":"2018-08-09"
            }';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.jengahq.io/account-test/v2/accounts/accountbalance/query",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature)
            )
        ));
        $result = curl_exec($curl);
        $err    = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $result;
        }
        
        // $curl = curl_init();

        // curl_setopt_array($curl, [
        // CURLOPT_URL => "https://sandbox.jengahq.io/authentication/api/v3/authenticate/mechant",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => "",
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 30,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => "merchantCode=1043552192&consumerSecret=f01K18z53RcHxzVq9y11IKnJJu0UbO",
        // CURLOPT_HTTPHEADER => [
        //     "Content-Type: application/json"
        // ],
        // ]);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        // echo "cURL Error #:" . $err;
        // } else {
        // echo $response;
        // }

        // $client = new Client();
        // 1043552192
        // f01K18z53RcHxzVq9y11IKnJJu0UbO
        // $response = $client->request('POST', 'https://uat.finserve.africa/authentication/api/v3/authenticate/mechant', [
        // 'headers' => [
        //     'Content-Type' => 'application/json',
        // ],
        // ]);

        

        // echo $response->getBody();

        // WyWyqDWaijac/0bnDrjAZ3HEitNeeo6z6SI/JLzZd1ROpVNKAd8byYusqQwR1wP9HzNkhgt7LRbS6E4BYEuWIA==
        
        // require_once('vendor/autoload.php');

        // $client = new \GuzzleHttp\Client();

        // $response = $client->request('GET', 'https://uat.finserve.africa/v3-apis/account-api/v3.0/accounts/balances/KE/0011547896523', [
        // 'headers' => [
        //     'Authorization' => 'sha512-as7homA2b50v7Ejqxg0d3eX3Q9fn4RGW81qLKz9I60FpBYCymwleoDxXqXW8cW17PRXkvSWM9YFbcKuXCY/NNA==?mAtL',
        //     'signature' => 'huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==',
        // ],
        // ]);

        // echo $response->getBody();
    }


    public function deliveryreports(Request $request){
        //store the received in Json File 
        $callback=file_get_contents('php://input');
        // deco the received json 
        $callbackurl=json_decode($callback,true);

        Property::setLogs($callbackurl);
    }

    public function Testing($id,$month){
        $waterbill=Property::MonthlyWaterbillTest($id,$month);
        return response()->json([
            'status'=>200,
            'totalwaterbill' =>$waterbill,
            'message'=>'Found Total Waterbill Stats',
        ]);
    }


    public function TestingSingle($type,$text){

        // $thisproperty=Property::findOrFail(Property::decryptText($text));
    
        // $thispropert= array();

        // $thispropert[] = array(
        //     'id' => $text,
        //     'Plotcode' => $thisproperty->Plotcode,
        //     'Plotname' => $thisproperty->Plotname,
        //     'Plotarea' => $thisproperty->Plotarea,
        //     'Plotaddr' => $thisproperty->Plotaddr,
        //     'Plotdesc' => $thisproperty->Plotdesc,
        //     'Waterbill' => $thisproperty->Waterbill,
        //     'Deposit' => $thisproperty->Deposit,
        //     'Waterdeposit' => $thisproperty->Waterdeposit,
        //     'Outsourced' => $thisproperty->Outsourced,
        //     'Garbage' => $thisproperty->Garbage,
        //     'Kplcdeposit' => $thisproperty->Kplcdeposit,
        //     'created_at' => $thisproperty->created_at,
        //     'updated_at' => $thisproperty->updated_at
        // );

        // return $thispropert;

        // return $thisproperty['id'];

        // $tenantsiss = Tenant::orderByDesc('id')->get();
        
        // foreach($tenantsiss as $thistenant){
        //     $tenantsi[] = array(
        //         'id'=> Property::encryptText($thistenant->id),
        //         'Fname' => $thistenant->Fname,
        //         'Oname' => $thistenant->Oname,
        //         'Gender' => $thistenant->Gender,
        //         'IDno' => $thistenant->IDno,
        //         'HudumaNo' => $thistenant->HudumaNo,
        //         'Phone' => $thistenant->Phone,
        //         'Email' => $thistenant->Email,
        //         'Status' => $thistenant->Status,
        //         'created_at' => $thistenant->created_at,
        //         'updated_at' => $thistenant->updated_at
        //     );
        //  }
        //  return response()->json([
        //     'status'=>200,
        //     'new' =>$tenantsi,
        // ]);

        return Property::all();

        // return House::findOrFail(1214);
        // $newdata='';
        // if($type=='de'){
        //     $newdata=Property::decryptText($text);
        // }
        // else{
        //     $newdata=Property::encryptText($text);
        // }
        // return response()->json([
        //     'status'=>200,
        //     'new' =>$newdata,
        // ]);
    }

    
    
    
    
}
