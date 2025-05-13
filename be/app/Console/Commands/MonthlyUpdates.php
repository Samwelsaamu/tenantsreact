<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Property;
use App\Models\House;
use App\Models\Payment;

class MonthlyUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthlybills:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating all  Rent and Garbage for each month. Also Balances from last month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $month=date('Y n');
        $houseinfo=House::all();
        foreach($houseinfo as $result){
            $hid= $result['id'];
            $pid= $result['Plot'];
            $Rent= $result['Rent'];
            $Garbage= $result['Garbage'];
            $Housename= $result['Housename'];
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
                if(!$paymentid=Property::getPaymentUpdateId($hid,$month)){
                    $paymentsnew = new Payment;
                    $paymentsnew->Plot=$pid;
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
                Property::setLogs($Housename.' Monthly Rent '.$Rent.' and Garbage '.$Garbage .' for Month of '.$month.' has been set With Arrears of '.$Arrears .' and Excess of '.$Excess);
            }
        }
    }
}