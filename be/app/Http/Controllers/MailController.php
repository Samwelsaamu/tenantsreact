<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Property;
use App\Models\Agency;
use App\Models\Report;
use App\Models\UserLogs;
use App\Models\Mails;
use App\Models\House;

use App\Models\Agreement;
use App\Models\Tenant;
use App\Models\Water;
use App\Models\Payment;
use App\Models\PaymentDate;
use App\Models\Blacklisted;
use App\Http\Controllers\TenantController;


use AfricasTalking\SDK\AfricasTalking;
use Webklex\IMAP\Facades\Client;

use PDF;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mail = Mails::findOrFail($id);
        $subject=Property::getMailSubject($id);
        $mail->status='Deleted';
        $mail->save();
        // $mail->delete();
        Property::setUserLogs('Deleted Sent Mail: '.$subject);
        return redirect('/mail/getmail')->with('success', 'Mail has been Deleted');
    }

    public function deliveryreports(Request $request){
        return response()->json([
            'status'=>500,
            'message'=>"HEllo",
        ]);
        Property::setLogs('Getting SMS delivery Notification');
        

        //store the received in Json File 
        $callback=file_get_contents('php://input');
        // deco the received json 
        $callbackurl=json_decode($callback,true);

        // id
        // status
        // phoneNumber 
        // networkCode 
        // failureReason 
        // retryCount 
        $savelog = new UserLogs;
        $savelog->User ='System';
        $savelog->Message ="Delivery Notification Found";
        $savelog->save();

        $savelog = new UserLogs;
        $savelog->User ='System';
        $savelog->Message =callbackurl;
        $savelog->save();


        

        // Property::setLogs($callbackurl);
    }

    public function smsDeliveryReports(){
        return response()->json([
            'status'=>500,
            'message'=>"HEllo",
        ]);
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
                        $tenantid=$waterbills->Tenant;
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
                                $tenantid=$prevwaterbills->Tenant;
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
                    $tenantid=$waterbills->Tenant;
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
                            $tenantid=$prevwaterbills->Tenant;
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
                            $tenantid=$prevwaterbills->Tenant;
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

    public function downloadwaterbillperyearexcel($id,$year,$month)
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

        $writer= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file,'Xls');
        $filename=$propertyname.' Water Bill for '.$startyear .' .' .strtolower('xls');
        $writer->save($filename);

        header('Content-Type:application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding:Binary');
        header("Content-disposition:attachment;filename=\"".$filename."\"");

        readfile($filename);

        unlink($filename);   

        exit;   
        //end per year

            

    }
}
