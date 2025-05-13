<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\House;
use App\Models\Agreement;
use App\Models\Tenant;
use App\Models\Water;
use App\Models\Payment;
use App\Models\Report;
use App\Models\PaymentDate;
use App\Models\Blacklisted;
use App\Http\Controllers\TenantController;
use PDF;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function __construct()
    // {
    //     $this->middleware(['auth','verified']);
    // }

    public function index()
    {
        $propertycount = Property::count();
        $housescount = House::count();
        $tenantscount = Tenant::count();
        $propertyinfo = Property::all();
        $housesinfo = House::all();
        $tenantsinfo = Tenant::all();
        return view('plots',compact('propertyinfo','propertycount','tenantscount','housescount','tenantsinfo','housesinfo'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $propertyinfo = Property::all();
        return view('newproperty',compact('propertyinfo'));
    }

    public function plotsmgr()
    {
        $propertycount = Property::count();
        $housescount = House::count();
        $tenantscount = Tenant::count();
        $propertyinfo = Property::all();
        $housesinfo = House::all();
        $tenantsinfo = Tenant::all();
        return view('plotsmgr',compact('propertyinfo','propertycount','tenantscount','housescount','tenantsinfo','housesinfo'));
    }

    public function tenantsmgr()
    {
        $propertycount = Property::count();
        $housescount = House::count();
        $tenantscount = Tenant::count();
        $propertyinfo = Property::all();
        $housesinfo = House::all();
        $tenantsinfo = Tenant::all();
        return view('tenantsmgr',compact('propertyinfo','propertycount','tenantscount','housescount','tenantsinfo','housesinfo'));
    }

    public function plotsmgrinfo(Request $request)
    {
        $propertycount = Property::count();
        $propertyinfo = Property::all();
        return compact('propertyinfo','propertycount');
    }

    public function plotsmgrhouses($id)
    {
        if($id=='vacant'){
            $housesinfos = House::orderByDesc('id')->where('Status','Vacant')->get();
            $housescount = House::where('Status','Vacant')->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant($houseid);
                $plotcode=Property::getPropertyCode($house->Plot);
                $plotname=Property::getPropertyName($house->Plot);
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
                    'Plot'=>        $house->Plot,
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
            return compact('housesinfo','housescount');
        }
        else{
            $housesinfos = House::where('Plot',$id)->get();
            $housescount = House::where('Plot',$id)->count();
            $payments= array();
            foreach ($housesinfos as $house) {
                $houseid=$house->id;
                $tenant=Property::checkCurrentTenant($houseid);
                $plotcode=Property::getPropertyCode($house->Plot);
                $plotname=Property::getPropertyName($house->Plot);
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
                    'Plot'=>        $house->Plot,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      $tenant,
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>      $plotcode,
                    'plotname'=>      $plotname,
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
            return compact('housesinfo','housescount');
        }
    }

    
    public function plotsmgrhousestenant($id)
    {
        $houseshere= Agreement::where('Tenant',$id)->where('Month',0)->get();
        $housescount=0;
        $payments= array();
        foreach ($houseshere as $houses) {
            $houseid=$houses->House;
            $tenant=$id;
            if($house=House::where('id',$houseid)->where('status','Occupied')->get()->first()){
                $housescount++;
                $plotcode=Property::getPropertyCode($houses->Plot);
                $plotname=Property::getPropertyName($houses->Plot);
                $tenantname=Property::checkCurrentTenantFName($tenant);
                $payments[] = array(
                    'id' =>         $houses->House,
                    'Plot'=>        $houses->Plot,
                    'Housename'=>   $house->Housename,
                    'tenant'=>      $tenant,
                    'tenantname'=>  ucwords(strtolower($tenantname)),
                    'plotcode'=>      $plotcode,
                    'plotname'=>      $plotname,
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
        }
        $housesinfo=$payments;
        return compact('housesinfo','housescount');
    }

    public function plotsmgrhousesvacatetenant($id)
    {
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
                    'id' =>         $agreement->House,
                    'Plot'=>        $agreement->Plot,
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
        $housesinfo=$payments;
        return compact('housesinfo','housescount');
    }
    

    public function plotsmgrtenants($status)
    {
        $tenantsdata= array();
        if($status=='New' || $status=='Vacated'){
            $alltenantinfo = Tenant::orderByDesc('id')->where('Status',$status)->get();
            $alltenantscount = Tenant::where('Status',$status)->get()->count();
            $alltenantscountvacated = 0;
            $alltenantsvacatedcount = 0;
            foreach ($alltenantinfo as $tenants) {
                $tenantid=$tenants->id;
                $totalhouses=TenantController::tenantHousesAssigned($tenantid);
                $houses=TenantController::tenantHousesMgr($tenantid);
                $tenantname=ucwords(strtolower(Property::checkCurrentTenantName($tenantid)));
                if($status=='Vacated'){
                    $totalhousesvacated=TenantController::tenantHousesAssignedVacated($tenantid);
                    if($totalhousesvacated>0){
                        $alltenantsvacatedcount++;
                        $tenantsdata[] = array(
                            'id' =>         $tenants->id,
                            'Fname'=>       ucwords(strtolower($tenants->Fname)),
                            'Oname'=>       ucwords(strtolower($tenants->Oname)),
                            'tenantname' => $tenantname,
                            'Gender'=>      $tenants->Gender,
                            'IDno'=>        $tenants->IDno,
                            'HudumaNo'=>    $tenants->HudumaNo,
                            'Phone' =>      $tenants->Phone,
                            'Email' =>      ($tenants->Email)==null?"":$tenants->Email,
                            'statuss'=>     $status,
                            'statusvalue'=> $status,
                            'Status' =>     $tenants->Status,
                            'totalhouses'=> $totalhousesvacated,
                            'houses' =>     $houses,
                            'created_at' => $tenants->created_at,
                        );
                    }
                    
                }
                else{
                    $tenantsdata[] = array(
                        'id' =>         $tenants->id,
                        'Fname'=>       ucwords(strtolower($tenants->Fname)),
                        'Oname'=>       ucwords(strtolower($tenants->Oname)),
                        'tenantname' => $tenantname,
                        'Gender'=>      $tenants->Gender,
                        'IDno'=>        $tenants->IDno,
                        'HudumaNo'=>    $tenants->HudumaNo,
                        'Phone' =>      $tenants->Phone,
                        'Email' =>      ($tenants->Email)==null?"":$tenants->Email,
                        'statuss'=>     $status,
                        'statusvalue'=> $status,
                        'Status' =>     $tenants->Status,
                        'totalhouses'=> $totalhouses,
                        'houses' =>     $houses,
                        'created_at' => $tenants->created_at,
                    );
                }
                if($status=='Vacated'){
                    $alltenantscountvacated=$alltenantsvacatedcount;
                    $alltenantscount=$alltenantscountvacated;
                }
                
            }
        }
        else{
            // $allagreementinfo = Agreement::where('Plot',$status)->get();
            $allhousesinfo = House::where('Plot',$status)->get();
            $alltenantscount = 0;
            $alltenantsdouble = 0;
            $alltenantscountall = 0;
            foreach ($allhousesinfo as $housesinfo) {
                $houseid=$housesinfo->id;
                $statuss=$housesinfo->Plot;
                $statusvalue=Property::getPropertyName($status);
                $tenantid=Property::checkCurrentTenant($houseid);
                $totalhouses=TenantController::tenantHousesAssigned($tenantid);
                $houses=TenantController::tenantHousesMgr($tenantid);
                $tenantname=ucwords(strtolower(Property::checkCurrentTenantName($tenantid)));
                TenantController::TenantFNames($tenantid);
                TenantController::TenantONames($tenantid);
                TenantController::TenantIdno($tenantid);
                TenantController::TenantPhone($tenantid);
                TenantController::TenantStatus($tenantid);
                TenantController::TenantEmail($tenantid);
                TenantController::TenantGender($tenantid);
                TenantController::TenantHuduma($tenantid);
                $tenantstatus=TenantController::TenantStatus($tenantid);
                if($tenantid=='' || $tenantid==null){
                    $tenantname='No Tenant';
                    $houses=$housesinfo->Housename;
                    $tenantstatus='Vacant';
                }
                if($totalhouses >0 ){
                    $alltenantscountall++;
                    if($totalhouses >1 ){
                        $alltenantsdouble++;
                    }
                    $tenantsdata[] = array(
                        'id' =>         $tenantid,
                        'Fname'=>       ucwords(strtolower(TenantController::TenantFNames($tenantid))),
                        'Oname'=>       ucwords(strtolower(TenantController::TenantONames($tenantid))),
                        'tenantname' => $tenantname,
                        'Gender'=>      TenantController::TenantGender($tenantid),
                        'IDno'=>        TenantController::TenantIdno($tenantid),
                        'HudumaNo'=>    TenantController::TenantHuduma($tenantid),
                        'Phone' =>      TenantController::TenantPhone($tenantid),
                        'Email' =>      (TenantController::TenantEmail($tenantid))==null?"":TenantController::TenantEmail($tenantid),
                        'Status' =>     $tenantstatus,
                        'statuss'=> $statuss,
                        'statusvalue'=> $statusvalue,
                        'totalhouses'=> $totalhouses,
                        'houses' =>     $houses,
                        'created_at' => TenantController::TenantCreated($tenantid),
                    );
                }
            }
            $alltenantscount=$alltenantscountall - ($alltenantsdouble);
        }
        $alltenantsinfo=$tenantsdata;

        return compact('alltenantsinfo','alltenantscount');
    }


    public function newhouse($id)
    {
        $housesinfo=House::where('Plot',$id)->get();
        $propertyinfo = Property::all();
        $properties = Property::findOrFail($id);
        return view('newhouse',compact('propertyinfo','properties','housesinfo'));
    }


    public function newtenant()
    {
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        return view('newtenant',compact('propertyinfo','tenantsinfo'));
    }


    public static function getCurrentMonthReport(){
        $watermonth =date("Y n");
        return $watermonth;
    }

    

    public function viewdocuments()
    {
        $reports =Report::orderByDesc('id')->where('Type','Documents')->get();
        $propertyinfo = Property::all();
        return view('documents',compact('reports','propertyinfo'));
    }

    public function loaddocuments()
    {
        try { 
            $success="Files Loaded Succesfully";
            $reports =Report::orderByDesc('id')->where('Type','Documents')->get();
            return compact('success','reports');
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

    
    public function deletedocument($id)
    {
        $property = Report::findOrFail($id);
        $property->delete();
        return redirect('/properties/View/Documents')->with('success', 'File has been deleted');
    }

    public function viewreports()
    {
        
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $watermonth=$this->getCurrentMonthReport();
        $waterbill=Water::where('Month',$watermonth)->get();
        $payments='';
        $thisproperty='';
        $thistype='Waterbill';
        $monthinfo= array();
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $endmonth=12;
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
                $monthname=Property::getMonthDateAddWater($month);
                $monthinfo[] = array(
                        'Month' => $month,
                        'Monthname' => $monthname
              );
            }
        }
        return view('reports.view',compact('propertyinfo','tenantsinfo','waterbill','thisproperty','thistype','watermonth','monthinfo','payments'));
    }

        public function viewreportstype($type,$month)
    {
        
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $watermonth=$month;
        if ($type=="Waterbill") {
            $waterbill=Water::where('Month',$watermonth)->get();
        }
        else{
            $waterbill='';
        }

        if ($type=="Payments") {
            $payments='';
        }
        else{
            $payments='';
        }

        if ($type=="TenantsInfo") {
            $tenantsinfo=House::all(['id','Plot','Housename']);
        }
        else{
            $tenantsinfo='';
        }
        $thisproperty='';
        $thistype=$type;
        $monthinfo= array();
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $endmonth=12;
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
                $monthname=Property::getMonthDateAddWater($month);
                $monthinfo[] = array(
                        'Month' => $month,
                        'Monthname' => $monthname
              );
            }
        }
        return view('reports.view',compact('propertyinfo','tenantsinfo','waterbill','thisproperty','thistype','watermonth','monthinfo','payments'));
    }
    
    public function viewreportstypeproperty($type,$id,$month)
    {
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $watermonth=$month;
        $payments='';
        $waterbill='';
        if ($id=="All") {
            $thisproperty=''; 
            $waterbill=Water::where('Month',$watermonth)->get();
        }  
        else{
            if ($type=="Waterbill") {
                $waterbill=DB::table('waters')->where([
                    'Plot'=>$id,
                    'Month'=>$watermonth
                ])->get();
            }
            else{
                $waterbill='';
            }
            if ($type=="TenantsInfo") {
                $tenantsinfo=House::where('Plot',$id)->get(['id','Plot','Housename']);
            }
            else{
                $tenantsinfo='';
            }

            if ($type=="Payments") {
                // $payments=House::where('Plot',$id)->get(['id','Plot','Housename']);

                $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename']);

                $payments= array();
                foreach($houseinfo as $result){
                    $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                    $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                    $hid= $result['id'];
                    $housename= $result['Housename'];
                    $tid=Property::checkCurrentTenant($hid);
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames($tid);
                        $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
                        $allpayments=DB::table('payments')->where([
                                 'Tenant'=>$tid,
                                'House'=>$hid,
                                'Month'=>$watermonth])->get();
                        foreach($allpayments as $payment){
                            $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
                            $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
                            $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
                            $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
                            $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;
                            $Penalty=$payment->Penalty;
                        }
                    }else{
                        $TenantNames="";
                        $tenantphone="";
                        $tid="Vacant";
                    }

                    $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears+$Penalty;
                    $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                    $Balance=$TotalUsed-$TotalPaid;
                    $payments[] = array(
                            'pid' => $id,
                            'id' => $hid,
                            'tid'=>$tid,
                            'Tenantname'=>$TenantNames,
                            'Phone'=>$tenantphone,
                            'Housename'=>$housename,
                            'Rent' => $Rent,
                            'Garbage' => $Garbage,
                            'KPLC' => $KPLC,
                            'HseDeposit' => $HseDeposit,
                            'Water' => $Water,
                            'Lease' => $Lease,
                            'Month' => $watermonth,
                            'Waterbill' => $Waterbill,
                            'Equity' => $Equity,
                            'Cooperative' => $Cooperative,
                            'Others' => $Others,
                            'Excess' => $Excess,
                            'Arrears' => $Arrears,
                            'PaidUploaded' => $PaidUploaded,
                            'TotalUsed' => $TotalUsed,
                            'TotalPaid' => $TotalPaid,
                            'Penalty' => $Penalty,
                            'Balance' => $Balance,
                        );
                        
                }

            }
            else{
                $payments='';

            }
            $thisproperty=Property::findOrFail($id);
            
        }
        $thistype=$type;
        $monthinfo= array();
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $endmonth=12;
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
                $monthname=Property::getMonthDateAddWater($month);
                $monthinfo[] = array(
                        'Month' => $month,
                        'Monthname' => $monthname
              );
            }
        }

        // return $payments;
        return view('reports.view',compact('propertyinfo','tenantsinfo','waterbill','thisproperty','thistype','watermonth','monthinfo','payments'));
    }

    

    public function downloadwaterbillexcel($id,$month)
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

                $houseinfo=House::where('Plot',$propertyid)->get(['id','Plot','Housename','Rent']);
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
                    $tid=Property::checkCurrentTenant($hid);
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames($tid);
                    }else{
                        $TenantNames=($rent==0)?'Caretaker':'Vacant';
                        $tid="Vacant";
                    }
                    
                    if($waterbills=Water::where('House',$hid)->where('Month',$month)->get()->first()){
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
                        $tenantid=$waterbills->Tenant;
                        $TenantNames=Property::TenantNames($tenantid);
                    }
                    else{
                        if(date('Y n')==$month){
                            $monthdate= $this->getLastMonthdate($month);
                            $previousmonth= $this->getLastMonth($month,$monthdate);
                            
                            if($prevwaterbills=Water::where('House',$hid)->where('Month',$previousmonth)->get()->first()){
                                $previousunits=$prevwaterbills->Current;
                                $previousunits=($previousunits=='')?0:$previousunits;
                                $previousunitstotals = $previousunitstotals + $previousunits;
                                $tenantid=$prevwaterbills->Tenant;
                                $TenantNames=Property::TenantNames($tenantid);
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


            $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
            $filename= 'All Properties Water Bill for '.$watermonthdate .' .' .strtolower('xls');
            $writer->save($filename);

            header('Content-Type:application/x-www-form-urlencoded');
            header('Content-Transfer-Encoding:Binary');
            header("Content-disposition:attachment;filename=\"".$filename."\"");

            readfile($filename);

            unlink($filename);       

            exit;
        }  
        else{
            if($month=="Now" || $month=="Previous"){
                $startyear=date('Y');
                $startmonth=1;
                $endmonth=date('n');
                $propertyname= Property::getPropertyName($id);
                $propertycode= Property::getPropertyCode($id);

                if($month=="Now"){
                    $startyear=date('Y');
                    $startmonth=1;
                    $endmonth=date('n');
                }
                else{
                    $startyear=date('Y')-1;
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

                    $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Rent']);
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
                        $tid=Property::checkCurrentTenant($hid);
                        if ($tid!="") {
                            $TenantNames=Property::TenantNames($tid);
                        }else{
                            $TenantNames=($rent==0)?'Caretaker':'Vacant';
                            $tid="Vacant";
                        }
                        
                        if($waterbills=Water::where('House',$hid)->where('Month',$thismonth)->get()->first()){
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
                            $tenantid=$waterbills->Tenant;
                            $TenantNames=Property::TenantNames($tenantid);
                        }
                        else{
                            if(date('Y n')==$thismonth){
                                $monthdate= $this->getLastMonthdate($thismonth);
                                $previousmonth= $this->getLastMonth($thismonth,$monthdate);
                                
                                if($prevwaterbills=Water::where('House',$hid)->where('Month',$previousmonth)->get()->first()){
                                    $previousunits=$prevwaterbills->Current;
                                    $previousunits=($previousunits=='')?0:$previousunits;
                                    $previousunitstotals = $previousunitstotals + $previousunits;
                                    $tenantid=$prevwaterbills->Tenant;
                                    $TenantNames=Property::TenantNames($tenantid);
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

                $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
                $filename=$propertyname.' Water Bill for '.$startyear .' .' .strtolower('xls');
                $writer->save($filename);

                header('Content-Type:application/x-www-form-urlencoded');
                header('Content-Transfer-Encoding:Binary');
                header("Content-disposition:attachment;filename=\"".$filename."\"");

                readfile($filename);

                unlink($filename);   

                exit;   

            }//end of now or previous

            else{
                $watermonthdate =Property::getMonthDateFull($month);
                $propertyname= Property::getPropertyName($id);
                $propertycode= Property::getPropertyCode($id);
                
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

                $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Rent']);
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
                    $tid=Property::checkCurrentTenant($hid);
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames($tid);
                        

                    }else{
                        $TenantNames=($rent==0)?'Caretaker':'Vacant';
                        $tid="Vacant";
                    }
                    
                    if($waterbills=Water::where('House',$hid)->where('Month',$month)->get()->first()){
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
                        $tenantid=$waterbills->Tenant;
                        $TenantNames=Property::TenantNames($tenantid);
                    }
                    else{
                        if(date('Y n')==$month){
                            $monthdate= $this->getLastMonthdate($month);
                            $previousmonth= $this->getLastMonth($month,$monthdate);
                            
                            if($prevwaterbills=Water::where('House',$hid)->where('Month',$previousmonth)->get()->first()){
                                $previousunits=$prevwaterbills->Current;
                                $previousunits=($previousunits=='')?0:$previousunits;
                                $previousunitstotals = $previousunitstotals + $previousunits;
                                $tenantid=$prevwaterbills->Tenant;
                                $TenantNames=Property::TenantNames($tenantid);
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
                            $monthdate= $this->getLastMonthdate($month);
                            $previousmonth= $this->getLastMonth($month,$monthdate);

                            if($prevwaterbills=Water::where('House',$hid)->where('Month',$previousmonth)->get()->first()){
                                $previousunits=$prevwaterbills->Current;
                                $previousunits=($previousunits=='')?0:$previousunits;
                                $previousunitstotals = $previousunitstotals + $previousunits;
                                $tenantid=$prevwaterbills->Tenant;
                                $TenantNames=Property::TenantNames($tenantid);
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

                $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
                $filename=$propertyname.' Water Bill for '.$watermonthdate .' .' .strtolower('xls');
                $writer->save($filename);

                header('Content-Type:application/x-www-form-urlencoded');
                header('Content-Transfer-Encoding:Binary');
                header("Content-disposition:attachment;filename=\"".$filename."\"");

                readfile($filename);

                unlink($filename);       

                exit;


            }
            
        }

    }

    public function downloadpaymentsexcel($id,$month)
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
                $sheet->setCellValue('Q4','Others');
                $sheet->setCellValue('R4','Uploaded');
                $sheet->setCellValue('S4','Dates');
                $sheet->setCellValue('T4','Paid');
                $sheet->setCellValue('U4','Bal');

                $count=5;
                $sno=1;

                $houseinfo=House::where('Plot',$propertyid)->get(['id','Plot','Housename','Rent']);
                

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
                    $tid=Property::checkCurrentTenant($hid);
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames($tid);
                    }else{
                        $TenantNames=($rent==0)?'Caretaker':'Vacant';
                        $tid="Vacant";
                    }
                    $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                    $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                    $paymentdates='';
                    if($allpayments=Payment::where('Plot',$propertyid)->where('House',$hid)->where('Tenant',$tid)->where('Month',$month)->get()->first()){
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


            $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
            $filename= 'All Properties Payments for '.$watermonthdate .' .' .strtolower('xls');
            
            $path=public_path('reports');
            $writer->save($path.'\\'.$filename);

            $writer->save($filename);


            header('Content-Type:application/x-www-form-urlencoded');
            header('Content-Transfer-Encoding:Binary');
            header("Content-disposition:attachment;filename=\"".$filename."\"");

            readfile($filename);

            unlink($filename);       

            exit;
        }  
        else{
            if($month=="Now" || $month=="Previous"){
                $startyear=date('Y');
                $startmonth=1;
                $endmonth=date('n');
                $propertyname= Property::getPropertyName($id);
                $propertycode= Property::getPropertyCode($id);

                if($month=="Now"){
                    $startyear=date('Y');
                    $startmonth=1;
                    $endmonth=date('n');
                }
                else{
                    $startyear=date('Y')-1;
                    $startmonth=1;
                    $endmonth=12;
                }

                for ($m=$startmonth; $m <=$endmonth ; $m++) { 
                    $thismonth= $startyear.' '.$m;
                    $monthname=Property::getMonthDateAddWater($thismonth);
                    $watermonthdate =Property::getMonthDateFull($thismonth);

                    $file->createSheet();
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
                    $sheet->setCellValue('Q4','Others');
                    $sheet->setCellValue('R4','Uploaded');
                    $sheet->setCellValue('S4','Dates');
                    $sheet->setCellValue('T4','Paid');
                    $sheet->setCellValue('U4','Bal');

                    $count=5;
                    $sno=1;

                    $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Rent']);
                    

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
                        $rent= $result['Rent'];
                        $tid=Property::checkCurrentTenant($hid);
                        if ($tid!="") {
                            $TenantNames=Property::TenantNames($tid);
                        }else{
                            $TenantNames=($rent==0)?'Caretaker':'Vacant';
                            $tid="Vacant";
                        }
                        $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                        $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                        $paymentdates='';
                        if($allpayments=Payment::where('Plot',$id)->where('House',$hid)->where('Tenant',$tid)->where('Month',$thismonth)->get()->first()){
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
                    $sheet->setCellValue('Q'.$count,$allOthers);
                    $sheet->setCellValue('R'.$count,$allPaidUploaded);
                    $sheet->setCellValue('S'.$count,'');
                    $sheet->setCellValue('T'.$count,$allTotalPaid);
                    $sheet->setCellValue('U'.$count,$allBalance);

                    $sheet->getStyle('D'.$count.':U'.$count)->applyFromArray($titlestyleArray);
                    
                    $sheet->mergeCells('A1:U1');
                    $sheet->mergeCells('A2:U2');
                    $sheet->setTitle($propertycode.'('.$monthname.')');

                    $sheetno++;
                    
                }

                $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
                $filename=$propertyname.' Payments for '.$startyear .' .' .strtolower('xls');

                $path=public_path('reports');
                $writer->save($path.'\\'.$filename);

                $writer->save($filename);

                header('Content-Type:application/x-www-form-urlencoded');
                header('Content-Transfer-Encoding:Binary');
                header("Content-disposition:attachment;filename=\"".$filename."\"");

                readfile($filename);

                unlink($filename);   

                exit;   

            }//end of now or previous

            else{
                $watermonthdate =Property::getMonthDateFull($month);
                $propertyname= Property::getPropertyName($id);
                $propertycode= Property::getPropertyCode($id);
                

                $file->createSheet();
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
                $sheet->setCellValue('Q4','Others');
                $sheet->setCellValue('R4','Uploaded');
                $sheet->setCellValue('S4','Dates');
                $sheet->setCellValue('T4','Paid');
                $sheet->setCellValue('U4','Bal');

                $count=5;
                $sno=1;

                $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Rent']);
                

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
                    $tid=Property::checkCurrentTenant($hid);
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames($tid);
                    }else{
                        $TenantNames=($rent==0)?'Caretaker':'Vacant';
                        $tid="Vacant";
                    }
                    $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                    $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
                    $paymentdates='';
                    if($allpayments=Payment::where('Plot',$id)->where('House',$hid)->where('Tenant',$tid)->where('Month',$month)->get()->first()){
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

                // $sheetno++;

                $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
                $filename=$propertyname.' Payments for '.$watermonthdate .' .' .strtolower('xls');
                $path=public_path('reports');
                $writer->save($path.'\\'.$filename);

                $writer->save($filename);


                header('Content-Type:application/x-www-form-urlencoded');
                header('Content-Transfer-Encoding:Binary');
                header("Content-disposition:attachment;filename=\"".$filename."\"");

                readfile($filename);

                unlink($filename);       

                exit;


            }
            
        }

    }

    

    public function downloadacknowledgement($tid,$hid,$month){
        $monthname=Property::getMonthDateAddWater($month);
        $housename=Property::getHouseName($hid);
        $propertyid=Property::getHouseProperty($hid);

            try { 
                $TenantNames="";
                $PaymentBal=0;
                $PaymentId="";
                $PaymentRent=0;
                $PaymentGarbage=0;
                $PaymentDeposit=0;
                $PaymentLease=0;
                $PaymentWaterbill=0;
                $PaymentPaid=0;
                $tenantphone="";

                $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;
                
                if ($tid!="") {
                    $TenantNames=Property::TenantNames($tid);
                    $tenantphone=$this->getTenantPhone($tid);
                    $allpayments=DB::table('payments')->where([
                             'Tenant'=>$tid,
                            'House'=>$hid,
                            'Month'=>$month])->get();
                    foreach($allpayments as $payment){
                        $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
                        $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
                        $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
                        $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
                        $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;
                        $PaymentId=$payment->id;
                    }
                }else{
                    $TenantNames="";
                    $tenantphone="";
                    $tid="Vacant";
                }

                $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
                $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                $Balance=$TotalUsed-$TotalPaid;
                $Deposit=$KPLC+$HseDeposit+$Water;
                $PaymentBal=$Balance;
                $PaymentRent=$Rent;
                $PaymentGarbage=$Garbage;
                $PaymentDeposit=$Deposit;
                $PaymentLease=$Lease;
                $PaymentWaterbill=$Waterbill;
                $PaymentPaid=$TotalPaid;

                $pdf=PDF::loadView('reports.download',compact('month','housename','TenantNames','PaymentBal','PaymentId','PaymentRent','PaymentGarbage','PaymentDeposit','PaymentLease','PaymentWaterbill','PaymentPaid','monthname'));
                $path=public_path('aknowledgements');
                $yearmonthname=Property::dateToMonthName($month);
                $fileName=$housename.'_'.$yearmonthname.'_Aknowledgement_On_'.date('Y_n_d').'.pdf';
                $phone=$tenantphone;
                $pdf->setEncryption($phone);
                $pdf->save($path.'\\'.$fileName);
                Property::saveReport('aknowledgements',$fileName,$tid);
                return redirect('/properties/View/Reports/Payments/'.$propertyid.'/'.$month)->with('success', 'Acknowledgement has been Generated for '.$fileName);
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                return redirect('/properties/View/Reports/Payments/'.$propertyid.'/'.$month)->with('dbError', $ex->getMessage());
            }

        // return $pdf->stream($fileName);
    }

    public function downloadacknowledgementforAllPropertySelected($pid,$month){
        $payments=House::where('Plot',$pid)->get(['id','Plot','Housename']);
        try { 
                $monthname=Property::getMonthDateAddWater($month);
                foreach($payments as $housesinfo){
                    $tid=Property::checkCurrentTenant($housesinfo->id);
                    $hid=$housesinfo->id;
                    $housename=Property::getHouseName($hid);
                    $TenantNames=Property::TenantNames($tid);

                    $PaymentBal=0;
                    $PaymentId="";
                    $PaymentRent=0;
                    $PaymentGarbage=0;
                    $PaymentDeposit=0;
                    $PaymentLease=0;
                    $PaymentWaterbill=0;
                    $PaymentPaid=0;
                    $tenantphone="";

                    $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
                    $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;
                    
                    if ($tid!="") {
                        $TenantNames=Property::TenantNames($tid);
                        $tenantphone=$this->getTenantPhone($tid);
                        $allpayments=DB::table('payments')->where([
                                 'Tenant'=>$tid,
                                'House'=>$hid,
                                'Month'=>$month])->get();
                        foreach($allpayments as $payment){
                            $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
                            $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
                            $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
                            $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
                            $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;
                            $PaymentId=$payment->id;
                        }
                    }else{
                        $TenantNames="";
                        $tenantphone="";
                        $tid="Vacant";
                    }

                    $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
                    $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
                    $Balance=$TotalUsed-$TotalPaid;
                    $Deposit=$KPLC+$HseDeposit+$Water;
                    $PaymentBal=$Balance;
                    $PaymentRent=$Rent;
                    $PaymentGarbage=$Garbage;
                    $PaymentDeposit=$Deposit;
                    $PaymentLease=$Lease;
                    $PaymentWaterbill=$Waterbill;
                    $PaymentPaid=$TotalPaid;
                    
                    try { 
                        $pdf=PDF::loadView('reports.download',compact('month','housename','TenantNames','PaymentBal','PaymentId','PaymentRent','PaymentGarbage','PaymentDeposit','PaymentLease','PaymentWaterbill','PaymentPaid','monthname'));
                        $path=public_path('aknowledgements');
                        $yearmonthname=Property::dateToMonthName($month);
                        $fileName=$housename.'_'.$yearmonthname.'_Aknowledgement_On_'.date('Y_n_d').'.pdf';
                        $phone=$tenantphone;
                        $pdf->setEncryption($phone);
                        $pdf->save($path.'\\'.$fileName);
                    
                        Property::saveReport('aknowledgements',$fileName,$tid);
                    }catch(\Illuminate\Database\QueryException $ex){ 
                        continue;
                    }catch(ErrorException $exc){
                         continue;
                    }

                }
            
            return redirect('/properties/View/Reports/Payments/'.$pid.'/'.$month)->with('success', 'All Acknowledgement has been Generated for this Property');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            return redirect('/properties/View/Reports/Payments/'.$pid.'/'.$month)->with('dbError', $ex->getMessage());
        }
    }


    public function downloadtenantsinfoexcel($id)
    {
        
        $file=new Spreadsheet();

        $active_sheet=$file->getActiveSheet();

        $propertyname=($id=='New')?'Tenant Not Assigned':'Tenant Vacated';
        $propertycode=$id;
        if($id=='New' || $id=='Vacated'){
            $active_sheet->setCellValue('A1',$propertyname.' TENANTS CONTACTS REPORT');

            $active_sheet->setCellValue('A2','No');
            $active_sheet->setCellValue('B2','Tenant Name');
            $active_sheet->setCellValue('C2','Phone');
            $active_sheet->setCellValue('D2','Email');
            $active_sheet->setCellValue('E2','Gender');
            $active_sheet->setCellValue('F2','IDno');
            $active_sheet->setCellValue('G2','Comments');


            $alltenantinfo = Tenant::orderByDesc('id')->where('Status',$id)->get();
            $count=3;
            $sno=1;
            foreach ($alltenantinfo as $tenants) {
                $tenantname=$tenants->Fname.' '.$tenants->Oname;
                
                $active_sheet->setCellValue('A'.$count,$sno);
                $active_sheet->setCellValue('B'.$count,$tenantname);
                $active_sheet->setCellValue('C'.$count,$tenants->Phone);
                $active_sheet->setCellValue('D'.$count,($tenants->Email)==null?"":$tenants->Email);
                $active_sheet->setCellValue('E'.$count,$tenants->Gender);
                $active_sheet->setCellValue('F'.$count,$tenants->IDno);
                $active_sheet->setCellValue('G'.$count,'    ');

                $count++;
                $sno++;
                
            }

            $active_sheet->mergeCells('A1:G1');
            $active_sheet->setTitle($propertycode);

        }
        else{

            $propertyname=Property::getPropertyName($id);
            $propertycode=Property::getPropertyCode($id);

            $active_sheet->setCellValue('A1',$propertyname.' TENANTS CONTACTS REPORT');

            $active_sheet->setCellValue('A2','No');
            $active_sheet->setCellValue('B2','Hse');
            $active_sheet->setCellValue('C2','Tenant Name');
            $active_sheet->setCellValue('D2','Phone');
            $active_sheet->setCellValue('E2','Email');
            $active_sheet->setCellValue('F2','Gender');
            $active_sheet->setCellValue('G2','IDno');
            $active_sheet->setCellValue('H2','Comments');

            $count=3;
            $sno=1;
            $tenantsinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Rent']);
            foreach ($tenantsinfo as $tenants) {
                $hid=$tenants->id;
                $house=$tenants->Housename;
                $rent=$tenants->Rent;
                $house=explode('-', $house);
                // $housename=$house[1];

                $housename=$tenants->Housename;
                $tid=Property::checkCurrentTenant($hid);
                $tenantname=Property::checkCurrentTenantName($tid);
                $tenantname=($tenantname=="")?'Vacant':$tenantname;
                $tenantname=($rent==0)?'Caretaker':$tenantname;
                $phone=$this->getTenantPhone($tid);
                $email=$this->getTenantEmail($tid);
                $gender=$this->getTenantGender($tid);
                $idno=$this->getTenantIDno($tid);

                $active_sheet->setCellValue('A'.$count,$sno);
                $active_sheet->setCellValue('B'.$count,$housename);
                $active_sheet->setCellValue('C'.$count,$tenantname);
                $active_sheet->setCellValue('D'.$count,$phone);
                $active_sheet->setCellValue('E'.$count,$email);
                $active_sheet->setCellValue('F'.$count,$gender);
                $active_sheet->setCellValue('G'.$count,$idno);
                $active_sheet->setCellValue('H'.$count,'    ');


                $count++;
                $sno++;
            }

            $active_sheet->mergeCells('A1:H1');
            $active_sheet->setTitle($propertycode);

        }

        



        $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
        $filename=$propertyname. ' Tenants info .' .strtolower('xls');
        $writer->save($filename);

        header('Content-Type:application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding:Binary');
        header("Content-disposition:attachment;filename=\"".$filename."\"");

        readfile($filename);

        unlink($filename);       

        exit;
    }

    public function downloadalltenantsinfoexcel(){
        $file=new Spreadsheet();

        $propertyinfo=Property::all();
        $sheetno=0;
        foreach ($propertyinfo as $property) {
            $propertyname= $property->Plotname;
            $propertycode= $property->Plotcode;
            $plotid= $property->id;
            $file->createSheet();
            $sheet=$file->getSheet($sheetno);

            $sheet->setCellValue('A1',$propertyname.' TENANTS CONTACTS REPORT');

            $sheet->setCellValue('A2','No');
            $sheet->setCellValue('B2','Hse');
            $sheet->setCellValue('C2','Tenant Name');
            $sheet->setCellValue('D2','Phone');
            $sheet->setCellValue('E2','Email');
            $sheet->setCellValue('F2','Gender');
            $sheet->setCellValue('G2','IDno');
            $sheet->setCellValue('H2','Comments');

            $count=3;
            $sno=1;
            $tenantsinfo=House::where('Plot',$plotid)->get(['id','Plot','Housename','Rent']);
            foreach ($tenantsinfo as $tenants) {
                $hid=$tenants->id;
                $house=$tenants->Housename;
                $rent=$tenants->Rent;
                $house=explode('-', $house);
                // $housename=$house[1];
                $housename=$tenants->Housename;
                $tid=Property::checkCurrentTenant($hid);
                $tenantname=Property::checkCurrentTenantName($tid);
                $tenantname=($tenantname=="")?'Vacant':$tenantname;
                $tenantname=($rent==0)?'Caretaker':$tenantname;
                $phone=$this->getTenantPhone($tid);
                $email=$this->getTenantEmail($tid);
                $gender=$this->getTenantGender($tid);
                $idno=$this->getTenantIDno($tid);
                

                $sheet->setCellValue('A'.$count,$sno);
                $sheet->setCellValue('B'.$count,$housename);
                $sheet->setCellValue('C'.$count,$tenantname);
                $sheet->setCellValue('D'.$count,$phone);
                $sheet->setCellValue('E'.$count,$email);
                $sheet->setCellValue('F'.$count,$gender);
                $sheet->setCellValue('G'.$count,$idno);
                $sheet->setCellValue('H'.$count,'    ');

                $count++;
                $sno++;
            }
            $sheet->mergeCells('A1:H1');
            $sheet->setTitle($propertycode);

            $sheetno++;
        }

        $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
        $filename='All Properties Tenants info .' .strtolower('xls');
        $writer->save($filename);

        header('Content-Type:application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding:Binary');
        header("Content-disposition:attachment;filename=\"".$filename."\"");

        readfile($filename);

        unlink($filename);       

        exit;
    }



    public function downloadtenantsacknowledgement($filename)
    {
        
        $path='public/aknowledgements';
        $report_file_name=$path.'\\'.$filename;
        $pdf=PDF::loadFile($report_file_name);
        
        return $pdf->download($report_file_name);
          // header('Content-Type: application/pdf');
          // // header('Content-Disposition: attachment; filename=' . $report_file_name);
          // header("Content-disposition:attachment;filename=\"".$report_file_name."\"");
          // header('Content-Transfer-Encoding: binary');
          // header('Content-Length: ' . filesize($report_file_name));
          // ob_clean();
          // flush();
          // readfile($report_file_name);
          // exec('rm ' . $report_file_name);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $storeData = $request->validate([
            'Plotname' => ['required', 'string', 'max:100'],
            'Plotarea' => ['required', 'string', 'max:100'],
            'Plotcode' => ['required', 'string', 'max:100'],   
            'Plotaddr' => ['required', 'string', 'max:255'],
            'Waterbill' => ['required', 'string', 'max:100'],
            'Plotdesc' => ['required', 'string', 'max:255'],
            'Deposit' => ['required', 'string', 'max:100'],
            'Waterdeposit' => ['required', 'string', 'max:100'],
            'Outsourced' => ['required', 'string', 'max:100'],
            'Garbage' => ['required', 'string', 'max:100'],
            'Kplcdeposit' => ['required', 'string', 'max:100'],
        ]);

         try { 
            $propertyinfo = new Property;
            $propertyinfo->Plotname =$request->input('Plotname');
            $propertyinfo->Plotarea =$request->input('Plotarea');
            $propertyinfo->Plotcode =$request->input('Plotcode');
            $propertyinfo->Plotaddr =$request->input('Plotaddr');
            $propertyinfo->Plotdesc =$request->input('Plotdesc');
            $propertyinfo->Waterbill =$request->input('Waterbill');
            $propertyinfo->Deposit =$request->input('Deposit');
            $propertyinfo->Waterdeposit =$request->input('Waterdeposit');
            $propertyinfo->Outsourced =$request->input('Outsourced');
            $propertyinfo->Garbage =$request->input('Garbage');
            $propertyinfo->Kplcdeposit =$request->input('Kplcdeposit');
            $propertyinfo->save();
            Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Added');
                return redirect('/newproperties')->with('success', 'Property Information Saved!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
              Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Not Saved');
                return redirect('/newproperties')->with('dbError', $ex->getMessage());
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
        
        $propertyinfo = Property::all();
        $propertyhouses = House::all();
        $tenantsinfo = Tenant::all();
        $properties = Property::findOrFail($id);
        return view('updateproperty', compact('properties','propertyinfo','propertyhouses','tenantsinfo'));
    }


    public function houses($id)
    {   
        
        $housesinfo = House::where('Plot',$id)->get();
        $propertyinfo = Property::all();
        $properties = Property::findOrFail($id);
        return view('propertyhouses', compact('properties','propertyinfo','housesinfo'));
    }

    public function allhouses()
    {   
        
        $housesinfo = House::all();
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        return view('allhouses', compact('propertyinfo','housesinfo','tenantsinfo'));
    }

    public function tenants($pid,$hid)
    {   
        
        $allhousesinfo=House::where('Plot',$pid)->get();
        $housesinfo=House::findOrFail($hid);
        $propertyhouses = House::all();
        $agreementinfo=Agreement::where('House',$hid)->get();
        $propertyinfo = Property::all();
        $properties = Property::findOrFail($pid);
        return view('housestenants', compact('properties','propertyinfo','housesinfo','agreementinfo','allhousesinfo','propertyhouses'));
    }

    public function alltenants()
    {   
        
        $propertyinfo = Property::all();
        $propertyhouses = House::all();
        $tenantsinfo = Tenant::all();
        $alltenantsinfo = Tenant::where('Status','New')->get();
        return view('alltenants', compact('propertyinfo','tenantsinfo','propertyhouses','alltenantsinfo'));
    }

    public function statustenants($status)
    {   
        $propertyinfo = Property::all();
        $propertyhouses = House::all();
        $tenantsinfo = Tenant::all();
        $alltenantsinfo = Tenant::where('Status',$status)->get();
        return view('alltenants', compact('propertyinfo','tenantsinfo','propertyhouses','alltenantsinfo'));
    }


    public function propertytenants($id)
    {   
        
        $propertyinfo = Property::all();
        $propertyhouses = House::all();
        $tenantsinfo = Tenant::all();
        $houseinfo=House::where('Plot',$id)->get();
        $tenants_data= array();
        foreach($houseinfo as $result){
            $hid=$result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantsdatas=DB::table('tenants')->where([
                'id'=>$tid
            ])->get();
            foreach($tenantsdatas as $msga){
                $tenants_data[] = array(
                    'Phone' => $msga->Phone,
                    'id' => $tid,
                    'Fname' => $msga->Fname,
                    'Oname' => $msga->Oname,
                    'Gender' => $msga->Gender,
                    'Status' => $msga->Status,
                    'IDno' => $msga->IDno,
                    'Email' => $msga->Email,
                    'created_at' => $msga->created_at
                );
            }
        }
        $alltenantsinfo=$tenants_data;
        return view('alltenants', compact('propertyinfo','tenantsinfo','propertyhouses','alltenantsinfo'));
    }

    public function housestenants($id)
    {   
        
        $propertyinfo = Property::all();
        $propertyhouses = House::all();
        $tenantsinfo = Tenant::all();
        $tenants_data= array();
        $tid=Property::checkCurrentTenant($id);
            $tenantsdatas=DB::table('tenants')->where([
                'id'=>$tid
            ])->get();
            foreach($tenantsdatas as $msga){
                $tenants_data[] = array(
                    'Phone' => $msga->Phone,
                    'id' => $tid,
                    'Fname' => $msga->Fname,
                    'Oname' => $msga->Oname,
                    'Gender' => $msga->Gender,
                    'Status' => $msga->Status,
                    'IDno' => $msga->IDno,
                    'Email' => $msga->Email,
                    'created_at' => $msga->created_at
                );
            }
        $alltenantsinfo=$tenants_data;
        return view('alltenants', compact('propertyinfo','tenantsinfo','propertyhouses','alltenantsinfo'));
    }

    public function messages(){
        
        $propertyinfo = Property::all();
        $waterbill="";
        $thisproperty='';
        $thismode='';
        $watermonth='';
        $thisothers='';
        $monthinfo=DB::table('waters')
            ->select('Month')
            ->where('Month','>=','2020 1')
            ->groupby('Month')
            ->orderby('Month','Desc')
            ->get('Month');
        return view('messages', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thisothers'));   
    }


    public function addwaterbill(){
        
        $propertyinfo = Property::all();
        $waterbill="";
        $thisproperty='';
        $thismode='';
        $watermonth='';
        $thishouse='';
        $thistenant='';
        $monthinfo= '';
        $thisothers= '';
        return view('waterbill', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thistenant','thishouse','thisothers'));   
    }

    public function addwaterbillproperty($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '';
        $thishouse='';
        $thisothers= '';
        $thistenant= '';
        if ($id==9 || $id==10) {
            $houseinfo=House::orderBy('id')->where('Plot',$id)->get();
        }
        else{
            $houseinfo=House::where('Plot',$id)->get();
        }
        

        $watermessage_data= array();
        foreach($houseinfo as $result){
            $hid= $result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            $watermessages=DB::table('waters')->where([
                    'House'=>$hid,
                    'Month'=>$watermonth
                        ])->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'Previous' => $msga->Previous,
                    'Current' => $msga->Current,
                    'Cost' => $msga->Cost,
                    'Units' => $msga->Units,
                    'Total' => $msga->Total,
                    'Total_OS' => $msga->Total_OS,
                    'Month' => $msga->Month,
                    'created_at' => $msga->created_at
                );
            }
        }
        $waterbill=$watermessage_data;

        return view('waterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','waterbill','thisothers','thistenant'));   
    }

    public function addwaterbillothers($month){
        
        $propertyinfo = Property::all();
        $thisproperty='';
        $thismode='';
        $watermonth=$month;
        $monthinfo= '';
        $thishouse='';
        $thisothers= 'Other';
        $houseinfo= '';
        $thistenant='';
        $thiswaterbill='';
        $thismonthwaterstatus='';
        $tenantinfo=Tenant::where('Status','Other')->get();

        $watermessage_data= array();
        foreach($tenantinfo as $result){
            $tid=$result['id'];
            $phone=$result['Phone'];
            $tenantname=$result['Fname'].' '.$result['Oname'];
            $tenantphone='+254'.substr($phone, 0);
            $watermessages=DB::table('water_others')->where([
                'Tenant'=>$tid,
                'Month'=>$watermonth
            ])->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'Phone' => $phone,
                    'tid' => $tid,
                    'tenantname' => $tenantname,
                    'Previous' => $msga->Previous,
                    'Current' => $msga->Current,
                    'Cost' => $msga->Cost,
                    'Units' => $msga->Units,
                    'Total' => $msga->Total,
                    'Total_OS' => $msga->Total_OS,
                    'Month' => $msga->Month,
                    'created_at' => $msga->created_at
                );
            }
        }
        $waterbill=$watermessage_data;
        return view('waterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','waterbill','thisothers','thistenant','tenantinfo','thiswaterbill','thismonthwaterstatus'));   
    }
    public function addwaterbillotherstenant($month,$tid){
        
        $propertyinfo = Property::all();
        $thisproperty='';
        $thismode='';
        $watermonth=$month;
        $monthinfo= '';
        $thisothers= 'Other';
        $thishouse='';
        $thistenant=Tenant::findOrFail($tid);
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $waterbill=DB::table('water_others')->where([
                'Tenant'=>$tid,
                'Month'=>$lastmonth
            ])->get();
        $thiswaterbill=DB::table('water_others')->where([
                'Tenant'=>$tid,
                'Month'=>$month
            ])->get();
        $thismonthwaterstatus='';
        if ($thiswaterbill!='[]') {
           $thismonthwaterstatus="Yes";
        }
        else{
            $thismonthwaterstatus="";
        }
        $houseinfo='';
        $tenantinfo=Tenant::where('Status','Other')->get();
        return view('waterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','waterbill','thiswaterbill','thismonthwaterstatus','thisothers','tenantinfo','thistenant'));   
    }

    public function addwaterbillpropertyhouse($id,$month,$hid){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '';
        $thisothers= '';
        $thistenant='';
        $thishouse=House::findOrFail($hid);
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $waterbill=DB::table('waters')->where([
                'House'=>$hid,
                'Month'=>$lastmonth
            ])->get();
        $thiswaterbill=DB::table('waters')->where([
                'House'=>$hid,
                'Month'=>$month
            ])->get();
        $agreements=DB::table('agreements')
            ->where([
                'House'=>$hid
            ])
            ->orderby('DateAssigned','Desc')->get();
        $thismonthwaterstatus='';
        if ($thiswaterbill!='[]') {
           $thismonthwaterstatus="Yes";
        }
        else{
            $thismonthwaterstatus="";
        }
        if ($id==9 || $id==10) {
            $houseinfo=House::orderBy('id')->where('Plot',$id)->get();
        }
        else{
            $houseinfo=House::where('Plot',$id)->get();
        }
        
        return view('waterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','waterbill','thiswaterbill','thismonthwaterstatus','agreements','thistenant','thisothers'));   
    }

    public function propertymessagemodemonth($id,$mode,$watermonth){
        
        $propertyinfo = Property::all();
        $thismode= $mode;
        $thisothers= '';
        $thisproperty=Property::findOrFail($id);
        $watermonth =$watermonth;
        $monthinfo=DB::table('waters')
            ->select('Month')
            ->where('Month','>=','2020 1')
            ->groupby('Month')
            ->orderby('Month','Desc')
            ->get('Month');
        // Choose Tenant,Choose Rent,Send All Tenant
        if ($mode=="Single Water" || $mode=="All Water") {
            $waterbill=DB::table('waters')->where([
                'Plot'=>$id,
                'Month'=>$watermonth
            ])->get();
        }
        else if ($mode=="Choose Tenant" || $mode=="Send All Tenant") {
            $waterbill=DB::table('agreements')->where([
                'Plot'=>$id,
                'Month'=>0
            ])->get();
        }
        else if($mode=="Completed Payment" || $mode=="Summary Paid") {
            // $allpayments=DB::table('payments')->where([
            //     'Plot'=>$id,
            //     'Month'=>$watermonth
            // ])->get();
            // $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
            // $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;
            // $payments= array();
            // foreach($allpayments as $payment){
            //     $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
            //     $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
            //     $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
            //     $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
            //     $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;$Penalty=0;

            //     $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
            //     $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;$Penalty=$payment->Penalty;
            //     $Balance=$TotalUsed-$TotalPaid;

            //     $hid= $payment->House;
            //     $housename= Property::getHouseName($hid);
            //     $tid=Property::checkCurrentTenant($hid);

            //     if ($tid!="") {
            //         $TenantNames=Property::TenantNames($tid);
            //         $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            //     }
            //     else{
            //         $TenantNames="Vacant";
            //         $tenantphone="";
            //         $tid="Vacant";
            //     }

            //     $payments[] = array(
            //         'pid' => $id,
            //         'hid' => $hid,
            //         'id' => $payment->id,
            //         'Tenant'=>$payment->Tenant,
            //         'tid'=>$tid,
            //         'Tenantname'=>$TenantNames,
            //         'Phone'=>$tenantphone,
            //         'Housename'=>$housename,
            //         'Rent' => $Rent,
            //         'Garbage' => $Garbage,
            //         'KPLC' => $KPLC,
            //         'HseDeposit' => $HseDeposit,
            //         'Water' => $Water,
            //         'Lease' => $Lease,
            //         'Month' => $watermonth,
            //         'Waterbill' => $Waterbill,
            //         'Equity' => $Equity,
            //         'Cooperative' => $Cooperative,
            //         'Others' => $Others,
            //         'Excess' => $Excess,
            //         'Arrears' => $Arrears,
            //         'PaidUploaded' => $PaidUploaded,
            //         'TotalUsed' => $TotalUsed,
            //         'TotalPaid' => $TotalPaid,
            //         'Penalty' => $Penalty,
            //         'Balance' => $Balance,
            //     );
            // }
            $waterbill="";
            // $waterbill=$payments;
            $monthinfo=DB::table('payments')
                ->select('Month')
                ->where('Month','>=','2020 1')
                ->groupby('Month')
                ->orderby('Month','Desc')
                ->get('Month');
            return view('messages', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thisothers'));   
        }
        else{
            $waterbill="";
        }
        // echo $waterbill;
        
        return view('messages', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thisothers'));   
    }

    public function propertymessagemodemonthload($id,$mode,$watermonth){
        
        $propertyinfo = Property::all();
        $thismode= $mode;
        $thisothers= '';
        $thisproperty=Property::findOrFail($id);
        $watermonth =$watermonth;
        $MonthWaterDate=TenantController::getMonthWaterDate($watermonth);
        
        // Choose Tenant,Choose Rent,Send All Tenant
        if ($mode=="Single Water" || $mode=="All Water") {
            $waterbill=DB::table('waters')->where([
                'Plot'=>$id,
                'Month'=>$watermonth
            ])->get();
            $monthinfo=DB::table('waters')
                ->select('Month')
                ->where('Month','>=','2020 1')
                ->groupby('Month')
                ->orderby('Month','Desc')
                ->get('Month');
        }
        else if ($mode=="Choose Tenant" || $mode=="Send All Tenant") {
            $waterbill=DB::table('agreements')->where([
                'Plot'=>$id,
                'Month'=>0
            ])->get();
            $monthinfo=DB::table('waters')
                ->select('Month')
                ->where('Month','>=','2020 1')
                ->groupby('Month')
                ->orderby('Month','Desc')
                ->get('Month');
        }
        else if($mode=="Completed Payment" || $mode=="Summary Paid") {
            $allpayments=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$watermonth
            ])->get();
            $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
            $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;
            $payments= array();
            foreach($allpayments as $payment){
                $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
                $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
                $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
                $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
                $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;$Penalty=0;

                $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
                $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;$Penalty=$payment->Penalty;
                $Balance=$TotalUsed-$TotalPaid;

                $hid= $payment->House;
                $housename= Property::getHouseName($hid);
                $tid=Property::checkCurrentTenant($hid);
                $SentDatePayment='';
                if ($tid!="") {
                    $TenantNames=Property::TenantNames($tid);
                    $TenantFNames=Property::TenantFNames($tid);
                    $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
                    $TenantPhones=TenantController::TenantPhone($tid);
                    if($mode=="Completed Payment") {
                        $SentDatePayment=(TenantController::getSentDatePaymentCompleted($id,$tid,$watermonth))?TenantController::getSentDatePaymentCompleted($id,$tid,$watermonth):'';
                    }
                    elseif($mode=="Summary Paid"){
                        $SentDatePayment=(TenantController::getSentDatePaymentSummary($id,$tid,$watermonth))?TenantController::getSentDatePaymentSummary($id,$tid,$watermonth):'';
                    }
                    
                    
                }
                else{
                    $TenantNames="Vacant";
                    $tenantphone="";
                    $tid="Vacant";
                }
                

                $payments[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'id' => $payment->id,
                    'Tenant'=>$payment->Tenant,
                    'tid'=>$tid,
                    'Tenantname'=>$TenantNames,
                    'Tenantfname'=>$TenantFNames,
                    'Phone'=>$tenantphone,
                    'TenantPhones'=>$TenantPhones,
                    'SentDatePayment'=>$SentDatePayment,
                    'Housename'=>$housename,
                    'Rent' => $Rent,
                    'Garbage' => $Garbage,
                    'KPLC' => $KPLC,
                    'HseDeposit' => $HseDeposit,
                    'Water' => $Water,
                    'Lease' => $Lease,
                    'Month' => $watermonth,
                    'MonthWaterDate'=>$MonthWaterDate,
                    'Waterbill' => $Waterbill,
                    'Equity' => $Equity,
                    'Cooperative' => $Cooperative,
                    'Others' => $Others,
                    'Excess' => $Excess,
                    'Arrears' => $Arrears,
                    'PaidUploaded' => $PaidUploaded,
                    'TotalUsed' => $TotalUsed,
                    'TotalPaid' => $TotalPaid,
                    'Penalty' => $Penalty,
                    'Balance' => $Balance,
                );
            }
            
            $waterbill=$payments;
            $monthinfo=DB::table('payments')
                ->select('Month')
                ->where('Month','>=','2020 1')
                ->groupby('Month')
                ->orderby('Month','Desc')
                ->get('Month');

        }
        else{
            $waterbill="";
        }
        // echo $waterbill;
        
        return compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thisothers');   
        // return view('messages', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thisothers'));   
    }

    

    public function othersmessagemodemonth($mode,$watermonth){
        
        $propertyinfo = Property::all();
        $thismode= $mode;
        $thisothers= 'Other';
        $thisproperty='';
        $watermonth =$watermonth;
        if ($mode=="Other Water") {
            $waterbill=DB::table('water_others')->where([
                'Month'=>$watermonth
            ])->get();
        }
        elseif ($mode=="Other Notification") {
            $monthdate= $this->getNextMonthdate($watermonth);
            $nextmonth= $this->getNextMonth($watermonth,$monthdate);
            $waterbill=DB::table('payments_others')->where([
                'Month'=>$nextmonth
            ])->get();
        }
        else{
            $waterbill="";
        }
        $monthinfo=DB::table('water_others')
            ->select('Month')
            ->where('Month','>=','2020 1')
            ->groupby('Month')
            ->orderby('Month','Desc')
            ->get('Month');
        return view('messages', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thisothers'));   
    }

    
    public function viewwaterbillmessages(){
        
        $propertyinfo = Property::all();
        $waterbill="";
        $thisproperty='';
        $thismode='';
        $watermonth='';
        $thishouse='';
        $monthinfo='';
        $waterbillmessage='';
        $housesinfo=House::all();
        return view('viewwaterbillmessage', compact('propertyinfo','thisproperty','thismode','waterbillmessage','watermonth','monthinfo','thishouse','housesinfo'));   
    }

    public function propertyviewwaterbillmessages($pid,$month){
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($pid);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $housesinfo=House::where('Plot',$pid)->get();
        $watermessage_data= array();
        foreach($housesinfo as $result){
            $hid= $result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            $watermessages=DB::table('water_messages')->where([
                'To'=>$tenantphone,
                'House'=>$hid,
                'Month'=>$watermonth
            ])->orderby('id','Desc')->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $pid,
                    'hid' => $hid,
                    'tid' => $tid,
                    'To' => $msga->To,
                    'Message' => $msga->Message
                );
            }
        }
        $waterbillmessage=$watermessage_data;
        return view('viewwaterbillmessage', compact('propertyinfo','thisproperty','thismode','waterbillmessage','watermonth','monthinfo','thishouse','housesinfo'));   
    }

    public function propertyviewwaterbillmessageshouse($pid,$month,$hid){
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($pid);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse=House::findOrFail($hid);
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $housesinfo=House::where('Plot',$pid)->get();
        $watermessage_data= array();
        $tid=Property::checkCurrentTenant($hid);
        $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
        $watermessages=DB::table('water_messages')->where([
            'To'=>$tenantphone,
            'House'=>$hid,
            'Month'=>$watermonth
        ])->orderby('id','Desc')->get();
        foreach($watermessages as $msga){
            $watermessage_data[] = array(
                'pid' => $pid,
                'hid' => $hid,
                'tid' => $tid,
                'To' => $msga->To,
                'Message' => $msga->Message
            );
        }
        $waterbillmessage=$watermessage_data;
        return view('viewwaterbillmessage', compact('propertyinfo','thisproperty','thismode','waterbillmessage','watermonth','monthinfo','thishouse','housesinfo'));   
    }

    public function viewothersmessages(){
        
        $propertyinfo = Property::all();
        $waterbill="";
        $thisproperty='';
        $thismode='';
        $thishouse='';
        $monthinfo='';
        $waterbillmessage='';
        $housesinfo=House::all();
        return view('viewothersmessage', compact('propertyinfo','thisproperty','thismode','waterbillmessage','monthinfo','thishouse','housesinfo'));   
    }

    public function propertyviewothersmessages($pid){
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($pid);
        $thismode='';
        $monthinfo= '' ;
        $thishouse='';
        $housesinfo=House::where('Plot',$pid)->get();
        $watermessage_data= array();
        foreach($housesinfo as $result){
            $hid= $result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            $watermessages=DB::table('messages')->where([
                'To'=>$tenantphone
            ])
            ->limit('1')->orderby('id','Desc')->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $pid,
                    'hid' => $hid,
                    'tid' => $tid,
                    'To' => $msga->To,
                    'Message' => $msga->Message,
                    'created_at' => $msga->created_at
                );
            }
        }
        $waterbillmessage=$watermessage_data;
        return view('viewothersmessage', compact('propertyinfo','thisproperty','thismode','waterbillmessage','monthinfo','thishouse','housesinfo'));   
    }

    public function propertyviewothersmessageshouse($pid,$hid){
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($pid);
        $thismode='';
        $monthinfo= '' ;
        $thishouse=House::findOrFail($hid);
        $housesinfo=House::where('Plot',$pid)->get();
        $watermessage_data= array();
        $tid=Property::checkCurrentTenant($hid);
        $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
        $watermessages=DB::table('messages')->where([
            'To'=>$tenantphone
        ])->orderby('id','Desc')->get();
        foreach($watermessages as $msga){
            $watermessage_data[] = array(
                'pid' => $pid,
                'hid' => $hid,
                'tid' => $tid,
                'To' => $msga->To,
                'Message' => $msga->Message,
                'created_at' => $msga->created_at
            );
        }
        $waterbillmessage=$watermessage_data;
        return view('viewothersmessage', compact('propertyinfo','thisproperty','thismode','waterbillmessage','monthinfo','thishouse','housesinfo'));   
    }

    public function uploadwaterbill(){
        
        $propertyinfo = Property::all();
        $waterbill="";
        $thisproperty='';
        $thismode='';
        $watermonth='';
        $thishouse='';
        $monthinfo= '' ;
        $output='';
        return view('uploadwaterbill', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thishouse','output'));   
    }

    public function updatewaterbill(){
        
        $propertyinfo = Property::all();
        $waterbill="";
        $thisproperty='';
        $thismode='';
        $watermonth='';
        $thishouse='';
        $monthinfo= '' ;
        $output='';
        return view('updatewaterbill', compact('propertyinfo','thisproperty','thismode','waterbill','watermonth','monthinfo','thishouse','output'));   
    }

    public function uploadwaterbillproperty($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $houseinfo=House::where('Plot',$id)->get();
        $watermessage_data= array();
        foreach($houseinfo as $result){
            $hid= $result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            $watermessages=DB::table('waters')->where([
                'House'=>$hid,
                'Month'=>$watermonth
            ])->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'tid' => $msga->Tenant,
                    'Previous' => $msga->Previous,
                    'Current' => $msga->Current,
                    'Cost' => $msga->Cost,
                    'Units' => $msga->Units,
                    'Total' => $msga->Total,
                    'Total_OS' => $msga->Total_OS,
                    'Month' => $msga->Month,
                    'created_at' => $msga->created_at
                );
            }
        }
        $waterbill=$watermessage_data;

        $output='';
        return view('uploadwaterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','waterbill'));   
    }

    public function updatewaterbillproperty($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo= $this->getMonthDate($month);
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $houseinfo=House::where('Plot',$id)->get();
        $watermessage_data= array();
        foreach($houseinfo as $result){
            $hid= $result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            $watermessages=DB::table('waters')->where([
                'House'=>$hid,
                'Month'=>$watermonth
            ])->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'tid' => $msga->Tenant,
                    'Previous' => $msga->Previous,
                    'Current' => $msga->Current,
                    'Cost' => $msga->Cost,
                    'Units' => $msga->Units,
                    'Total' => $msga->Total,
                    'Total_OS' => $msga->Total_OS,
                    'Month' => $msga->Month,
                    'created_at' => $msga->created_at
                );
            }
        }
        $waterbill=$watermessage_data;

        $output='';
        return view('updatewaterbill', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','waterbill'));   
    }

    public function updatewaterbillpropertyload($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo= $this->getMonthDate($month);
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $houseinfo=House::where('Plot',$id)->get();
        
        $output='';
        $watermessage_data= array();
        foreach($houseinfo as $result){
            $hid= $result['id'];
            $tid=Property::checkCurrentTenant($hid);
            $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
            $waterid=Property::checkCurrentTenantBill($hid,$tid,$month);
            $housename=$result['Housename'];
            $tenantname='';
            if ($tid=='') {
                $tenant='Vacated';
                $tenantname='House Vacant';
            }
            else{
                $tenantname=Property::checkCurrentTenantName($tid);
            }
            $watermessages=DB::table('waters')->where([
                'House'=>$hid,
                'Month'=>$watermonth
            ])->get();
            
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $id,
                    'id' => $hid,
                    'tid' => $msga->Tenant,
                    'previous' => ($msga->Previous!='')?$msga->Previous:'',
                    'current' => ($msga->Current!='')?$msga->Current:'',
                    'cost' => $msga->Cost,
                    'units' => $msga->Units,
                    'total' => $msga->Total,
                    'total_os' => $msga->Total_OS,
                    'housename'=>$housename,
                    'tenantname' => $tenantname,
                    'waterid' => $waterid,
                    'month' => $msga->Month,
                    'created_at' => $msga->created_at
                );
            }
        }
        $output=$watermessage_data;
        $curmonth=$this->getMonthDate($month);
        $success='<span>Waterbill for '.$curmonth.' is Loaded.</br>Please Drag or Browser Waterbill to Update</span></br>
                <span class="" title="Waterbill per House Uploaded">
                    Waterbill Saved: <b >'.$this->getTotalBillsHse($id,$month).'/'.$this->getTotalHousesHse($id).'</b> </span></br>
                <span class="" title="Waterbill Messages Sent">
                    Messages Sent: <b >'.$this->getTotalBillsMsgHse($id,$month).'/'.$this->getTotalHousesHse($id).'</b> </span>';
        return compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','success');
        // return compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','waterbill');   
    }
    

    public static function getTenantPhone($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Phone'];
            }
        return $resultname;
    }

    public static function getTenantEmail($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Email'];
            }
        return $resultname;
    }

    public static function getTenantGender($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Gender'];
            }
        return $resultname;
    }

    public static function getTenantIDno($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['IDno'];
            }
        return $resultname;
    }

    public static function getMonthDate($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'M, Y');
        return $month;
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        try { 
         $updateProperty = $request->validate([
            'Plotname' => ['required', 'string', 'max:100'],
            'Plotarea' => ['required', 'string', 'max:100'],
            'Plotcode' => ['required', 'string', 'max:100'],
            'Plotaddr' => ['required', 'string', 'max:255'],
            'Waterbill' => ['required', 'string', 'max:100'],
            'Plotdesc' => ['required', 'string', 'max:255'],
            'Deposit' => ['required', 'string', 'max:100'],
            'Waterdeposit' => ['required', 'string', 'max:100'],
            'Outsourced' => ['required', 'string', 'max:100'],
            'Garbage' => ['required', 'string', 'max:100'],
            'Kplcdeposit' => ['required', 'string', 'max:100'],
        ]);
            Property::whereId($id)->update($updateProperty);
            Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Updated');
            return redirect('/plot/'.$id.'/edit')->with('success', 'Property has been updated');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
          Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Not Updated');
            return redirect('/plot/'.$id.'/edit')->with('dbError', $ex->getMessage());
        }
    }
    public static function getLastMonth($month,$monthdate){
        $watermonthlast= date("Y n",strtotime("-1 months",strtotime($monthdate)));
        return $watermonthlast;
    }

    public static function getLastMonthdate($watermonth){
        $explomonth=explode(' ', $watermonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y-m-01');
        return $month;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plotname=Property::getPropertyName($id);
        $property = Property::findOrFail($id);
        $property->delete();
        Property::setUserLogs('Property '.$plotname.' Deleted');
        return redirect('/properties')->with('success', 'Property has been deleted');
    }

    public function deleteProperty(Request $request)
    {
        try{
            $id=$request->input('delpid');
            $plotname=Property::getPropertyName($id);
            if($property = Property::find($id)){
                $property->delete();
                Property::setUserLogs('Property '.$plotname.' Deleted');
                $success='<span>Property has been Deleted</br>';
                return compact('success');
            }
            else{
                Property::setUserLogs('Property '.$plotname.' Deleted');
                $error='<span>Property is Not Found</br>';
                return compact('error');
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';

            $error='<span>Property Not Deleted</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Property is Occupied</br>';
            }
            
            Property::setUserLogs('Property '.($id).') Not Deleted.' .$error);
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>Property Not Deleted</br>'.$ex->getMessage();
            Property::setUserLogs('Property '.($id).') Not Deleted.' .$error);
            return compact('error');
        }
    }

    
    public function saveProperty(Request $request)
    {
        $storeData = $request->validate([
            'Plotname' => ['required', 'string', 'max:100'],
            'Plotarea' => ['required', 'string', 'max:100'],
            'Plotcode' => ['required', 'string', 'max:100'],   
            'Plotaddr' => ['required', 'string', 'max:255'],
            'Waterbill' => ['required', 'string', 'max:100'],
            'Plotdesc' => ['required', 'string', 'max:255'],
            'Deposit' => ['required', 'string', 'max:100'],
            'Waterdeposit' => ['required', 'string', 'max:100'],
            'Outsourced' => ['required', 'string', 'max:100'],
            'Garbage' => ['required', 'string', 'max:100'],
            'Kplcdeposit' => ['required', 'string', 'max:100'],
        ]);

         try { 
            $propertyinfo = new Property;
            $propertyinfo->Plotname =$request->input('Plotname');
            $propertyinfo->Plotarea =$request->input('Plotarea');
            $propertyinfo->Plotcode =$request->input('Plotcode');
            $propertyinfo->Plotaddr =$request->input('Plotaddr');
            $propertyinfo->Plotdesc =$request->input('Plotdesc');
            $propertyinfo->Waterbill =$request->input('Waterbill');
            $propertyinfo->Deposit =$request->input('Deposit');
            $propertyinfo->Waterdeposit =$request->input('Waterdeposit');
            $propertyinfo->Outsourced =$request->input('Outsourced');
            $propertyinfo->Garbage =$request->input('Garbage');
            $propertyinfo->Kplcdeposit =$request->input('Kplcdeposit');
            if($propertyinfo->save()){
                Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Added');
                $success='<span>Property Information has been Saved</br>';
                return compact('success');
            }
            else{
                Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Not Saved');
                $error='<span>Property Information has not Been Saved</br>';
                return compact('error');
            }
            
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>Property Was Not Saved</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Property is Already Saved</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>Property Not Saved</br>'.$ex->getMessage();
        }
    }

    public function updateProperty(Request $request)
    {
        $storeData = $request->validate([
            'Plotname' => ['required', 'string', 'max:100'],
            'Plotarea' => ['required', 'string', 'max:100'],
            'Plotcode' => ['required', 'string', 'max:100'],   
            'Plotaddr' => ['required', 'string', 'max:255'],
            'Waterbill' => ['required', 'string', 'max:100'],
            'Plotdesc' => ['required', 'string', 'max:255'],
            'Deposit' => ['required', 'string', 'max:100'],
            'Waterdeposit' => ['required', 'string', 'max:100'],
            'Outsourced' => ['required', 'string', 'max:100'],
            'Garbage' => ['required', 'string', 'max:100'],
            'Kplcdeposit' => ['required', 'string', 'max:100'],
        ]);

         try { 
            $id=$request->input('Plotid');
            if($propertyinfo = Property::find($id)){
                $propertyinfo->Plotname =$request->input('Plotname');
                $propertyinfo->Plotarea =$request->input('Plotarea');
                $propertyinfo->Plotcode =$request->input('Plotcode');
                $propertyinfo->Plotaddr =$request->input('Plotaddr');
                $propertyinfo->Plotdesc =$request->input('Plotdesc');
                $propertyinfo->Waterbill =$request->input('Waterbill');
                $propertyinfo->Deposit =$request->input('Deposit');
                $propertyinfo->Waterdeposit =$request->input('Waterdeposit');
                $propertyinfo->Outsourced =$request->input('Outsourced');
                $propertyinfo->Garbage =$request->input('Garbage');
                $propertyinfo->Kplcdeposit =$request->input('Kplcdeposit');
                if($propertyinfo->save()){
                    Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Updated');
                    $success='<span>Property Information has been Updated</br>';
                    return compact('success');
                }
                else{
                    Property::setUserLogs('Property '.$request->input('Plotname').' ('.$request->input('Plotcode').') Not Updated');
                    $error='<span>Property Information has not Been Updated</br>';
                    return compact('error');
                }
            }
            else{
                $error='<span>Property is Not Found</br>';
                return compact('error');
            }
            
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingsavederror='1062';
            $beingusederror='1451';
            $error='<span>Property Not Updated</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Property is Being Used</br>';
            }
            if (preg_match("/$beingsavederror/i", $errors)) {
                $error='<span>Property is Already Saved</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>Property Not Updated</br>'.$ex->getMessage();
        }
    }

    public function updateHouse(Request $request)
    {
         $updateHouse = $request->validate([
            'Housename' => ['required', 'string', 'max:150'],
            'Rent' => ['required', 'integer'],
            'Deposit' => ['required', 'integer'],   
            'Kplc' => ['required', 'integer'],
            'Water' => ['required', 'integer'],
            'Lease' => ['required', 'integer'],
            'Garbage' => ['required', 'integer'],
            'DueDay' => ['required', 'integer', 'max:31'],
        ]);
         try { 
            $id=$request->input('hid');
            if($houseinfo = House::find($id)){
                $houseinfo->Housename =$request->input('Housename');
                $houseinfo->Rent =$request->input('Rent');
                $houseinfo->Kplc =$request->input('Kplc');
                $houseinfo->Lease =$request->input('Lease');
                $houseinfo->Garbage =$request->input('Garbage');
                $houseinfo->Water =$request->input('Water');
                $houseinfo->Deposit =$request->input('Deposit');
                $houseinfo->DueDay =$request->input('DueDay');
                if($houseinfo->save()){
                    Property::setUserLogs('House '.Property::getHouseName($id).' Updated');
                    $success='<span>House Information has been Updated</br>';
                    return compact('success');
                }
                else{
                    Property::setUserLogs('House '.Property::getHouseName($id).' Not Updated');
                    $error='<span>House Information has not Been Updated</br>';
                    return compact('error');
                }
            }
            else{
                $error='<span>House is Not Found</br>';
                return compact('error');
            }
            
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingsavederror='1062';
            $beingusederror='1451';
            $error='<span>House Not Updated</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>House is Being Used</br>';
            }
            if (preg_match("/$beingsavederror/i", $errors)) {
                $error='<span>House is Already Saved</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>House Not Updated</br>'.$ex->getMessage();
        }
    }

    
    public function deleteHouse(Request $request)
    {
        try{
            $id=$request->input('delhid');
            if($property = House::find($id)){
                $property->delete();
                $success='<span>House has been Deleted</br>';
                return compact('success');
            }
            else{
                $error='<span>House is Not Found</br>';
                return compact('error');
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error='<span>House Not Deleted</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>House is Occupied</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>House Not Deleted</br>'.$ex->getMessage();
            return compact('error');
        }
    }


    

    

    public function download(){
        // $month='2021 1';
        // $pdf=PDF::loadView('reports.download');
        // $path=public_path('public/aknowledgements');
        // $fileName='P1-JD5_'.$month.'_Aknowledgement_On_'.date('Y n d').'.pdf';
        // $pdf->save($path.'/'.$fileName);
        // return $pdf->download($fileName);

        // return view('reports.download');
        // echo "download Now";
    }


    public function updatebills(){
        
        $propertyinfo = Property::all();
        $paymentbills="";
        $thisproperty='';
        $thismode='';
        $watermonth='';
        $thishouse='';
        $monthinfo= '' ;
        $output='';
        return view('updatebills', compact('propertyinfo','thisproperty','thismode','paymentbills','watermonth','monthinfo','thishouse','output'));   
    }

    public function updatebillsproperty($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);
        $houseinfo=House::where('Plot',$id)->get();
        $watermessage_data= array();
        foreach($houseinfo as $result){
            $hid= $result['id'];
            $watermessages=DB::table('payments')->where([
                'House'=>$hid,
                'Month'=>$watermonth
            ])->get();
            foreach($watermessages as $msga){
                $watermessage_data[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'Tenant' => $msga->Tenant,
                    'Excess' => $msga->Excess,
                    'Arrears' => $msga->Arrears,
                    'Rent' => $msga->Rent,
                    'Garbage' => $msga->Garbage,
                    'Waterbill' => $msga->Waterbill,
                    'Month' => $msga->Month,
                    'created_at' => $msga->created_at
                );
            }
        }
        $paymentbills=$watermessage_data;
        $output='';
        return view('updatebills', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','paymentbills'));   
    }

    public function updatebillspropertydata($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo=Property::getMonthDateAddWater($month);
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);

        // $allpayments=DB::table('payments')->where([
        //     'Month'=>$month])->get();
        // foreach($allpayments as $payment){
        //     $Plot=$payment->Plot;
        //     Payment::where('Plot',$Plot)->update(['Arrears'=>0.00,'Excess'=>0.00]);
        // }

        $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Status']);
        $MessageStatus='';
        $payments= array();
        // foreach($houseinfo as $result){
        //     $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
        //     $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;
        //     $hid= $result['id'];
        //     $housename= $result['Housename'];
        //     $tid=Property::checkCurrentTenant($hid);
        //     if ($tid!="") {
        //         $TenantNames=Property::TenantNames($tid);
        //         $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
        //         $allpayments=DB::table('payments')->where([
        //                     'Tenant'=>$tid,
        //                 'House'=>$hid,
        //                 'Month'=>$watermonth])->get();
        //         foreach($allpayments as $payment){
        //             $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
        //             $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
        //             $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
        //             $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
        //             $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;$Penalty=0;
        //         }
        //     }else{
        //         $TenantNames="Vacant";
        //         $tenantphone="";
        //         $tid="Vacant";
        //     }
        //     $MessageStatus='
        //     <div class="bg-danger m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" >
        //         <i class="text-dark">None</i>
        //     </div>';
        //     // <div class="bg-info m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" title="Notified as Paid">
        //     //     <i class="fa fa-envelope text-white"> Paid</i>
        //     // </div>
        //     // <div class="bg-success m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" title="Message with Payment Details like Rent, Paid and Balance">
        //     //     <i class="fa fa-check"> Details</i>
        //     // </div>
        //     // <div class="bg-danger m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" >
        //     //     <i class="text-dark">None</i>
        //     // </div>
        //     $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
        //     $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;$Penalty=$payment->Penalty;
        //     $Balance=$TotalUsed-$TotalPaid;
        //     $payments[] = array(
        //             'pid' => $id,
        //             'hid' => $hid,
        //             'id' => $hid,
        //             'Tenant'=>$tid,
        //             'tid'=>$tid,
        //             'Tenantname'=>$TenantNames,
        //             'Phone'=>$tenantphone,
        //             'Housename'=>$housename,
        //             'Rent' => $Rent,
        //             'Garbage' => $Garbage,
        //             'KPLC' => $KPLC,
        //             'HseDeposit' => $HseDeposit,
        //             'Water' => $Water,
        //             'Lease' => $Lease,
        //             'Month' => $watermonth,
        //             'Waterbill' => $Waterbill,
        //             'Equity' => $Equity,
        //             'Cooperative' => $Cooperative,
        //             'Others' => $Others,
        //             'Excess' => $Excess,
        //             'Arrears' => $Arrears,
        //             'PaidUploaded' => $PaidUploaded,
        //             'TotalUsed' => $TotalUsed,
        //             'TotalPaid' => $TotalPaid,
        //             'Penalty' => $Penalty,
        //             'MessageStatus'=> $MessageStatus,
        //             'Balance' => $Balance,
        //         );
                
        // }

        $paymentbills=$payments;
        $output='';
        // return compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','paymentbills');
        return view('updatebills', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','paymentbills'));   
    }

    public function updatebillspropertydataload($id,$month){
        
        $propertyinfo = Property::all();
        $thisproperty=Property::findOrFail($id);
        $thismode='';
        $watermonth=$month;
        $monthinfo=Property::getMonthDateAddWater($month);
        $thishouse='';
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);

        // $allpayments=DB::table('payments')->where([
        //     'Month'=>$month])->get();
        // foreach($allpayments as $payment){
        //     $Plot=$payment->Plot;
        //     Payment::where('Plot',$Plot)->update(['Arrears'=>0.00,'Excess'=>0.00]);
        // }

        $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Status']);
        $MessageStatus='';
        $payments= array();
        foreach($houseinfo as $result){
            $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
            $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
            $hid= $result['id'];
            $housename= $result['Housename'];
            $tid=Property::checkCurrentTenant($hid);
            if ($tid!="") {
                $TenantNames=Property::TenantNames($tid);
                $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
                $allpayments=DB::table('payments')->where([
                            'Tenant'=>$tid,
                            'House'=>$hid,
                            'Month'=>$watermonth])->get();
                foreach($allpayments as $payment){
                    $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
                    $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
                    $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
                    $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
                    $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;
                    $Penalty=$payment->Penalty;
                }
            }else{
                $TenantNames="Vacant";
                $tenantphone="";
                $tid="Vacant";
            }

            $SentDatePaymentCompleted=(TenantController::getSentDatePaymentCompleted($id,$tid,$watermonth))?TenantController::getSentDatePaymentCompleted($id,$tid,$watermonth):'';
            $SentDatePaymentSummary=(TenantController::getSentDatePaymentSummary($id,$tid,$watermonth))?TenantController::getSentDatePaymentSummary($id,$tid,$watermonth):'';
            if($SentDatePaymentCompleted){
                $MessageStatus='
                    <div class="bg-info m-1 mt-0 p-1" style="font-size: 8px;border-radius:5px;" title="Notified as Paid">
                        <i class="fa fa-envelope text-white"> '.$SentDatePaymentCompleted.'</i>
                    </div>';
            }
            elseif($SentDatePaymentSummary){
                $MessageStatus='
                    <div class="bg-success m-1 mb-0 p-1" style="font-size: 8px;border-radius:5px;" title="Message with Payment Details like Rent, Paid and Balance">
                        <i class="fa fa-envelope text-white"> '.$SentDatePaymentSummary.'</i>
                    </div>';
            }
            else{
                $MessageStatus='
                <div class="m-1 mb-0 p-1" style="font-size: 8px;border-radius:5px;" >
                    <i class="text-black">Not Yet</i>
                </div>';
            }
            
            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears+$Penalty;
            $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
            $Balance=$TotalUsed-$TotalPaid;
            $payments[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'id' => $hid,
                    'Tenant'=>$tid,
                    'tid'=>$tid,
                    'Tenantname'=>$TenantNames,
                    'Phone'=>$tenantphone,
                    'Housename'=>$housename,
                    'Rent' => $Rent,
                    'Garbage' => $Garbage,
                    'KPLC' => $KPLC,
                    'HseDeposit' => $HseDeposit,
                    'Water' => $Water,
                    'Lease' => $Lease,
                    'Month' => $watermonth,
                    'Waterbill' => $Waterbill,
                    'Equity' => $Equity,
                    'Cooperative' => $Cooperative,
                    'Others' => $Others,
                    'Excess' => $Excess,
                    'Arrears' => $Arrears,
                    'PaidUploaded' => $PaidUploaded,
                    'TotalUsed' => $TotalUsed,
                    'TotalPaid' => $TotalPaid,
                    'Penalty' => $Penalty,
                    'MessageStatus'=> $MessageStatus,
                    'Balance' => $Balance,
                );
                
        }

        $paymentbills=$payments;
        $output='';
        return compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','paymentbills');
        // return view('updatebills', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','paymentbills'));   
    }

    public function updatebillspropertydatahse($id,$month,$hid){
        
        $thismode='';
        $watermonth=$month;
        $monthinfo= '' ;
        $thishouse=$hid;
        $monthdate= $this->getLastMonthdate($month);
        $lastmonth= $this->getLastMonth($month,$monthdate);

        // $houseinfo=House::where('Plot',$id)->get(['id','Plot','Housename','Status']);

        $payments= array();
        // foreach($houseinfo as $result){
            $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
            $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
            $MessageStatus='';

            $housename= Property::getHouseName($hid);
            $tid=Property::checkCurrentTenant($hid);
            if ($tid!="") {
                $TenantNames=Property::TenantNames($tid);
                $tenantphone='+254'.substr($this->getTenantPhone($tid), 0);
                $allpayments=DB::table('payments')->where([
                            'Tenant'=>$tid,
                        'House'=>$hid,
                        'Month'=>$watermonth])->get();
                foreach($allpayments as $payment){
                    $Rent=$payment->Rent;$Water=$payment->Water;$Garbage=$payment->Garbage;
                    $Lease=$payment->Lease;$HseDeposit=$payment->HseDeposit;$KPLC=$payment->KPLC;
                    $Waterbill=$payment->Waterbill;$Arrears=$payment->Arrears;
                    $Excess=$payment->Excess;$Equity=$payment->Equity;$Cooperative=$payment->Cooperative;
                    $Others=$payment->Others;$PaidUploaded=$payment->PaidUploaded;$Penalty=$payment->Penalty;
                }
            }else{
                $TenantNames="Vacant";
                $tenantphone="";
                $tid="Vacant";
            }
            // $MessageStatus='
            // <span class="m-1 mb-0 p-1" style="font-size: 10px;border-radius:5px;" title="Notified as Paid">
            //     <i class="fa fa-envelope text-olive"> Message sent as Paid</i>
            // </span>';
            // $MessageStatus='
            // <span class="m-1 mb-0 p-1" style="font-size: 10px;border-radius:5px;" title="Message with Payment Details like Rent, Paid and Balance">
            //     <i class="fa fa-envelope text-info"> Message sent with Payment Details</i>
            // </span>';
            $MessageStatus='
            <span class="m-1 mb-0 p-1" style="font-size: 10px;border-radius:5px;" >
                <i class="text-danger">None</i>
            </span>';
            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
            $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
            $Balance=$TotalUsed-$TotalPaid;
            $payments[] = array(
                    'pid' => $id,
                    'hid' => $hid,
                    'id' => $hid,
                    'Tenant'=>$tid,
                    'tid'=>$tid,
                    'Tenantname'=>$TenantNames,
                    'Phone'=>$tenantphone,
                    'Housename'=>$housename,
                    'Rent' => $Rent,
                    'Garbage' => $Garbage,
                    'KPLC' => $KPLC,
                    'HseDeposit' => $HseDeposit,
                    'Water' => $Water,
                    'Lease' => $Lease,
                    'Month' => $watermonth,
                    'Waterbill' => $Waterbill,
                    'Equity' => $Equity,
                    'Cooperative' => $Cooperative,
                    'Others' => $Others,
                    'Excess' => $Excess,
                    'Arrears' => $Arrears,
                    'PaidUploaded' => $PaidUploaded,
                    'TotalUsed' => $TotalUsed,
                    'TotalPaid' => $TotalPaid,
                    'Penalty' => $Penalty,
                    'MessageStatus'=> $MessageStatus,
                    'Balance' => $Balance,
                );
                
        // }

        $paymentbills=$payments;
        $output='';
        return compact('watermonth','thishouse','paymentbills');
        // return view('updatebills', compact('propertyinfo','thisproperty','thismode','watermonth','monthinfo','houseinfo','thishouse','output','paymentbills'));   
    }

    

    public function updateerrors(){
        
        
        // $houseinfo=Payment::where('Month','2021 6')->get();
        // return json_encode($houseinfo);
        // dd($houseinfo);

        // $watermessage_data= array();
        // foreach($houseinfo as $result){
        //     $House=$result['House'];
        //     $Tenant=$result['Tenant'];
        //     $Waterbill=$result['Waterbill'];
        //     $id=$result['id'];

        //    $pid=Property::getHouseProperty($House);
        //     if ($pid==9) {
        //         // echo $pid;
        //         // $watermessages=DB::table('payments')->where([
        //         //     'House'=>$House,
        //         //     'Tenant'=>$Tenant,
        //         //     'Month'=>'2021 5'
        //         // ])->get(['id','Waterbill']);
        //         // echo $watermessages.' '. $id.' '.$Waterbill.'<br>';

        //         // foreach($watermessages as $msga){
        //         //     $aid= $msga->id;
        //         //     $payments = Payment::findOrFail($aid);
        //         //     $payments->Waterbill=$Waterbill;
        //         //     $payments->save();
        //         //     echo $payments.'<br>';
        //         // }

        //         $payments = Payment::findOrFail($id);
        //         $payments->Month='2021 5';
        //         $payments->save();
        //         // $deletepayment = Payment::findOrFail($id);
        //         // $deletepayment->delete();
        //     }
            
        // }
    }

    public function frequentlyasked()
    {
        
        Property::setUserLogs('Viewing frequentlyasked');
        $propertyinfo = Property::all();
        return view('frequentlyasked',compact('propertyinfo'));
    }

    public function getBlacklistedDetails()
    {
        $blacklisted=DB::table('blacklisteds')->get();

        // foreach ($blacklisted as $blacked) {
        //     $id=$blacked->id;
        //     $phone_tenant=$blacked->Phone;
        //     $phone=str_replace("254", '', $phone_tenant);
        //     $tenantsinfo=DB::table('tenants')->where([
        //             'Phone'=>$phone
        //         ])->get();
        //     $tenant_name='';
        //     $tenant_id='';
        //         foreach($tenantsinfo as $tenant){
        //            $tenant_name= $tenant->Fname.' '.$tenant->Oname;
        //            $tenant_id= $tenant->id;
        //         }
        //     $house_id=Property::checkCurrentTenantHouse($tenant_id);
        //     $house_name=Property::getHouseName($house_id);
        //     // echo $phone.' '.$tenant_name.' '.$house_name.'<br>';
        //     Blacklisted::where('id',$id)->update(['Tenant'=>$tenant_name]);
        // }
        // $blacklisted=DB::table('blacklisteds')->get();
        return $blacklisted;
        // $phone=str_replace("\r\n", '<br>', $msg);
    }
    
}