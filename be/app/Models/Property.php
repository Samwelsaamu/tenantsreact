<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;
use App\Models\House;
use App\Models\Tenant;
use App\Models\UserLogs;
use App\Models\User;
use App\Models\Report;
use App\Models\Mails;
use App\Models\Message;
use App\Models\PaymentMessage;
use App\Models\WaterMessage;
use App\Models\WaterMessagesOthers;
use App\Models\Propertyhousetype;
use Illuminate\Support\Facades\Auth;


use Carbon\Carbon;

class Property extends Model
{
    use HasFactory;

    protected $fillable=[
    	'Plotname',
		'Plotarea',
		'Plotcode',
		'Plotaddr',
		'Plotdesc',
		'Waterbill',
		'Deposit',
		'Waterdeposit',
		'Outsourced',
		'Garbage',
		'Kplcdeposit',
    ];

    public function getIdAttribute($value){	
		return Property::encryptText($value);
	}

    public static function getMailSubject($id){
        $results = Mails::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['subject'];
            }
        return $resultname;
    }

    public static function getUsername($id){
        $results = User::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['email'];
            }
        return $resultname;
    }

    public static function getProfilename($id){
        $results = User::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Fullname'];
            }
        return $resultname;
    }

    public static function getHousePropertyID($id){
        $results = House::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['plot'];
            }
        return $resultname;
    }

    public static function getPropertyHidID($id){
        $results = Property::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['id'];
            }
        return $resultname;
    }

    public static function getPropertyName($id){
        $results = Property::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Plotname'];
            }
        return $resultname;
    }

    public static function getPropertyCode($id){
        $results = Property::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Plotcode'];
            }
        return $resultname;
        
    }

    // public static function getPropertyCode($id){
    //     $currentlyassigned=DB::table('properties')->where([
    //         'id'=>Property::decryptText($id)
    //     ])->max('Plotcode');
    //     return $currentlyassigned;
    // }

    public static function getHouseProperty($id){
        $results = House::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['plot'];
            }
        return $resultname;
    }

    public static function getHouseName($id){
        $results = House::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Housename'];
            }
        return $resultname;
    }

    public static function getHouseStatus($id){
        $results = House::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Status'];
            }
        return $resultname;
    }

    public static function getHouseCode($housename){
        $results = House::where('Housename',$housename)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['id'];
            }
        return $resultname;
    }

    public static function getHousePlotUploaded($housename){
        $results = House::where('Housename',$housename)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['plot'];
            }
        return $resultname;
    }

    public static function tenantStatus($id){
       $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Status'];
            }
        return $resultname;
    }

    public static function getMonthDateFull($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'F Y');
        return $month;
    }

    public static function getMonthPaymentDateShort($yearmonth){
        // $explomonth=explode(' ', $yearmonth);
        // $years=$explomonth[0];
        // $months=$explomonth[1];
        // $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonth),'d/m');
        return $month;
    }

    public static function getMonthWaterDate($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y F');
        return $month;
    }


    public static function getMonthDateAddWater($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y, M');
        return $month;
    }

    public static function getMonthDateAddWaterP($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y M');
        return $month;
    }

    public static function getMonthDateMonthPrevious($yearmonth){
        $month='';
        if($yearmonth=='0000-00-00' || $yearmonth==null || $yearmonth==''){
            $month='';
        }
        else{
            $explomonth=explode(' ', $yearmonth);
            $years=$explomonth[0];
            $months=$explomonth[1];
            $yearmonthday=$years.'-'.$months.'-1';
            $month=date_format(date_create($yearmonthday),'M, Y');
        }
        return $month;
    }

    public static function getMonthDateDash($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'M');
        return $month;
    }
    public static function getYearDateDash($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y');
        return $month;
    }

    public static function PatchNo(){
        $nextpatch=DB::table('payment_messages')->max('PatchNo');
        if($nextpatch){
            return $nextpatch++;
        }
        else{
            return 1;
        }
    }

    public static function checkCurrentTenant($hid){
        $currentlyassigned=DB::table('agreements')->where([
            'House'=>$hid,
            'Month'=>0
        ])->max('tenant');
        return $currentlyassigned;
    }

    public static function checkCurrentTenantWithMonth($hid,$month){
        // $currentlyassigned=DB::table('agreements')->where([
        //     'House'=>$hid,
        //     'Month'=>0
        // ])->max('tenant');

        $currentlyassigned=Agreement::query()
                    ->where('house','=',$hid)
                    ->where('Month','=',0)
                    ->where('MonthAssigned','>=',$month)->max('tenant');

        return $currentlyassigned;
    }

    public static function checkCurrentTenantMonthAssigned($hid){
        $currentlyassigned=DB::table('agreements')->where([
            'House'=>$hid,
            'Month'=>0
        ])->max('MonthAssigned');

        // $currentlyassigned=Agreement::query()
        //             ->where('house','=',$hid)
        //             ->where('Month','=',0)
        //             ->where('MonthAssigned','>=',$month)->max('tenant');

        return $currentlyassigned;
    }

    public static function checkCurrentTenantMonthAssignedTenant($hid){
        $currentlyassigned=DB::table('agreements')->where([
            'House'=>$hid,
            'Month'=>0
        ])->max('tenant');
        return $currentlyassigned;
    }
    
    
    public static function getPropertyTypeName($id){
        $results = Propertyhousetype::where('id',$id)->get();
        $resultid='';
            foreach($results as $result){
               $resultid= $result['typename'];
            }
        return $resultid;
    }

    public static function checkCurrentTenantID($tid){
        $results = Tenant::where('id',$tid)->get();
        $resultid='';
            foreach($results as $result){
               $resultid= $result['id'];
            }
        return $resultid;
    }


    
    public static function checkCurrentTenantHouse($tid){
        $currentlyassigned=DB::table('agreements')->where([
            'tenant'=>$tid,
            'Month'=>0
        ])->max('house');
        return $currentlyassigned;
    }

    

    public static function checkCurrentTenantHouseDateAssigned($aid){
        $currentlyassigned=DB::table('agreements')->where([
            'id'=>$aid
        ])->max('DateAssigned');
        return $currentlyassigned;
    }

    public static function checkCurrentTenantName($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Fname'].' '.$result['Oname'];
            }
        return $resultname;
    }

    public static function checkCurrentTenantFName($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Fname'];
            }
        return $resultname;
    }

    public static function checkCurrentTenantNamePayment($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='Vacant';
            foreach($results as $result){
               $resultname= $result['Fname'].' '.$result['Oname'];
            }
        return $resultname;
    }

    public static function checkCurrentTenantFNamePayment($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='Vacant';
            foreach($results as $result){
               $resultname= $result['Fname'];
            }
        return $resultname;
    }

    public static function checkCurrentTenantBill($hid,$tenant,$month){
        $waters=DB::table('waters')->where([
            'Tenant'=>$tenant,
            'House'=>$hid,
            'Month'=>$month
        ])->max('id');
        return $waters;
    }

    public static function checkCurrentHouseBill($hid,$month){
        $waters=DB::table('waters')->where([
            'House'=>$hid,
            'Month'=>$month
        ])->max('id');
        return $waters;
    }

    public static function checkCurrentTenantBillPayment($pid,$hid,$tenant,$month){
        $waters=DB::table('payments')->where([
            'Plot'=>$pid,
            'Tenant'=>$tenant,
            'House'=>$hid,
            'Month'=>$month
        ])->max('id');
        return $waters;
    }

    public static function checkTenantBillPaymentStatus($pid,$hid,$tenant,$month,$excess,$arrears,$rent,$garbage,$water,$others,$payment){
        $results=DB::table('payments')->where([
            'Plot'=>$pid,
            'Tenant'=>$tenant,
            'House'=>$hid,
            'Month'=>$month
        ])->get();
        $resultname=false;
        foreach($results as $result){
            if($result->Excess != $excess){
                $resultname=true;
            }
            elseif($result->Arrears != $arrears){
                $resultname=true;
            }
            elseif($result->Rent != $rent){
                $resultname=true;
            }
            elseif($result->Garbage != $garbage){
                $resultname=true;
            }
            elseif($result->Waterbill != $water){
                $resultname=true;
            }
            elseif($result->Others != $others){
                $resultname=true;
            }
            elseif($result->PaidUploaded != $payment){
                $resultname=true;
            }
            else{
                $resultname=false;
            }
        }
        return $resultname;
    }

    public static function checkCurrentTenantPreviousBill($hid,$tenant,$month){
        $waters=DB::table('waters')->where([
            'Tenant'=>$tenant,
            'House'=>$hid,
            'Month'=>$month
        ])->max('Previous');
        return $waters;
    }

    public static function checkCurrentTenantCurrentBill($hid,$tenant,$month){
        $waters=DB::table('waters')->where([
            'Tenant'=>$tenant,
            'House'=>$hid,
            'Month'=>$month
        ])->max('Current');
        return $waters;
    }

    public static function setUserLogs($message){
        $id=Auth::user()->id;
        $savelog = new UserLogs;
        $savelog->User =$id;
        $savelog->Message =$message;
        $savelog->save();
    }

    public static function setLogs($message){
        $savelog = new UserLogs;
        $savelog->User ='System';
        $savelog->Message =$message;
        $savelog->save();
    }

    public static function PaymentKPLC($id,$hid,$month){
        $KPLC=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('KPLC');
        return $KPLC;
    }

    public static function PaymentWater($id,$hid,$month){
        $Water=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Water');
        return $Water;
    }

    public static function PaymentId($id,$hid,$month){
        $pid=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->max('id');
        return $pid;
    }

    public static function PaymentExcess($id,$hid,$month){
        $Excess=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Excess');
        return $Excess;
    }

    public static function PaymentArrears($id,$hid,$month){
        $Arrears=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Arrears');
        return $Arrears;
    }

    public static function PaymentRent($id,$hid,$month){
        $Rent=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Rent');
        return $Rent;
    }

    public static function PaymentGarbage($id,$hid,$month){
        $Garbage=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Garbage');
        return $Garbage;
    }

    public static function PaymentWaterbill($id,$hid,$month){
        $Waterbill=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Waterbill');
        return $Waterbill;
    }
    public static function PaymentHseDeposit($id,$hid,$month){
        $HseDeposit=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('HseDeposit');
        return $HseDeposit;
    }

    public static function PaymentDeposit($id,$hid,$month){
        $KPLC=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('KPLC');

        $HseDeposit=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('HseDeposit');

        $Water=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Water');

        $Deposit=$KPLC+$HseDeposit+$Water;
        return $Deposit;
    }

    public static function PaymentLease($id,$hid,$month){
        $Lease=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Lease');
        return $Lease;
    }

     public static function PaymentTotals($id,$hid,$month){
        $Arrears=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Arrears');

        $Excess=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Excess');

        $Rent=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Rent');

        $Garbage=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Garbage');

        $KPLC=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('KPLC');

        $HseDeposit=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('HseDeposit');

        $Water=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Water');

        $Lease=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Lease');

        $Waterbill=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Waterbill');

        $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
        return $TotalUsed;
    }
    
    public static function PaymentPaid($id,$hid,$month){
        $Excess=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Excess');

        $Equity=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Equity');

        $Cooperative=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Cooperative');

        $Others=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Others');

        $PaidUploaded=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('PaidUploaded');
        $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
        return $TotalPaid;
    }

    public static function PaymentBal($id,$hid,$month){
        $Arrears=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Arrears');

        $Excess=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Excess');

        $Rent=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Rent');

        $Garbage=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Garbage');

        $KPLC=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('KPLC');

        $HseDeposit=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('HseDeposit');

        $Water=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Water');

        $Lease=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Lease');

        $Waterbill=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Waterbill');

        $Equity=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Equity');

        $Cooperative=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Cooperative');

        $Others=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('Others');

        $PaidUploaded=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>$month
        ])->sum('PaidUploaded');
        $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
        $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
        $Balance=$TotalUsed-$TotalPaid;
        return $Balance;
    }

    public static function TenantBalance($id,$hid){
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

    public static function MonthlyRent($id,$month){
        $Rent=0.00;
        if($id=="all"){
            $Rent=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Rent');
        }
        else{
            $Rent=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Rent');
        }
        return $Rent;
    }

    public static function MonthlyGarbage($id,$month){
        $Garbage=0.00;
        if($id=="all"){
            $Garbage=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Garbage');
        }
        else{
            $Garbage=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Garbage');
        }
        return $Garbage;
    }

    public static function MonthlyWaterbill($id,$month){
        // return $month;
        $bills=0.00;
        if($id=="all"){
            $Waterbills=DB::table('payments')->where([
                'Month'=>$month
            ])->get('Waterbill');
            foreach ($Waterbills as $bill) {
                $Waterbill=($bill->Waterbill >0 ?$bill->Waterbill:0.00);
                $bills=$bills+$Waterbill;
                $bills=round($bills,2);
            }
        }
        else{
            $Waterbills=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month,
            ])->get('Waterbill');
            foreach ($Waterbills as $bill) {
                $Waterbill=($bill->Waterbill >0 ?$bill->Waterbill:0.00);
                $bills=$bills+$Waterbill;
                $bills=round($bills,2);
            }
        }
        return $bills;
    }

    public static function MonthlyWaterbillTest($id,$month){
        // $Waterbill=0.00;
        // if($id=="all"){
        //     $Waterbill=Payment::query()
        //         ->where('Month','=',$month)
        //         ->where('Waterbill','>','0')->sum('Waterbill');
        // }
        // else{
        //     $Waterbill=Payment::query()
        //         ->where('Plot','=',$id)
        //         ->where('Month','=',$month)
        //         ->where('Waterbill','>','0')->sum('Waterbill');
        // }
        $bills=0.00;
        // $bills= array();
        if($id=="all"){
            $Waterbills=DB::table('payments')->where([
                'Month'=>$month
            ])->get('Waterbill');
            foreach ($Waterbills as $bill) {
                $Waterbill=($bill->Waterbill >0 ?$bill->Waterbill:0.00);
                $bills=$bills+$Waterbill;
                $bills=round($bills,2);
                // $bills[] = array(
                //     'bill'=>$Waterbill,
                // );
            }
            // $Waterbill=$Waterbill[0];
        }
        else{
            $Waterbills=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month,
            ])->get('Waterbill');
            foreach ($Waterbills as $bill) {
                $Waterbill=($bill->Waterbill >0 ?$bill->Waterbill:0.00);
                $bills=$bills+$Waterbill;
                $bills=round($bills,2);
                // $bills[] = array(
                //     'bill'=>$Waterbill,
                // );
            }
        }
        return $bills;
    }

    public static function MonthlyLease($id,$month){
        $Lease=0.00;
        if($id=="all"){
            $Lease=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Lease');
        }
        else{
            $Lease=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Lease');
        }
        return $Lease;
    }

    public static function MonthlyKPLC($id,$month){
        $KPLC=0.00;
        if($id=="all"){
            $KPLC=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('KPLC');
        }
        else{
            $KPLC=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('KPLC');
        }
        return $KPLC;
    }

    public static function MonthlyHseDeposit($id,$month){
        $HseDeposit=0.00;
        if($id=="all"){
            $HseDeposit=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('HseDeposit');
        }
        else{
            $HseDeposit=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('HseDeposit');
        }
        return $HseDeposit;
    }

    public static function MonthlyWater($id,$month){
        $Water=0.00;
        if($id=="all"){
            $Water=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Water');
        }
        else{
            $Water=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Water');
        }
        return $Water;
    }

    public static function MonthlyArrears($id,$month){
        $Arrears=0.00;
        if($id=="all"){
            $Arrears=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Arrears');
        }
        else{
            $Arrears=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Arrears');
        }
        return $Arrears;
    }

    public static function MonthlyExcess($id,$month){
        $Excess=0.00;
        if($id=="all"){
            $Excess=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Excess');
        }
        else{
            $Excess=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Excess');
        }
        return $Excess;
    }

    public static function MonthlyRefund($id,$month){
        $Refund=0.00;
        if($id=="all"){
            $Refund=DB::table('agreements')->where([
                'Month'=>$month
            ])->sum('Refund');
        }
        else{
            $Refund=DB::table('agreements')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Refund');
        }
        return $Refund;
    }

    public static function MonthlyBilled($id,$month){
        $TotalUsed=0.00;
        if($id=="all"){
            $Rent=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Rent');

            $Garbage=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Garbage');

            $KPLC=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('KPLC');

            $HseDeposit=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('HseDeposit');

            $Water=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Water');

            $Lease=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Lease');

            $Waterbill=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Waterbill');

            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill;
        }
        else{
            $Rent=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Rent');

            $Garbage=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Garbage');

            $KPLC=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('KPLC');

            $HseDeposit=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('HseDeposit');

            $Water=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Water');

            $Lease=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Lease');

            $Waterbill=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Waterbill');

            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill;
        }

        return $TotalUsed;
    }
    
    public static function MonthlyPaid($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Equity=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Equity');

            $Cooperative=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Cooperative');

            $Others=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Others');

            $PaidUploaded=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('PaidUploaded');
            $TotalPaid=$Equity+$Cooperative+$Others+$PaidUploaded;
        }
        else{
            $Equity=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Equity');

            $Cooperative=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Cooperative');

            $Others=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Others');

            $PaidUploaded=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('PaidUploaded');
            $TotalPaid=$Equity+$Cooperative+$Others+$PaidUploaded;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidEquity($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Equity=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Equity');
            $TotalPaid=$Equity;
        }
        else{
            $Equity=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Equity');
            $TotalPaid=$Equity;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidCoop($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Cooperative=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Cooperative');

            $TotalPaid=$Cooperative;
        }
        else{
            
            $Cooperative=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Cooperative');

            $TotalPaid=$Cooperative;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidOthers($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Others=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Others');
            
            $TotalPaid=$Others;
        }
        else{
            $Others=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Others');

            $TotalPaid=$Others;
        }
        return $TotalPaid;
    }



    public static function MonthlyPaidKCB($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $KCB=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('KCB');

            $TotalPaid=$KCB;
        }
        else{
            
            $KCB=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('KCB');

            $TotalPaid=$KCB;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidMPesa($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $MPesa=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('MPesa');

            $TotalPaid=$MPesa;
        }
        else{
            
            $MPesa=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('MPesa');

            $TotalPaid=$MPesa;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidCash($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Cash=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Cash');

            $TotalPaid=$Cash;
        }
        else{
            
            $Cash=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Cash');

            $TotalPaid=$Cash;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidCheque($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Cheque=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Cheque');

            $TotalPaid=$Cheque;
        }
        else{
            
            $Cheque=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Cheque');

            $TotalPaid=$Cheque;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidPenalty($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $Penalty=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Penalty');

            $TotalPaid=$Penalty;
        }
        else{
            
            $Penalty=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Penalty');

            $TotalPaid=$Penalty;
        }
        return $TotalPaid;
    }

    public static function MonthlyPaidUploaded($id,$month){
        $TotalPaid=0.00;
        if($id=="all"){
            $PaidUploaded=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('PaidUploaded');
            $TotalPaid=$PaidUploaded;
        }
        else{
            $PaidUploaded=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('PaidUploaded');
            $TotalPaid=$PaidUploaded;
        }
        return $TotalPaid;
    }

    public static function MonthlyBalance($id,$month){
        $Balance=0.00;
        if($id=="all"){
            $Arrears=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Arrears');

            $Excess=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Excess');

            $Rent=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Rent');

            $Garbage=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Garbage');

            $KPLC=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('KPLC');

            $HseDeposit=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('HseDeposit');

            $Water=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Water');

            $Lease=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Lease');

            $Waterbill=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Waterbill');

            $Equity=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Equity');

            $Cooperative=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Cooperative');

            $Others=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('Others');

            $PaidUploaded=DB::table('payments')->where([
                'Month'=>$month
            ])->sum('PaidUploaded');
            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
            $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
            $Balance=$TotalUsed-$TotalPaid;
        }
        else{
            $Arrears=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Arrears');

            $Excess=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Excess');

            $Rent=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Rent');

            $Garbage=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Garbage');

            $KPLC=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('KPLC');

            $HseDeposit=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('HseDeposit');

            $Water=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Water');

            $Lease=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Lease');

            $Waterbill=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Waterbill');

            $Equity=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Equity');

            $Cooperative=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Cooperative');

            $Others=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('Others');

            $PaidUploaded=DB::table('payments')->where([
                'Plot'=>$id,
                'Month'=>$month
            ])->sum('PaidUploaded');
            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears;
            $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
            $Balance=$TotalUsed-$TotalPaid;
        }
        return $Balance;
    }

    public static function getLastMonth($month,$monthdate){
        $watermonthlast= date("Y n",strtotime("-1 months",strtotime($monthdate)));
        return $watermonthlast;
    }

    // public static function getNextMonth($monthdate){
    //     $watermonthlast= date("Y n",strtotime("+1 months",strtotime($monthdate)));
    //     return $watermonthlast;
    // }

    public static function getWaterLastMonthdate($watermonth){
        $explomonth=explode(' ', $watermonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y n');
        return $month;
    }

    public static function getLastMonthdate($watermonth){
        $explomonth=explode(' ', $watermonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y-m-01');
        return $month;
    }

    public static function getPaymentUpdateId($id,$month){
        $Payments=DB::table('payments')->where([
            'House'=>$id,
            'Month'=>$month
        ])->max('id');
        return $Payments;
    }

    public static function getOnlineStatus($id){
        if (Auth::check()) {
            return $id==Auth::user()->id;
        }
    }

    public static function getCountHousesForProperty($id){
        $houses=DB::table('houses')->where([
            'Plot'=>$id
        ])->count();
        return $houses;
    }

    public static function getCountTenantsForHouses($id){
        $houses=DB::table('agreements')->where([
            'House'=>$id
        ])->count();
        return $houses;
    }

    public static function getMailIDSaved($uid){
        $mails=DB::table('mails')->where([
            'mailid'=>$uid
        ])->count();
        return $mails;
    }

    public static function saveReport($type,$fileName,$tid){
        $savereport = new Report;
        $savereport->DateTrans =date('Y-m-d');
        $savereport->Filename =$fileName;
        $savereport->Type =$type;
        $savereport->ReportTo =$tid;
        $savereport->save();
    }

    public static function checkDocumentIfFound($document){
        $results = Report::where('Filename',$document)->get();
        $resultname=false;
            foreach($results as $result){
               $resultname= true;
            }
        return $resultname;
    }

    public static function dateToMonthName($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y_M');
        return $month;
    }

    public static function dateToMonthNameMonth($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'M');
        return $month;
    }

    public static function dateToMonthNameValue($yearmonth){
        $month=date_format(date_create($yearmonth),'d M, Y');
        return $month;
    }


    public static function TenantNames($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Fname'].' '.$result['Oname'];
            }
        return $resultname;
    }

    public static function TenantFNames($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Fname'];
            }
        return $resultname;
    }

    public static function CatchQueryException($error){
            $networkerror='cURL error 6:';
            $errorMessage='Please Try Again';
            if (preg_match("/$networkerror\/|$networkerror\s/i", $error, $match)) {
                return redirect("/properties/messages")->with('dbError','Network Error');
            }  
    }


    public static function getTotalBillsHse($id,$month){
        $Totals=DB::table('payments')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->count();
        return $Totals;
    }

    public static function getTotalWaterBillsHse($id,$month){
        $Totals=Payment::query()
            ->where('Plot','=',$id)
            ->where('Month','=',$month)
            ->where('Waterbill','>','0')
        ->count();
        return $Totals;
    }

    public static function getTotalWaterMsgHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('water_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            $Totals=$Totals+$total;
        }
        return $Totals;
    }

    public static function getTotalWaterMsgSentOnceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('water_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            if($total==1){
                $Totals++;
            }
        }
        return $Totals;
    }

    public static function getTotalWaterMsgSentTwiceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('water_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            if($total==2){
                $Totals++;
            }
        }
        return $Totals;
    }

    public static function getTotalWaterMsgSentThriceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('water_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            if($total==3){
                $Totals++;
            }
        }
        return $Totals;
    }

    public static function getTotalBillsMsgHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('payment_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            $Totals=$Totals+$total;
        }
        return $Totals;
    }

    public static function getTotalBillsMsgSentOnceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('payment_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            if($total==1){
                $Totals++;
            }
        }
        return $Totals;
    }

    public static function getTotalBillsMsgSentTwiceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('payment_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            if($total==2){
                $Totals++;
            }
        }
        return $Totals;
    }

    public static function getTotalBillsMsgSentThriceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=Property::decryptText($house->id);
            $total=DB::table('payment_messages')->where([
                'House'=>$hse,
                'Month'=>$month
            ])->count();
            if($total==3){
                $Totals++;
            }
        }
        return $Totals;
    }

    public static function getTotalHousesHse($id){
        $Totals=DB::table('houses')->where([
            'Plot'=>$id
        ])->count();
        return $Totals;
    }

    public static function getTotalHousesOccupied($id){
        $Totals=DB::table('houses')->where([
            'Plot'=>$id,
            'Status'=>'Occupied'
        ])->count();
        return $Totals;
    }

    public static function getTotalTenantsHse($id){
        $allhousesinfo = House::where('Plot',$id)->get();
        $alltenantscount = 0;
        $alltenantsdouble = 0;
        $alltenantscountall = 0;
        foreach ($allhousesinfo as $housesinfo) {
            $houseid=$housesinfo->id;
            $tenantid=Property::checkCurrentTenant($houseid);
            $totalhouses=Property::tenantHousesAssigned($tenantid);
            
            if($totalhouses >0 ){
                $alltenantscountall++;
                if($totalhouses >1 ){
                    $alltenantsdouble++;
                }
            }
        }
        $alltenantscount=$alltenantscountall - ($alltenantsdouble);
        return $alltenantscount;
    }

    public static function getTotalUnits($id,$month){
        $Units=DB::table('waters')->where([
            'plot'=>$id,
            'Month'=>$month
        ])->sum('Units');
        return $Units;
    }

    public static function getTotalTotal($id,$month){
        $Total=DB::table('waters')->where([
            'plot'=>$id,
            'Month'=>$month
        ])->sum('Total');
        return $Total;
    }

    public static function getTotalTotal_OS($id,$month){
        $Total_OS=DB::table('waters')->where([
            'plot'=>$id,
            'Month'=>$month
        ])->sum('Total_OS');
        return $Total_OS;
    }

    public static function getMonthDate($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'M, Y');
        return $month;
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

    public static function getHouseAgreement($hid){
        $houseshere= Agreement::where('House',$hid)->where('Month',0)->get();
        $housesassignedcount=0;
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                $housesassignedcount++;
            }
        }
        return $housesassignedcount;
    }

    public static function tenantHousesOccupied($id,$hid){
        $houseshere= Agreement::where('Tenant',$id)->where('Month',0)->get();
        $output='';
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                if(Property::decryptText($houses->house) != $hid){
                    $output.= Property::getHouseName(Property::decryptText($houses->house))."\t ";
                }
            }
        }
        return $output;
    }

    public static function tenantHousesOccupiedDataOnly($id){
        $houseshere= Agreement::where('tenant',$id)->where('Month',0)->get();
        $housesdata= array();
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                $housesdata[] = array(
                    'hid' =>        $houses->house,
                    'house' =>      Property::decryptText($houses->house),
                    'pid' =>        $houses->plot,
                    'Housename' =>  Property::getHouseName(Property::decryptText($houses->house))
                );
                // $output.="<Link to='/properties/house/".$houses->House."/".$houses->Plot."'>".Property::getHouseName($houses->House)."</Link>";
            }
        }
        return $housesdata;
    }

    public static function tenantHousesOccupiedDataOnlyHid($id,$hid){
        $houseshere= Agreement::where('tenant',$id)->where('Month',0)->get();
        $housesdata= array();
        $hids=Property::decryptText($hid);
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                if($houses->House == $hids){
                    $housesdata[] = array(
                        'hid' =>        $hid,
                        'pid' =>        $houses->plot,
                        'Housename' =>  Property::getHouseName(Property::decryptText($houses->house))
                    );
                }
                else{
                    $housesdata[] = array(
                        'hid' =>        $houses->house,
                        'pid' =>        $houses->plot,
                        'Housename' =>  Property::getHouseName(Property::decryptText($houses->house))
                    );
                }
                // $output.="<Link to='/properties/house/".$houses->House."/".$houses->Plot."'>".Property::getHouseName($houses->House)."</Link>";
            }
        }
        return $housesdata;
    }

    public static function tenantHousesOccupiedOnly($id){
        $houseshere= Agreement::where('tenant',$id)->where('Month',0)->get();
        // return $houseshere;
        $output='';
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                $output.= Property::getHouseName(Property::decryptText($houses->house)).", ";
            }
        }
        return $output;
    }

    

    public static function getTenantPhone($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Phone'];
            }
        return $resultname;
    }

    public static function getTenantIdUsingPhone($phone){
        $res1=substr($phone,3,13);
        // return $res1;
        $results = Tenant::where('Phone',$res1)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= Property::decryptText($result['id']);
            }
        return $resultname;
    }

    public static function getTenantPhoneMask($phone){
        // $res=str_repeat("*",strlen($phone)-3) . substr($phone,-3);
        $res1=substr($phone,0,3);
        $res='****';
        $res2=substr($phone,-2);
        return $res1.$res.$res2;
    }

    public static function getMessageMask($message){
        // $res=str_repeat("*",strlen($phone)-3) . substr($phone,-3);
        $msg_length=strlen($message);

        $allwords=explode(" ", $message);
        $words_count = count($allwords);
        if($words_count >3 ){
            $res1 = $allwords[0];
            $res='****';
            $res2 = $allwords[$words_count-1];
            return $res1.' '.$res.' '.$res2;
        }
        else{
            return $message;
        }

        // return count($allwords);
        // return count($eachword);

        // if($msg_length >15)
        // $res1=substr($message,0,10);
        // $res='****';
        // $res2=substr($message,-2);
        // return $res1.$res.$res2;
    }

    public static function getMessageFormated($message){
        $new_message=str_replace(";", '; ', $message);
        return $new_message;
    }
    

    public static function getTenantIDNoMask($idno){
        $res1=substr($idno,0,2);
        $res='****';
        $res2=substr($idno,-2);
        return $res1.$res.$res2;
    }

    public static function getTenantFname($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Fname'];
            }
        return $resultname;
    }

    public static function getTenantOname($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Oname'];
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

    public static function getTenantStatus($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Status'];
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

    public static function getTenantCReatedAt($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['created_at'];
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
    public static function getAgreementIds($pidfrom,$fromhid,$tid){
        $results = Agreement::where('Plot',$pidfrom)->where('House',$fromhid)->where('Tenant',$tid)->get();
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

    public static function getNextMonth($month,$monthdate){
        $watermonthlast= date("Y n",strtotime("+1 months",strtotime($monthdate)));
        return $watermonthlast;
    }

    public static function getNextMonths($monthdate){
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

    public static function getMonthlyMonthdate($thismonth){
        $explomonth=explode(' ', $thismonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y-m');
        return $month;
    }

    public static function updateTenantVacate($tid,$hid,$tenantassignedhse){
        try { 
            $updatehoousevacate = House::findOrFail($hid);
            $updatehoousevacate->Status ='Vacant';
            $updatehoousevacate->save();

            if ($tenantassignedhse<1) {
                $updatetenantvacates = Tenant::findOrFail($tid);
                $updatetenantvacates->Status ='Vacated';
                $updatetenantvacates->save();
            }
            
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public static function updateTenantHouse($tid,$hid){
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

    public static function updateTenantHouseAdd($hid){
        try { 
            $updatehouse = House::findOrFail($hid);
            $updatehouse->Status ='Occupied';
            $updatehouse->save();
            return true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return false;
        }
    }

    public static function updateTenantReassign($tid,$hid,$fromhid){
        try { 
            $updatetenant = Tenant::findOrFail($tid);
            $updatetenant->Status ='Reassigned';
            $updatetenant->save();

            $updatefrom = House::findOrFail($fromhid);
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

    public static function updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease){
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

    public static function updatePaymentsExisting($paymentid,$Rent,$Garbage){
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


    public static function encryptText($text){
        $encrypted =Crypt::encryptString($text);
        return $encrypted;
    }

    public static function decryptText($text){
        try{
            $decrypted =Crypt::decryptString($text);
            return $decrypted;
        }
        catch (\Illuminate\Contracts\Encryption\DecryptException $ex){
            // $errors="The payload is";
            // $error=$ex->getMessage();
            // if (preg_match("/$errors\/|$errors\s/i", $error, $match)) {
            //     return 'The text is not valid encrypted String';
            // }
            return $text;
        }
        
    }

    public static function tenantsHidData(){
        $tenantsiss = Tenant::orderByDesc('id')->get();
        $tenantsi= array();
        foreach($tenantsiss as $thistenant){
            $tenantsi[] = array(
                'id'=> $thistenant->id,
                'Fname' => $thistenant->Fname,
                'Oname' => $thistenant->Oname,
                'Gender' => $thistenant->Gender,
                'IDno' => $thistenant->IDno,
                'HudumaNo' => $thistenant->HudumaNo,
                'Phone' => $thistenant->Phone,
                'Email' => $thistenant->Email,
                'Status' => $thistenant->Status,
                'created_at' => $thistenant->created_at,
                'updated_at' => $thistenant->updated_at
            );
         }
         return $tenantsi;
    }

    public static function tenantsHidDataVacate(){
        $tenantsiss = Tenant::orderByDesc('id')->where('Status','Assigned')->orwhere('Status','Reassigned')->orwhere('Status','Transferred')->get();
        $tenantsi= array();
        foreach($tenantsiss as $thistenant){
            $tenantsi[] = array(
                'id'=> $thistenant->id,
                'Fname' => $thistenant->Fname,
                'Oname' => $thistenant->Oname,
                'Gender' => $thistenant->Gender,
                'IDno' => $thistenant->IDno,
                'HudumaNo' => $thistenant->HudumaNo,
                'Phone' => $thistenant->Phone,
                'Email' => $thistenant->Email,
                'Status' => $thistenant->Status,
                'created_at' => $thistenant->created_at,
                'updated_at' => $thistenant->updated_at
            );
         }
         return $tenantsi;
    }

    public static function tenantsHidDataReassign(){
        $tenantsiss = Tenant::orderByDesc('id')->where('Status','Assigned')->orwhere('Status','Reassigned')->orwhere('Status','Transferred')->get();
        $tenantsi= array();
        foreach($tenantsiss as $thistenant){
            $tenantsi[] = array(
                'id'=> $thistenant->id,
                'Fname' => $thistenant->Fname,
                'Oname' => $thistenant->Oname,
                'Gender' => $thistenant->Gender,
                'IDno' => $thistenant->IDno,
                'HudumaNo' => $thistenant->HudumaNo,
                'Phone' => $thistenant->Phone,
                'Email' => $thistenant->Email,
                'Status' => $thistenant->Status,
                'created_at' => $thistenant->created_at,
                'updated_at' => $thistenant->updated_at
            );
         }
         return $tenantsi;
    }

    public static function tenantsHidDataNew(){
        $tenantsiss = Tenant::orderByDesc('id')->where('Status','New')->orwhere('Status','Vacated')->get();
        $tenantsi= array();
        foreach($tenantsiss as $thistenant){
            $tenantsi[] = array(
                'id'=> $thistenant->id,
                'Fname' => $thistenant->Fname,
                'Oname' => $thistenant->Oname,
                'Gender' => $thistenant->Gender,
                'IDno' => $thistenant->IDno,
                'HudumaNo' => $thistenant->HudumaNo,
                'Phone' => $thistenant->Phone,
                'Email' => $thistenant->Email,
                'Status' => $thistenant->Status,
                'created_at' => $thistenant->created_at,
                'updated_at' => $thistenant->updated_at
            );
         }
         return $tenantsi;
    }

    public static function tenantsHidDataAddHouse(){
        $tenantsiss = Tenant::orderByDesc('id')->where('Status','Assigned')->orwhere('Status','Reassigned')->orwhere('Status','Transferred')->orwhere('Status','Other')->get();
        $tenantsi= array();
        foreach($tenantsiss as $thistenant){
            $tenantsi[] = array(
                'id'=> $thistenant->id,
                'Fname' => $thistenant->Fname,
                'Oname' => $thistenant->Oname,
                'Gender' => $thistenant->Gender,
                'IDno' => $thistenant->IDno,
                'HudumaNo' => $thistenant->HudumaNo,
                'Phone' => $thistenant->Phone,
                'Email' => $thistenant->Email,
                'Status' => $thistenant->Status,
                'created_at' => $thistenant->created_at,
                'updated_at' => $thistenant->updated_at
            );
         }
         return $tenantsi;
    }

    public static function tenantsHidDataThis($id){
        $tenants = Tenant::orderByDesc('id')->where('id',Property::decryptText($id))->get();
        $tenantname=Property::TenantNames(Property::decryptText($id));
        
        $thistenant= array();
        $thistenant[] = array(
            'id'=> $id,
            'ids'=> $id,
            'Fname' => $tenants[0]->Fname,
            'Oname' => $tenants[0]->Oname,
            'Gender' => $tenants[0]->Gender,
            'IDnoMasked' => Property::getTenantIDNoMask($tenants[0]->IDno),
            'IDno' => $tenants[0]->IDno,
            'HudumaNo' => $tenants[0]->HudumaNo,
            'Phone' => $tenants[0]->Phone,
            'Email' => $tenants[0]->Email,
            'tenantname' => ucwords(strtolower($tenantname)),
            'Houses' => Property::tenantHousesAssigned(Property::decryptText($id)),
            'housesdata' => Property::tenantHousesOccupiedDataOnly(Property::decryptText($id)),
            'Housenames' => Property::tenantHousesOccupiedOnly(Property::decryptText($id)),
            'PhoneMasked' => Property::getTenantPhoneMask($tenants[0]->Phone),
            'Status' => $tenants[0]->Status,
            'created_at' => $tenants[0]->created_at,
            'updated_at' => $tenants[0]->updated_at
        );

        $thistenant['tenantname']=ucwords(strtolower($tenantname));
        $thistenant['id']=$id;
        $thistenant['ids']=$id;
        $thistenant['Houses']=Property::tenantHousesAssigned(Property::decryptText($id));
        $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly(Property::decryptText($id));
        $thistenant['Housenames']=Property::tenantHousesOccupiedOnly(Property::decryptText($id));
        $thistenant['Fname']=Property::getTenantFname(Property::decryptText($id));
        $thistenant['Oname']=Property::getTenantOname(Property::decryptText($id));
        $thistenant['Email']=Property::getTenantEmail(Property::decryptText($id));
        $thistenant['Gender']=Property::getTenantGender(Property::decryptText($id));
        $thistenant['IDno']=Property::getTenantIDNoMask(Property::getTenantIDno(Property::decryptText($id)));
        
        $thistenant['created_at']=Property::getTenantCReatedAt(Property::decryptText($id));
        $thistenant['Status']=Property::getTenantStatus(Property::decryptText($id));
        $thistenant['Phone']=Property::getTenantPhone(Property::decryptText($id));
        $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($id)));

         return $thistenant;
    }

    public static function tenantsHidDataThisVac($id,$hid){
        $tenants = Tenant::orderByDesc('id')->where('id',Property::decryptText($id))->get();
        $tenantname=Property::TenantNames(Property::decryptText($id));
        
        $thistenant= array();
        $thistenant[] = array(
            'id'=> $id,
            'ids'=> $id,
            'Fname' => $tenants[0]->Fname,
            'Oname' => $tenants[0]->Oname,
            'Gender' => $tenants[0]->Gender,
            'IDnoMasked' => Property::getTenantIDNoMask($tenants[0]->IDno),
            'IDno' => $tenants[0]->IDno,
            'HudumaNo' => $tenants[0]->HudumaNo,
            'Phone' => $tenants[0]->Phone,
            'Email' => $tenants[0]->Email,
            'tenantname' => ucwords(strtolower($tenantname)),
            'Houses' => Property::tenantHousesAssigned(Property::decryptText($id)),
            'housesdata' => Property::tenantHousesOccupiedDataOnlyHid(Property::decryptText($id),$hid),
            'Housenames' => Property::tenantHousesOccupiedOnly(Property::decryptText($id)),
            'PhoneMasked' => Property::getTenantPhoneMask($tenants[0]->Phone),
            'Status' => $tenants[0]->Status,
            'created_at' => $tenants[0]->created_at,
            'updated_at' => $tenants[0]->updated_at
        );

        $thistenant['tenantname']=ucwords(strtolower($tenantname));
        $thistenant['id']=$id;
        $thistenant['ids']=$id;
        $thistenant['Houses']=Property::tenantHousesAssigned(Property::decryptText($id));
        $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnlyHid(Property::decryptText($id),$hid);
        $thistenant['Housenames']=Property::tenantHousesOccupiedOnly(Property::decryptText($id));
        $thistenant['Fname']=Property::getTenantFname(Property::decryptText($id));
        $thistenant['Oname']=Property::getTenantOname(Property::decryptText($id));
        $thistenant['Email']=Property::getTenantEmail(Property::decryptText($id));
        $thistenant['Gender']=Property::getTenantGender(Property::decryptText($id));
        $thistenant['IDno']=Property::getTenantIDNoMask(Property::getTenantIDno(Property::decryptText($id)));
        
        $thistenant['created_at']=Property::getTenantCReatedAt(Property::decryptText($id));
        $thistenant['Status']=Property::getTenantStatus(Property::decryptText($id));
        $thistenant['Phone']=Property::getTenantPhone(Property::decryptText($id));
        $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($id)));

         return $thistenant;
    }

            
    public static function housesHidDataThis($hid,$id,$tenanttname,$tenantfullname){
        $thishouses=House::findOrFail(Property::decryptText($hid));

        $thishouse= array();
        $thishouse[] = array(
            'id' => $hid,
            'Housename' => $thishouses->Housename,
            'Plot' => $thishouses->Plot,
            'Rent' => $thishouses->Rent,
            'Deposit' => $thishouses->Deposit,
            'Kplc' => $thishouses->Kplc,
            'Water' => $thishouses->Water,
            'Lease' => $thishouses->Lease,
            'Garbage' => $thishouses->Garbage,
            'Status' => $thishouses->Status,
            'DueDay' => $thishouses->DueDay,
            'tenant' => $id,
            'tenantname' => $tenanttname,
            'tenantfullname' => $tenantfullname,
            'created_at' => $thishouses->created_at,
            'updated_at' => $thishouses->updated_at
        );

        $thishouse['tenantfullname']=$tenantfullname;
        $thishouse['tenantname']=$tenanttname;
        $thishouse['tenant']=$id;
        $thishouse['id']=$hid;

        // $thistenant['tenantname']=ucwords(strtolower($tenantname));
        // $thistenant['id']=$id;
        // $thistenant['ids']=Property::decryptText($id);
        // $thistenant['Houses']=Property::tenantHousesAssigned(Property::decryptText($id));
        // $thistenant['housesdata']=Property::tenantHousesOccupiedDataOnly(Property::decryptText($id));
        // $thistenant['Housenames']=Property::tenantHousesOccupiedOnly(Property::decryptText($id));
        // $thistenant['Fname']=Property::getTenantFname(Property::decryptText($id));
        // $thistenant['Oname']=Property::getTenantOname(Property::decryptText($id));
        // $thistenant['Email']=Property::getTenantEmail(Property::decryptText($id));
        // $thistenant['Gender']=Property::getTenantGender(Property::decryptText($id));
        // $thistenant['IDno']=Property::getTenantIDNoMask(Property::getTenantIDno(Property::decryptText($id)));
        
        // $thistenant['created_at']=Property::getTenantCReatedAt(Property::decryptText($id));
        // $thistenant['Status']=Property::getTenantStatus(Property::decryptText($id));
        // $thistenant['Phone']=Property::getTenantPhone(Property::decryptText($id));
        // $thistenant['PhoneMasked']=Property::getTenantPhoneMask(Property::getTenantPhone(Property::decryptText($id)));

         return $thishouse;
    }

    public static function getTotalPropertyMonthly($month){
        $current= Property::getMonthlyMonthdate($month);
        $datesent=Property::query()
                    ->where('created_at','LIKE',"%{$current}%")->count();
        return $datesent;
    }

    public static function getTotalHousesMonthly($month){
        $current= Property::getMonthlyMonthdate($month);
        $datesent=House::query()
                    ->where('created_at','LIKE',"%{$current}%")->count();
        return $datesent;
    }

    public static function getTotalTenantsMonthly($month){
        $current= Property::getMonthlyMonthdate($month);
        // $datesent=Tenant::query()
        //             ->where('created_at','LIKE',"%{$current}%")->count();
        // return $datesent;

        $assigned=Tenant::query()
                    ->where('Status','=','Assigned')
                    ->where('created_at','LIKE',"%{$current}%")->count();
        $transferred=Tenant::query()
                    ->where('Status','=','Transferred')
                    ->where('created_at','LIKE',"%{$current}%")->count();
        $reaaasigned=Tenant::query()
                    ->where('Status','=','Reassigned')
                    ->where('created_at','LIKE',"%{$current}%")->count();

                    
        $datesent=($assigned+$transferred+$reaaasigned);
        return $datesent;
    }

    public static function getTotalHousesOccupiedMonthly($month){
        // $current= Property::getMonthlyMonthdate($month);
        $datesent=Agreement::query()
                    ->where('Month','=','0')
                    ->where('MonthAssigned','=',$month)->count();
        return $datesent;
    }

    public static function getTotalTenantsVacatedOrNewMonthly($month){
        $current= Property::getMonthlyMonthdate($month);
        $vacated=Tenant::query()
                    ->where('Status','=','Vacated')
                    ->where('created_at','LIKE',"%{$current}%")->count();
        $new=Tenant::query()
                    ->where('Status','=','New')
                    ->where('created_at','LIKE',"%{$current}%")->count();
        $datesent=($vacated+$new);
        return $datesent;
    }


    public static function getTotalVacantHousesMonthly($month){
        $current= Property::getMonthlyMonthdate($month);

        $vacanthouses=House::where('Status','Vacant')->get();
        $houses=0;
        foreach($vacanthouses as $result){
            $houseid= Property::decryptText($result['id']);
            $created_at=$result['created_at'];

            $createdmonth=date_format(date_create($created_at),'Y n');
            if($vacated=Agreement::query()
                    ->where('house','=',$houseid)->get()->first()){
                    // ->where('Month','=',$month)->get()->first()){
                $date_vacated=$vacated->DateVacated;
                $vacatedmonth=date_format(date_create($date_vacated),'Y n');
                if($month==$vacatedmonth){
                    $houses++;
                }
            }
            else if($month==$createdmonth){
                $houses++;
            }



            //check for those created current month
         }
        return $houses;
    }

    

    // Tenant::where('Status','Vacated')->orWhere('Status','New')->get()->count();

    public static function getSentDate($id,$month,$current_water){
        $current='Current:'.$current_water;
        $datesent=WaterMessage::query()
                    ->where('house','=',$id)
                    ->where('Month','=',$month)
                    ->where('Message','LIKE',"%{$current}%")->max('created_at');
        return $datesent;
    }

    public static function getSentDateMsg($id,$month){
        $datesent=WaterMessage::query()
                    ->where('House','=',$id)
                    ->where('Month','=',$month)->max('created_at');
        return $datesent;
    }

    public static function getSentDateOther($id,$month){
        $datesent=DB::table('water_messages_others')->where([
            'Tenant'=>$id,
            'Month'=>$month
        ])->max('created_at');
        return $datesent;
    }

    public static function getSentDatePayment($pid,$tid,$month){
        $datesent=DB::table('payment_messages')->where([
            'Plot'=>$pid,
            'Tenant'=>$tid,
            'Month'=>$month
        ])->max('created_at');
        if($datesent){
            return Carbon::parse($datesent)->diffForHumans();
            // $datesentdiff=Carbon::parse($datesent)->format('Y');
        }
        else{
            return false;
        }
    }

    public static function getSentDatePaymentCompleted($pid,$tid,$month){
        $datesent=DB::table('payment_messages')->where([
            'Plot'=>$pid,
            'Tenant'=>$tid,
            'msgtype'=>'Completed',
            'Month'=>$month
        ])->max('created_at');
        if($datesent){
            return Carbon::parse($datesent)->diffForHumans();
            // $datesentdiff=Carbon::parse($datesent)->format('Y');
        }
        else{
            return false;
        }
    }

    public static function getSentDatePaymentSummary($pid,$tid,$month){
        $datesent=DB::table('payment_messages')->where([
            'Plot'=>$pid,
            'Tenant'=>$tid,
            'msgtype'=>'Summary',
            'Month'=>$month
        ])->max('created_at');
        if($datesent){
            return Carbon::parse($datesent)->diffForHumans();
            // $datesentdiff=Carbon::parse($datesent)->format('Y');
        }
        else{
            return false;
        }
    }

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

    public static function getIfBlacklistedNumber($pid,$id){
        // $allproperties=House::where('plot',$pid)->get();
        // foreach ($allproperties as $housed) {
        //     $hid=Property::decryptText($housed->id);
        //     $allwatermessages=WaterMessage::where('house',$hid)->get();
        //     foreach($allwatermessages as $message){
        //         $phone=$message->To;
        //         if($messagesStatus=WaterMessage::query()->where('To','=',$phone)->get()->first()){
        //             $sent_at=$messagesStatus->created_at;
        //             $status=$messagesStatus->Status;
        //             $tenantid=Property::getTenantIdUsingPhone($phone);
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
        //                     if($status == null){
                                
        //                     }
        //                     else{
        //                         $tenant = Tenant::findOrFail($tenantid);
        //                         $tenant->isblacklisted ='Check';
        //                         $tenant->blacklisted_at =$sent_at;
        //                         $tenant->save();
        //                     }

                            
        //                 }
        //             }
        //         }
        //     }
        // }
        
        

        $isblacklistedstatus='No';
        if($tenantisblacklisted = Tenant::findOrFail($id)){
            $isblacklistedstatus=$tenantisblacklisted->isblacklisted;
        }

        return $isblacklistedstatus;
    }

    public static function  visualizeGraph($maxLevel) {
        $states = [];
        $queue = [""]; // Start with the initial state

        for ($level = 0; $level <= $maxLevel; $level++) {
            $levelStates = [];
            $levelSize = count($queue);

            for ($i = 0; $i < $levelSize; $i++) {
                $currentState = array_shift($queue); // Get the first state from the queue
                $levelStates[] = $currentState;

                if ($level < $maxLevel) {
                    $queue[] = $currentState . "A";
                    $queue[] = $currentState . "B";
                    $queue[] = $currentState . "C";
                }
            }
            $states[$level] = $levelStates;
        }
        return $states;
    }

    public static function generateStates() {
        $correctPasswords = ["AAACCC", "ABBCC", "BABAB", "BCABAC", "CBAC", "CBACB"];
        $maxLevel = 6; // Maximum length of the passwords
        $states = [];
        $queue = [""];
    
        for ($level = 0; $level <= $maxLevel; $level++) {
            $levelSize = count($queue);
            for ($i = 0; $i < $levelSize; $i++) {
                $currentState = array_shift($queue);
                $states[] = $currentState; // Add to the result array
    
                 if (in_array($currentState, $correctPasswords)) {
                    continue; //if it is one of the correct passwords, do not expand it.
                 }
    
                if ($level < $maxLevel) {
                    $queue[] = $currentState . "A";
                    $queue[] = $currentState . "B";
                    $queue[] = $currentState . "C";
                }
            }
        }
        return $states;
    }
    

    
    

}
