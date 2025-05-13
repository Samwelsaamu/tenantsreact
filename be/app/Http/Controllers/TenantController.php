<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

use Carbon\Carbon;

class TenantController extends Controller
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
        
        Property::setUserLogs('Saving Tenant Information');
        $storeData = $request->validate([
            'Fname' => ['required', 'string', 'max:150'],
            'Oname' => ['required', 'string', 'max:150'],
            'Gender' => ['required', 'string', 'max:150'],
            'IDno' => ['required', 'integer'],   
            'HudumaNo' => ['required', 'integer'],
            'Phone' => ['required', 'integer'],
            'Status' => ['required', 'string','max:150'],
        ]);
        $tenantname=$request->input('Fname').' '.$request->input('Oname');
         try { 
            $houseinfo = new Tenant;
            $houseinfo->Fname =$request->input('Fname');
            $houseinfo->Oname =$request->input('Oname');
            $houseinfo->Gender =$request->input('Gender');
            $houseinfo->IDno =$request->input('IDno');
            $houseinfo->HudumaNo =$request->input('HudumaNo');
            $houseinfo->Email =$request->input('Email');
            $houseinfo->Phone =$request->input('Phone');
            $houseinfo->Status =$request->input('Status');
            $houseinfo->save();
            
            Property::setUserLogs($tenantname.' Tenant Information Saved');
                return redirect("/properties/newtenant")->with('success', 'Tenant Information Saved!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                Property::setUserLogs($tenantname.' Tenant Information not Saved with Error: '.$ex->getMessage());
                return redirect("/properties/newtenant")->with('dbError', $ex->getMessage());
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
        $thistenant = Tenant::findOrFail($id);
        $tenantname=$this->TenantNames($id);
        Property::setUserLogs('Viewing to Edit Tenant '.$tenantname.' Information');
        return view('updatetenant', compact('propertyhouses','propertyinfo','tenantsinfo','thistenant'));
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
        
        $tenantname=$this->TenantNames($id);
        Property::setUserLogs('Updating Tenant '.$tenantname.' Information');
        try { 
         $updateTenant = $request->validate([
            'Fname' => ['required', 'string', 'max:150'],
            'Oname' => ['required', 'string', 'max:150'],
            'Gender' => ['required', 'string', 'max:150'],
            'IDno' => ['required', 'integer'],   
            'HudumaNo' => ['required', 'integer'],
            'Phone' => ['required', 'integer'],
        ]);
         Property::setUserLogs('Tenant '.$tenantname.' Information Updated');
            Tenant::whereId($id)->update($updateTenant);
            return redirect('/tenant/'.$id.'/edit')->with('success', 'Tenant has been updated');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            Property::setUserLogs('Tenant '.$tenantname.' Information not Updated with Error: '.$ex->getMessage() );
            return redirect('/tenant/'.$id.'/edit')->with('dbError', $ex->getMessage());
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
        $tenantname=$this->TenantNames($id);
        try { 
            $tenant = Tenant::findOrFail($id);
            $tenant->delete();
            Property::setUserLogs('Deleted Tenant '.$tenantname.' Information');
            return redirect('/properties/tenants')->with('success', 'Tenant has been deleted');

        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            Property::setUserLogs('Not Deleted Tenant '.$tenantname.' Information with Error : '.$ex->getMessage());
            return redirect('/properties/tenants')->with('dbError','Could Not Delete Tenant');
        }
    }

    public function deleteTenant(Request $request)
    {
        try{
            $id=$request->input('deltid');
            if($property = Tenant::find($id)){
                $property->delete();
                $success='<span>Tenant has been Deleted</br>';
                return compact('success');
            }
            else{
                $error='<span>Tenant is Not Found</br>';
                return compact('error');
            }
            
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1451';
            $error='<span>Tenant Not Deleted</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Occupied</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>Tenant Not Deleted</br>'.$ex->getMessage();
            return compact('error');
        }
    }

    public function saveTenant(Request $request)
    {
        $storeData = $request->validate([
            'Fname' => ['required', 'string', 'max:150'],
            'Oname' => ['required', 'string', 'max:150'],
            'Gender' => ['required', 'string', 'max:150'],
            'IDno' => ['required', 'integer'],   
            'Phone' => ['required', 'integer'],
            'Status' => ['required', 'string','max:150'],
        ]);
        $tenantname=$request->input('Fname').' '.$request->input('Oname');
         try { 
            $tenantinfo = new Tenant;
            $tenantinfo->Fname =$request->input('Fname');
            $tenantinfo->Oname =$request->input('Oname');
            $tenantinfo->Gender =$request->input('Gender');
            $tenantinfo->IDno =$request->input('IDno');
            $tenantinfo->HudumaNo =$request->input('HudumaNo');
            $tenantinfo->Email =$request->input('Email');
            $tenantinfo->Phone =$request->input('Phone');
            $tenantinfo->Status =$request->input('Status');
            if($tenantinfo->save()){
                Property::setUserLogs($tenantname.' Tenant Information Saved');
                $success='<span>Tenant Information Saved!</br>';
                return compact('success');
            }
            else{
                Property::setUserLogs($tenantname.' Tenant Information not Saved');
                $error='<span>Information for Tenant Not Saved </br>';
                return compact('error');
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>Tenant Was Not Saved</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Already Saved</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            Property::setUserLogs($tenantname.' Tenant Information not Saved . Error: '.$ex->getMessage());
            $error='<span>Tenant Not Saved</br>'.$ex->getMessage();
            return compact('error');
        }
    }

    public function updateTenant(Request $request)
    {
        $updateTenant = $request->validate([
            'Fname' => ['required', 'string', 'max:150'],
            'Oname' => ['required', 'string', 'max:150'],
            'Gender' => ['required', 'string', 'max:150'],
            'IDno' => ['required', 'integer'],   
            'Phone' => ['required', 'integer'],
        ]);
         try { 
            $id=$request->input('tid');
            $tenantname=$request->input('Fname').' '.$request->input('Oname');
            if($tenantinfo = Tenant::find($id)){
                $tenantinfo->Fname =$request->input('Fname');
                $tenantinfo->Oname =$request->input('Oname');
                $tenantinfo->Gender =$request->input('Gender');
                $tenantinfo->IDno =$request->input('IDno');
                $tenantinfo->Phone =$request->input('Phone');
                $tenantinfo->Email =$request->input('Email');
                if($tenantinfo->save()){
                    Property::setUserLogs('Tenant '.$tenantname.' Updated');
                    //update phone Number
                    $Phone=$request->input('Phone');
                    Agreement::where('Tenant',$id)->update(['Phone'=>$Phone]);
                    $success='<span>Tenant Information has been Updated</br>';
                    return compact('success');
                }
                else{
                    Property::setUserLogs('Tenant '.$tenantname.' Not Updated');
                    $error='<span>Tenant Information has not Been Updated</br>';
                    return compact('error');
                }
            }
            else{
                $error='<span>Tenant is Not Found</br>';
                return compact('error');
            }
            
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingsavederror='1062';
            $beingusederror='1451';
            $error='<span>Tenant Not Updated</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Being Used</br>';
            }
            if (preg_match("/$beingsavederror/i", $errors)) {
                $error='<span>Tenant is Already Saved</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>Tenant Not Updated</br>'.$ex->getMessage();
        }
    }

    public function assignTenantHouse(Request $request)
    {
        $hid=$request->input('hid');
        $tid=$request->input('tid');

        $DateAssigned=$request->input('DateAssigned');
        $Nature=$request->input('Nature');
        $tenantname=$this->TenantNames($tid);
        if(($this->TenantStatus($tid)=='New') || ($this->TenantStatus($tid)=='Vacated')){
            
        }
        else{
            $error='<span>Tenant '.$tenantname.' is already assigned House. </br>Please Choose Add House Option.';
            return compact('error');
        }
         try { 
            if($vacanthouse=House::where('id',$hid)->where('status','Vacant')->get()->first()){
                $Housename=$vacanthouse->Housename;
                $Rent=$vacanthouse->Rent;
                $Garbage=$vacanthouse->Garbage;
                $KPLC=$vacanthouse->KPLC;
                $HseDeposit=$vacanthouse->HseDeposit;
                $Water=$vacanthouse->Water;
                $Lease=$vacanthouse->Lease;

                $deposits=$HseDeposit+$Water+$KPLC;
                $month=date_format(date_create($DateAssigned),'Y n');
                $pid=$this->getPropertyId($hid);
                $tenantphone=$this->getTenantPhone($tid);
                $uid=$pid.' '.$hid.' '.$tid;

                //register tenant and house
                $agreement = new Agreement;
                $agreement->Plot=$pid;
                $agreement->House=$hid;
                $agreement->Tenant=$tid;
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
                    if ($Nature=="New") {
                    $this->updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease);
                    }
                    else{
                        $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
                    }
                    $this->updateTenantHouse($tid,$hid);

                    Property::setUserLogs($tenantname.' Tenant Assigned to House: '.$Housename);
                    $success='<span>'.$tenantname.' Tenant Assigned to House: '.$Housename.'!</br>';
                    return compact('success');
                }
                else{
                    Property::setUserLogs($tenantname.' Tenant Not Assigned to House: '.$Housename);
                    $error='<span>'.$tenantname.' Tenant Not Assigned to House: '.$Housename.' </br>';
                    return compact('error');
                }
            }
            else{
                $error='<span>Selected House Not Found</br>';
                return compact('error');
            }

        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>Tenant Was Not Assigned</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Already Assigned</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            Property::setUserLogs($tenantname.' Tenant not Assigned . Error: '.$ex->getMessage());
            $error='<span>Tenant Not Assigned</br>'.$ex->getMessage();
            return compact('error');
        }
    }

    
    public function assignTenantHouseAdd(Request $request)
    {
        $hid=$request->input('hid');
        $tid=$request->input('tid');

        $DateAssigned=$request->input('DateAssigned');
        $Nature=$request->input('Nature');
        $tenantname=$this->TenantNames($tid);
        if(($this->TenantStatus($tid)=='New') || ($this->TenantStatus($tid)=='Vacated')){
            $error='<span>Tenant '.$tenantname.' has No First House Assigned. </br>Please Choose Assign House Option.';
            return compact('error');
        }
        else{
            
        }
         try { 
            if($vacanthouse=House::where('id',$hid)->where('status','Vacant')->get()->first()){
                $Housename=$vacanthouse->Housename;
                $Rent=$vacanthouse->Rent;
                $Garbage=$vacanthouse->Garbage;
                $KPLC=$vacanthouse->KPLC;
                $HseDeposit=$vacanthouse->HseDeposit;
                $Water=$vacanthouse->Water;
                $Lease=$vacanthouse->Lease;

                $deposits=$HseDeposit+$Water+$KPLC;
                $month=date_format(date_create($DateAssigned),'Y n');
                $pid=$this->getPropertyId($hid);
                $tenantphone=$this->getTenantPhone($tid);
                $uid=$pid.' '.$hid.' '.$tid;

                //register tenant and house
                $agreement = new Agreement;
                $agreement->Plot=$pid;
                $agreement->House=$hid;
                $agreement->Tenant=$tid;
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
                    if ($Nature=="New") {
                    $this->updatePaymentsNew($paymentid,$Rent,$Garbage,$KPLC,$HseDeposit,$Water,$Lease);
                    }
                    else{
                        $this->updatePaymentsExisting($paymentid,$Rent,$Garbage);
                    }
                    $this->updateTenantHouseAdd($hid);
                    Property::setUserLogs($tenantname.' Tenant Added to House: '.$Housename);
                    $success='<span>'.$tenantname.' Tenant Added to House: '.$Housename.'!</br>';
                    return compact('success');
                }
                else{
                    Property::setUserLogs($tenantname.' Tenant Not Added to House: '.$Housename);
                    $error='<span>'.$tenantname.' Tenant Not Added to House: '.$Housename.' </br>';
                    return compact('error');
                }
            }
            else{
                $error='<span>Selected House Not Found</br>';
                return compact('error');
            }

        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>Tenant Was Not Added</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Already Added to this House</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            Property::setUserLogs($tenantname.' Tenant not Added . Error: '.$ex->getMessage());
            $error='<span>Tenant Not Added</br>'.$ex->getMessage();
            return compact('error');
        }
    }

    public function reassignTenantHouse(Request $request)
    {
        \DB::beginTransaction();
        $hid=$request->input('hid');//new
        $tid=$request->input('tid');
        $fromhid=$request->input('fromhid');//old
        $Excess=$request->input('Excess');
        $Arrears=$request->input('Arrears');
        $DateAssigned=$request->input('DateAssigned');
        $Nature=$request->input('Nature');
        $tenantname=$this->TenantNames($tid);
        if(($this->TenantStatus($tid)=='New') || ($this->TenantStatus($tid)=='Vacated')){
            $error='<span>Tenant '.$tenantname.' has No  House Assigned. </br>Please Choose Assign House Option.';
            return compact('error');
        }
        else{
            
        }
         try { 
            if($vacanthouse=House::where('id',$hid)->where('status','Vacant')->get()->first()){
                $Housename=$vacanthouse->Housename;
                
                $Rent=$vacanthouse->Rent;
                $Garbage=$vacanthouse->Garbage;
                $KPLC=$vacanthouse->KPLC;
                $HseDeposit=$vacanthouse->HseDeposit;
                $Water=$vacanthouse->Water;

                $Lease=$vacanthouse->Lease;
                
                $deposits=$HseDeposit+$Water+$KPLC;
                $month=date_format(date_create($DateAssigned),'Y n');

                $pid=$this->getPropertyId($hid);//new
                $pidfrom=$this->getPropertyId($fromhid);//old
                $tenantphone=$this->getTenantPhone($tid);
                $uid=$pidfrom.' '.$fromhid.' '.$tid;//old
                $newuid=$pid.' '.$hid.' '.$tid;//new

                $aid=$this->getAgreementIds($pidfrom,$fromhid,$tid);
                // $success='<span>'.$tenantname.' Tenant Re-Assigned to House: '.$fromhid.' Tenant: '.$tid.' Plot: '.$pidfrom.'!</br>';
                // $error='<span>'.$tenantname.' Tenant Re-Assigned to House: '.$aid.'!</br>';
                // return compact('error');

                $balance=$this->TenantBalance($tid,$fromhid);
                if ($balance>0) {
                    $Arrears=$Arrears+$balance;
                }
                else{
                    $Excess=$Excess+abs($balance);
                }

                //reassign tenant house
                $agreement = new Agreement;
                $agreement->Plot=$pid;
                $agreement->House=$hid;
                $agreement->Tenant=$tid;
                $agreement->DateAssigned=$DateAssigned;
                $agreement->DateTo=$DateAssigned;
                $agreement->Deposit=$deposits;
                $agreement->Phone=$tenantphone;
                $agreement->UniqueID=$newuid;

                if($agreement->save()){
                    //update old tenant house information
                    $updateagreement = Agreement::findOrFail($aid);
                    $updateagreement->DateVacated=$DateAssigned;
                    $updateagreement->Month=$month;
                    $updateagreement->DateTo=$DateAssigned;
                    $updateagreement->DateVacated=$DateAssigned;
                    $updateagreement->save();
                    // save assigned tenant house bills information
                    $payments = new Payment;
                    $payments->Plot=$pid;
                    $payments->House=$hid;
                    $payments->Tenant=$tid;
                    $payments->Rent =$Rent;
                    $payments->Garbage =$Garbage;
                    $payments->Arrears =$Arrears;
                    $payments->Excess =$Excess;
                    $payments->Month=$month;
                    $payments->save();
                    $paymentid=$payments->id;

                    $this->updateTenantReassign($tid,$hid,$fromhid);

                    Property::setUserLogs($tenantname.' Tenant Re-Assigned to House: '.$Housename);
                    \DB::commit();
                    $success='<span>'.$tenantname.' Tenant Re-Assigned to House: '.$Housename.'!</br>';
                    return compact('success');
                }
                else{
                    \DB::rollback();
                    Property::setUserLogs($tenantname.' Tenant Not Re-Assigned to House: '.$Housename);
                    $error='<span>'.$tenantname.' Tenant Not Re-Assigned to House: '.$Housename.' </br>';
                    return compact('error');
                }
            }
            else{
                \DB::rollback();
                $error='<span>Selected House Not Found</br>';
                return compact('error');
            }

        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>Tenant Was Not Re-Assigned</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Already Re-Assigned to this House</br>';
            }
            \DB::rollback();
            return compact('error');
        }
        catch(\Exception $ex){ 
            \DB::rollback();
            Property::setUserLogs($tenantname.' Tenant not Re-Assigned . Error: '.$ex->getMessage());
            $error='<span>Tenant Not Re-Assigned</br>'.$ex->getMessage();
            return compact('error');
        }
    }

    public function vacateTenantHouse(Request $request)
    {
        \DB::beginTransaction();
        $hid=$request->input('fromhid');//new
        $tid=$request->input('tid');
        $aid=$request->input('aid');

        $tenantname=$this->TenantNames($tid);

        $Deposit=$request->input('Deposit');
        $Refund=$request->input('Refund');
        $Arrears=$request->input('Arrears');
        $Damages=$request->input('Damages');
        $DateVacated=$request->input('DateAssigned');
        $Transaction=$request->input('Transaction');

        $month=date_format(date_create($DateVacated),'Y n');
        $tenantassignedhse=$this->tenantHousesAssigned($tid);

        if(($this->TenantStatus($tid)=='New') || ($this->TenantStatus($tid)=='Vacated')){
            $error='<span>Tenant '.$tenantname.' has No House Assigned. </br>Please Choose Assign House Option.';
            return compact('error');
        }
        else{
            
        }
         try { 
            if($vacanthouse=House::where('id',$hid)->where('status','Occupied')->get()->first()){
                $Housename=$vacanthouse->Housename;
                
                $agreement = Agreement::findOrFail($aid);
                $agreement->DateVacated=$DateVacated;
                $agreement->Deposit=$Deposit;
                $agreement->Refund=$Refund;
                $agreement->Damages=$Damages;
                $agreement->Month=$month;
                $agreement->Arrears=$Arrears;
                $agreement->DateTo=$DateVacated;
                $agreement->Transaction=$Transaction;
                if($agreement->save()){
                    $this->updateTenantVacate($tid,$hid,$tenantassignedhse);

                    Property::setUserLogs($tenantname.' Tenant Vacated from House: '.$Housename);
                    \DB::commit();
                    $success='<span>'.$tenantname.' Tenant Vacated from House: '.$Housename.'!</br>';
                    return compact('success');
                }
                else{
                    \DB::rollback();
                    Property::setUserLogs($tenantname.' Tenant Not Vacated from House: '.$Housename);
                    $error='<span>'.$tenantname.' Tenant Not Vacated from House: '.$Housename.' </br>';
                    return compact('error');
                }
            }
            else{
                \DB::rollback();
                $error='<span>Selected House Not Found</br>';
                return compact('error');
            }

        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>Tenant Was Not Vacated</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>Tenant is Already Vacated from this House</br>';
            }
            \DB::rollback();
            return compact('error');
        }
        catch(\Exception $ex){ 
            \DB::rollback();
            Property::setUserLogs($tenantname.' Tenant not Vacated . Error: '.$ex->getMessage());
            $error='<span>Tenant Not Vacated</br>'.$ex->getMessage();
            return compact('error');
        }
    }
    

    public function searchtenant($id)
    {   
        $tenantname=$this->TenantNames($id);
        Property::setUserLogs('Viewing Tenant '.$tenantname.' Information');
        $thistenant =Tenant::findOrFail($id);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $propertyhouses = House::all();
        $thistenantreports =Report::where('ReportTo',$id)->get();
        return view('searchtenants', compact('thistenant','propertyinfo','tenantsinfo','propertyhouses','thistenantreports'));
    }

    public function Assigntenant($id)
    {   
        $tenantname=$this->TenantNames($id);
        Property::setUserLogs('Viewing Tenant '.$tenantname.' to Assign House');
        $thistenant = Tenant::findOrFail($id);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse="";
        return view('assigntenants', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse'));
    }

    public function AssigntenantHse($id,$tid)
    {   
        $tenantname=$this->TenantNames($tid);
        Property::setUserLogs('Viewing Tenant '.$tenantname.' to Assign House '.Property::getHouseName($id));
        $thistenant = Tenant::findOrFail($tid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse=House::findOrFail($id);
        return view('assigntenants', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse'));
    }

    public function viewagreement($id)
    {   
        $tenantname=$this->TenantNames($id);
        Property::setUserLogs('Viewing Tenant '.$tenantname.' Agreement');
        $thistenant = Tenant::findOrFail($id);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $agreements= Agreement::where('Tenant',$id)->get();
        return view('viewagreement', compact('thistenant','propertyinfo','tenantsinfo','agreements'));
    }

    public function tenanthousesinfo($id,$hid,$aid)
    {   
        $thistenant = Tenant::findOrFail($id);
        $thishouse = House::findOrFail($hid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $agreements=DB::table('agreements')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->get();
        $payments=DB::table('payments')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->get();
        $waterbill=DB::table('waters')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->orderby('id','Desc')->get();
        $tenantphone='+254'.substr($this->getTenantPhone($id), 0);
        $watermessages=DB::table('water_messages')->where([
            'To'=>$tenantphone,
            'House'=>$hid
        ])->orderby('id','Desc')->get();
        $messages=DB::table('messages')->where([
            'To'=>$tenantphone
        ])->orderby('id','Desc')->get();
        // dd($thisagreement);
        return view('viewtenantshouseinfo', compact('thistenant','propertyinfo','tenantsinfo','agreements','payments','waterbill','messages','thishouse','watermessages'));
    }

     public function vacatetenant($id,$hid,$aid)
    {   
        
        $thistenant = Tenant::findOrFail($id);
        $thishouse = House::findOrFail($hid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $agreements=DB::table('agreements')->where([
            'Tenant'=>$id,
            'House'=>$hid
        ])->get();
        
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
        $tenantphone='+254'.substr($this->getTenantPhone($id), 0);
        $messages=DB::table('messages')->where([
            'To'=>$tenantphone
        ])->orderby('id','Desc')->get();
        $thisagreement=Agreement::findOrFail($aid);
        // dd($thisagreement);
        return view('vacatetenant', compact('thistenant','propertyinfo','tenantsinfo','agreements','messages','thishouse','Arrears','Excess','TotalUsed','TotalPaid','thisagreement','Balance'));
    }

    public static function tenantHouses($id){
        $houseshere= Agreement::where('Tenant',$id)->get();
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                echo '<a href="/properties/Houses/Tenant/'.$id.'/'.$houses->House.'/'.$houses->id.'" class="btn btn-success btn-sm " style="padding: 3px;font-size: 10px;margin-right: 2px;color: white;" title="Current: View Tenant House"><span class="fa fa-info-circle fa-1x"></span> '.Property::getHouseName($houses->House).'</a>';
            }
            else{
                echo '<a href="/properties/Houses/Tenant/'.$id.'/'.$houses->House.'/'.$houses->id.'" class="btn btn-default btn-sm " style="padding: 3px;font-size: 10px;margin-right: 2px;color: black;" title="Vacated: View Tenant House"><span class="fa fa-info-circle fa-1x"></span> '.Property::getHouseName($houses->House).'</a>';
            }
            
        }
    }
    
    public static function tenantHousesMgr($id){
        $houseshere= Agreement::where('Tenant',$id)->get();
        $output='';
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                $output.= '<span class="text-success" style="border-radius:5px;font-size:9px;padding:2px;margin:2px;">'.Property::getHouseName($houses->House).' </span>';
            }
            // else{
            //     $output.= '<span class="m-1 p-1 text-danger text-xs" style="border:1px solid gray;border-radius:5px;"><i class="fa fa-times"></i> '.Property::getHouseName($houses->House).' </span>';
            // }
        }
        return $output;
    }

    public static function housetenants($id){
        $houseshere= Agreement::where('House',$id)->get();
        $allhouses=0;
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                $allhouses++;
            }
            else{
                
            }
            
        }
        return $allhouses;
    }

    public static function tenantHousesHistory($id){
        $houseshere= Agreement::where('Tenant',$id)->get();
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                echo '<button class="btn btn-success btn-sm " style="padding: 3px;font-size: 10px;margin-right: 2px;color: white;" title="Current: View Tenant House"><span class="fa fa-info-circle fa-1x"></span> '.Property::getHouseName($houses->House).'</button>';
            }
            else{
                echo '<button class="btn btn-default btn-sm " style="padding: 3px;font-size: 10px;margin-right: 2px;color: black;" title="Vacated: View Tenant House"><span class="fa fa-warning fa-1x"></span> '.Property::getHouseName($houses->House).'</button>';
            }
            
        }
    }

    public static function tenantHousesActions($id){
        $status=Property::tenantStatus($id);
        $houseshere= Agreement::where('Tenant',$id)->get();
       
        if ($status!="Vacated" || $status!="New") {
            foreach ($houseshere as $houses) {
                if ($houses->Month==0) {
                    echo '<a href="/properties/Vacate/Tenant/'.$id.'/'.$houses->House.'/'.$houses->id.'" class="btn btn-danger btn-sm " style="padding: 3px;font-size: 9px;margin-right: 2px;" title="Vacate"><span class="fa fa-times-circle fa-1x"></span> '.Property::getHouseName($houses->House).'</a>';
                }
            }
        }
    }

    public static function tenantHousesTransfer($id){
        $houseshere= Agreement::where('Tenant',$id)->get();
        foreach ($houseshere as $houses) {
            if ($houses->Month==0) {
                echo '<a href="/properties/Transfer/Tenant/'.$id.'/'.$houses->House.'" class="btn btn-info btn-sm " style="padding: 3px;font-size: 9px;margin-right: 2px;color:white;" title="Internal Transfer into '.Property::getHouseName($houses->House).'"><span class="fa fa-paper-plane fa-1x"></span> '.Property::getHouseName($houses->House).'</a>
                <a href="/properties/Reassign/Tenant/'.$id.'/'.$houses->House.'" class="btn btn-success btn-sm " style="padding: 3px;font-size: 9px;margin-right: 2px;color:white;" title="Reassign Tenant House From '.Property::getHouseName($houses->House).'"><span class="fa fa-retweet"></span> '.Property::getHouseName($houses->House).'</a>';
            }
            
        }
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

    public static function tenantHousesAssignedVacated($id){
        $houseshere= Agreement::where('Tenant',$id)->get();
        $housesassignedcount=0;
        foreach ($houseshere as $houses) {
            if ($houses->Month>0) {
                $housesassignedcount++;
            }
        }
        return $housesassignedcount;
    }

    public function addtenanthouse($id)
    {   
        
        $thistenant = Tenant::findOrFail($id);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse="";
        return view('addtenanthouse', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse'));
    }


    public function addtenanthousehse($id,$tid)
    {   
        
        $thistenant = Tenant::findOrFail($tid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse=House::findOrFail($id);
        return view('addtenanthouse', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse'));
    }


    public function reassigntenant($tid,$id)
    {   
        
        $thistenant = Tenant::findOrFail($tid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse=House::findOrFail($id);
        
        $reassignhouse='';
        return view('reassigntenanthouse', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse','reassignhouse'));
    }

     public function reassigntenanthse($tid,$id,$hid)
    {   
        
        $thistenant = Tenant::findOrFail($tid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse=House::findOrFail($id);
        $reassignhouse=House::findOrFail($hid);
        return view('reassigntenanthouse', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse','reassignhouse'));
    }

    public function transfertenanthere($tid,$id)
    {   
        
        $thistenant = Tenant::findOrFail($tid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse=House::findOrFail($id);
        $transferthistenant="";
        return view('transfertenant', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse','transferthistenant'));
    }


    public function transfertenanthereid($tid,$id,$transfer)
    {   
        
        $thistenant = Tenant::findOrFail($tid);
        $propertyinfo = Property::all();
        $tenantsinfo = Tenant::all();
        $houseinfo= House::where('Status','Vacant')->get();
        $thishouse=House::findOrFail($id);
        $transferthistenant=Tenant::findOrFail($transfer);
        return view('transfertenant', compact('thistenant','propertyinfo','tenantsinfo','houseinfo','thishouse','transferthistenant'));
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

    public static function TenantONames($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Oname'];
            }
        return $resultname;
    }

    public static function TenantIdno($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['IDno'];
            }
        return $resultname;
    }

    public static function TenantHuduma($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['HudumaNo'];
            }
        return $resultname;
    }

    public static function TenantPhone($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Phone'];
            }
        return $resultname;
    }

    public static function TenantStatus($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Status'];
            }
        return $resultname;
    }

    public static function TenantEmail($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Email'];
            }
        return $resultname;
    }

    public static function TenantGender($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Gender'];
            }
        return $resultname;
    }

    public static function TenantCreated($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['created_at'];
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


    public static function getMonthWaterDate($yearmonth){
        $explomonth=explode(' ', $yearmonth);
        $years=$explomonth[0];
        $months=$explomonth[1];
        $yearmonthday=$years.'-'.$months.'-1';
        $month=date_format(date_create($yearmonthday),'Y F');
        return $month;
    }

    public static function getTenantPhone($id){
        $results = Tenant::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Phone'];
            }
        return $resultname;
    }

    public static function dateToMonthName($yearmonth){
        $month=date_format(date_create($yearmonth),'d M, Y');
        return $month;
    }

    public static function getCurrentMonth(){
        $watermonth =date("Y n",strtotime("-1 months",strtotime(date("Y-m")."-01")));
        return $watermonth;
    }

    public static function getCurrentMonthReport(){
        $watermonth =date("Y n");
        return $watermonth;
    }


    public static function getAddWaterMonths($id,$watermonth){
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
                if ($watermonth==$month) {
                    echo '<option value="/properties/add/waterbill/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/add/waterbill/'.$id.'/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }


    public static function getAddWaterMonthsThis(){
        $endyear=date('Y');
        $endmonth=date('n');
        return $endyear.' '.$endmonth;
    }

    public static function getAddWaterOtherMonths($watermonth){
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
                if ($watermonth==$month) {
                    echo '<option value="/properties/add/waterbill/Others/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/add/waterbill/Others/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }
    public static function getViewWaterMonths($id,$watermonth){
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
                if ($watermonth==$month) {
                    echo '<option value="/properties/view/messages/waterbill/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/view/messages/waterbill/'.$id.'/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }

    public static function getViewWaterMonthsThis(){
        $endyear=date('Y');
        $endmonth=date('n');
        return $endyear.' '.$endmonth;
    }

    public static function getViewOtherMonths($id,$watermonth){
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
                if ($watermonth==$month) {
                    echo '<option value="/properties/view/messages/others/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/view/messages/others/'.$id.'/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }

    public static function getViewOtherMonthsThis(){
        $endyear=date('Y');
        $endmonth=date('n');
        return $endyear.' '.$endmonth;
    }

    public static function getUploadWaterMonths($id,$watermonth){
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
                // if ($watermonth==$month) {
                //     echo '<option value="/properties/upload/waterbill/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                // }
                // else{
                //     echo '<option value="/properties/upload/waterbill/'.$id.'/'.$month.'">'.$monthname.'</option>';
                // }
                if ($watermonth==$month) {
                    echo '<option value="/properties/update/waterbill/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/update/waterbill/'.$id.'/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }

    public static function getUpdateWaterMonths($id,$watermonth){
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
                if ($watermonth==$month) {
                    echo '<option value="/properties/update/waterbill/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/update/waterbill/'.$id.'/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }

    

    public static function getUpdateBillsMonths($id,$watermonth){
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
                if ($watermonth==$month) {
                    echo '<option value="/properties/update/bills/'.$id.'/'.$month.'" selected>'.$monthname.'</option>';
                }
                else{
                    echo '<option value="/properties/update/bills/'.$id.'/'.$month.'">'.$monthname.'</option>';
                }
                
            }
        }
    }


    public static function reassignAmount($amount){
        if ($amount>0) {
           echo '<dt class="col-sm-7">Arrears</dt>
             <dd class="col-sm-5">'.$amount.'</dd>';
        }
        else{
            echo '<dt class="col-sm-7">Excess</dt>
             <dd class="col-sm-5">'.abs($amount).'</dd>';
        }
        
    }

    public static function reassignAmountStatus($amount){
        // reassignAmountStatus((($reassignhouse->Deposit+$reassignhouse->Water+$reassignhouse->Kplc)-($thishouse->Deposit+$thishouse->Water+$thishouse->Kplc)+500))
        if ($amount>0) {
           echo '<input type="hidden" name="Arrears" value="'.$amount.'">
                <input type="hidden" name="Excess" value="0.00">';
        }
        else{
            echo '<input type="hidden" name="Arrears" value="0.00">
                <input type="hidden" name="Excess" value="'.abs($amount).'">';
        }
        
    }

    public static function vacateRefund($refund){
        if ($refund>0) {
            return 'Owes('.$refund.')';
        }
        else{
            return abs($refund);
        }
    }

    public static function getSentDate($id,$month,$current_water){
        $current='Current:'.$current_water;
        $datesent=WaterMessage::query()
                    ->where('House','=',$id)
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

    

    public static function checkCurrentAssigned($id,$hid,$assigned){
        $currentlyassigned=DB::table('agreements')->where([
            'Tenant'=>$id,
            'House'=>$hid,
            'Month'=>0
        ])->max('DateAssigned');
        if ($currentlyassigned==$assigned) {
            return 'Current';
        }
        else{
            return 'Vacated Here';
        }
    }

    public static function getRecordedStatus($id,$month){
        $thiswaterbill=DB::table('waters')->where([
                'House'=>$id,
                'Month'=>$month
            ])->get();
        if ($thiswaterbill!='[]') {
           return 'Recorded';
        }
        else{
            return 'Not Recorded';
        }
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

    public static function getMonthsPrev(){
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $startyearnow=$endyear-1;
        $currentdate= date('Y n');
        $endmonth=12;
        $paymentmonths='';
        for ($i=$startyear; $i <= $endyear; $i++) { 
            if ($i==2019) {
                // $thisend=date('n');
                // if ($thisend=>9) {
                //     // code...
                // }
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
            for ($m=$startmonth; $m <=$endmonth ; $m++) { 
                $month= $i.' '.$m;
                $monthname=Property::getMonthDateAddWater($month);
                $monthly=Property::getMonthDateDash($month);
                $yearly=Property::getYearDateDash($month);
                
                // echo '<option value="/properties/upload/waterbill'.$month.'" selected>'.$monthly.' '.$yearly.'</option>';
                $thispayment='';
                if ($currentdate==$month) {
                    $thispayment='
                        <button class="btn btn-info text-xs p-1 active m-0 monthlywater" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link" href="#">
                                <span class="page-month">'.$monthly.', '.$yearly.'</span>
                            </a>
                        </button>';
                    $paymentmonths=$thispayment.$paymentmonths;
                }
                else{
                    $thispayment='
                        <button class="btn btn-default text-xs p-1 m-0 monthlywater" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link" href="#">
                                <span class="page-month">'.$monthly.', '.$yearly.'</span>
                            </a>
                        </button>';
                    $paymentmonths=$thispayment.$paymentmonths;
                }
                
            }
        }
        
        echo $paymentmonths;
    }

    public static function getMonthsPaymentPrev(){
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $startyearnow=$endyear-1;
        $currentdate= date('Y n');
        $endmonth=12;
        $paymentmonths='';
        for ($i=$startyear; $i <= $endyear; $i++) { 
            if ($i==2019) {
                // $thisend=date('n');
                // if ($thisend=>9) {
                //     // code...
                // }
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
            for ($m=$startmonth; $m <=$endmonth ; $m++) { 
                $month= $i.' '.$m;
                $monthname=Property::getMonthDateAddWater($month);
                $monthly=Property::getMonthDateDash($month);
                $yearly=Property::getYearDateDash($month);
                
                // echo '<option value="/properties/upload/waterbill'.$month.'" selected>'.$monthly.' '.$yearly.'</option>';
                $thispayment='';
                if ($currentdate==$month) {
                    $thispayment='
                        <button class="btn btn-success text-xs p-1 active m-0 monthlypayments" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link" href="#">
                                <span class="page-month">'.$monthly.', '.$yearly.'</span>
                            </a>
                        </button>';
                    $paymentmonths=$thispayment.$paymentmonths;
                }
                else{
                    $thispayment='
                        <button class="btn btn-default text-xs p-1 m-0 monthlypayments" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link" href="#">
                                <span class="page-month">'.$monthly.', '.$yearly.'</span>
                            </a>
                        </button>';
                    $paymentmonths=$thispayment.$paymentmonths;
                }
                
            }
        }
        echo $paymentmonths;
    }


    

    public static function getMonths(){
        $startyear=2019;
        $startmonth=1;
        $endyear=date('Y');
        $startyear=$endyear-1;
        $currentdate= date('Y n');
        $endmonth=12;
        for ($i=$startyear; $i <= $endyear; $i++) { 
            if ($i==$startyear) {
                // $thisend=date('n');
                // if ($thisend=>9) {
                //     // code...
                // }
                    $startmonth=10;
            }
            else{
                $startmonth=1;
            }
            // return $startmonth;
            if ($i==$endyear) {
                $endmonth=date('n')+5;
            }
            else{
                $endmonth=12;
            }
            for ($m=$startmonth; $m <=$endmonth ; $m++) { 
                $month= $i.' '.$m;
                $monthname=Property::getMonthDateAddWater($month);
                $monthly=Property::getMonthDateDash($month);
                $yearly=Property::getYearDateDash($month);
                
                // echo '<option value="/properties/upload/waterbill'.$month.'" selected>'.$monthly.' '.$yearly.'</option>';
                if ($currentdate==$month) {
                    echo '<li class="active monthlywater btn btn-xs btn-info m-0 p-0" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link p-1" href="#">
                                <p class="page-month m-0 p-0 text-lg">'.$monthly.'</p>
                                <p class="page-year m-0 p-0">'.$yearly.'</p>
                            </a>
                        </li>';
                }
                else if ($month>$currentdate) {
                    echo '<li class="" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link p-1 bg-light" href="#" disabled style="cursor:cursor;">
                                <p class="page-month m-0 p-0 text-lg">'.$monthly.'</p>
                                <p class="page-year m-0 p-0">'.$yearly.'</p>
                            </a>
                        </li>';
                }
                else{
                    echo '<li class="monthlywater" data-id0="'.$month.'" data-id1="'.$monthly.'" data-id2="'.$yearly.'" data-id3="'.$currentdate.'">
                            <a class="page-link p-1" href="#">
                                <p class="page-month m-0 p-0 text-lg">'.$monthly.'</p>
                                <p class="page-year m-0 p-0 text-xs">'.$yearly.'</p>
                            </a>
                        </li>';
                }
                
            }
        }
    }

    public static function getMonthsWaterLast4(){
        $month1=Carbon::now();
        $month2=$month1->copy()->subMonth();
        $month3=$month2->copy()->subMonth();

        $thismonth1=Carbon::parse($month1)->format('Y n');
        $thismonth2=Carbon::parse($month2)->format('Y n');
        $thismonth3=Carbon::parse($month3)->format('Y n');

        $thismonthly1=Carbon::parse($month1)->format('M');
        $thismonthly2=Carbon::parse($month2)->format('M');
        $thismonthly3=Carbon::parse($month3)->format('M');

        $thisyearly1=Carbon::parse($month1)->format('Y');
        $thisyearly2=Carbon::parse($month2)->format('Y');
        $thisyearly3=Carbon::parse($month3)->format('Y');
        echo    
            '
            <li class="page-item monthlywater" data-id0="'.$thismonth3.'" data-id1="'.$thismonthly3.'" 
                                data-id2="'.$thisyearly3.'" data-id3="'.$thismonth1.'">
                <a href="#" class="page-link text-xs">'.$thismonthly3.'</a>
            </li>
            <li class="page-item monthlywater" data-id0="'.$thismonth2.'" data-id1="'.$thismonthly2.'" 
                                data-id2="'.$thisyearly2.'" data-id3="'.$thismonth1.'">
                <a href="#" class="page-link text-xs">'.$thismonthly2.'</a>
            </li>
            <li class="page-item active monthlywater btn btn-xs btn-info m-0 p-0" data-id0="'.$thismonth1.'" data-id1="'.$thismonthly1.'" 
                                data-id2="'.$thisyearly1.'" data-id3="'.$thismonth1.'">
                <a href="#" class="page-link text-xs">'.$thismonthly1.'</a>
            </li>
            ';
    }

    public static function getMonthsPaymentsLast4(){
        $month1=Carbon::now();
        $month2=$month1->copy()->subMonth();
        $month3=$month2->copy()->subMonth();

        $thismonth1=Carbon::parse($month1)->format('Y n');
        $thismonth2=Carbon::parse($month2)->format('Y n');
        $thismonth3=Carbon::parse($month3)->format('Y n');

        $thismonthly1=Carbon::parse($month1)->format('M');
        $thismonthly2=Carbon::parse($month2)->format('M');
        $thismonthly3=Carbon::parse($month3)->format('M');

        $thisyearly1=Carbon::parse($month1)->format('Y');
        $thisyearly2=Carbon::parse($month2)->format('Y');
        $thisyearly3=Carbon::parse($month3)->format('Y');

        echo    
            '
            <li class="page-item monthlypayments" data-id0="'.$thismonth3.'" data-id1="'.$thismonthly3.'" 
                                data-id2="'.$thisyearly3.'" data-id3="'.$thismonth1.'">
                <a href="#" class="page-link text-xs">'.$thismonthly3.'</a>
            </li>
            <li class="page-item monthlypayments" data-id0="'.$thismonth2.'" data-id1="'.$thismonthly2.'" 
                                data-id2="'.$thisyearly2.'" data-id3="'.$thismonth1.'">
                <a href="#" class="page-link text-xs">'.$thismonthly2.'</a>
            </li>
            <li class="page-item active monthlypayments btn btn-xs btn-success m-0 p-0" data-id0="'.$thismonth1.'" data-id1="'.$thismonthly1.'" 
                                data-id2="'.$thisyearly1.'" data-id3="'.$thismonth1.'">
                <a href="#" class="page-link text-xs">'.$thismonthly1.'</a>
            </li>
            ';
    }

    

    public function getMonthlyBills(Request $request)
    {
        
        $propertyinfo = Property::where('Waterbill','Monthly')->get();
        $month=$request->input('month');
        $curmonth='';
        if ($month==0) {
            $month=date('Y n');
            $curmonth=date('M, Y');
        }
        else{
            $curmonth=$this->getMonthDate($month);
        }
        $water_data= array();
        $sno=0;
        foreach ($propertyinfo as $properties) { 
            $sno++;
            $totals=$this->getTotalTotal($properties->id,$month)+$this->getTotalTotal_OS($properties->id,$month);
            $water_data[] = array(
                'sno'=>$sno,
                'id' => $properties->id,
                'plotcode' => $properties->Plotcode,
                'plotname' => $properties->Plotname,
                'curmonth' => $curmonth,
                'curmonth' => $curmonth,
                'totalbillshse' => $this->getTotalBillsHse($properties->id,$month),
                'totalhouseshse' =>$this->getTotalHousesHse($properties->id),
                'totals' =>$totals,
                'totalunits' => $this->getTotalUnits($properties->id,$month),
                'totalbillsmsghse' => $this->getTotalBillsMsgHse($properties->id,$month),
                'totalbillsmsgsentoncehse' => $this->getTotalBillsMsgSentOnceHse($properties->id,$month),
                'totalbillsmsgsenttwicehse' => $this->getTotalBillsMsgSentTwiceHse($properties->id,$month),
                'totalbillsmsgsentthricehse'=>$this->getTotalBillsMsgSentThriceHse($properties->id,$month),
                'month' => $month
            );
        }
        $output=$water_data;
        return compact('output','curmonth','month');
        // echo $output;
        //use json
        
    }

    public function getMonthlyPaymentBills(Request $request)
    {
        $propertyinfo = Property::all();
        $month=$request->input('month');
        // $month='2022 4';
        $curmonth='';
        if ($month==0) {
            $month=date('Y n');
            $curmonth=date('M, Y');
        }
        else{
            $curmonth=$this->getMonthDate($month);
        }

        // $allpayments=DB::table('payments')->where([
        //     'Month'=>$month])->get();
        // foreach($allpayments as $payment){
        //     $Plot=$payment->Plot;
        //     Payment::where('Plot',$Plot)->update(['Arrears'=>0.00,'Excess'=>0.00]);
        // }

        // $allpayments=DB::table('payments')->where([
        //         'Plot'=>Null])->get();
        // foreach($allpayments as $payment){
        //     $House=$payment->House;
        //     $Tenant=$payment->Tenant;
        //     $Plot=Property::getHouseProperty($House);
        //     Payment::where('House',$House)->update(['Plot'=>$Plot]);
        //     echo $Plot.' '.$House.' '.$Tenant.'<br>';
        // }

        $water_data= array();
        $payments= array();
        $sno=0;
        foreach ($propertyinfo as $properties) { 
            $sno++;
            $Plot=$properties->id;
            // $TotalHousesPayment=$this->getTotalHousesPayment($properties->id);
            $TotalHousesOccupied=$this->getTotalHousesHse($properties->id);
            $TotalHousesPayment=0;
            $totals=$this->getTotalTotal($properties->id,$month)+$this->getTotalTotal_OS($properties->id,$month);
            $Rent=0;$Water=0;$Garbage=0;$Lease=0;$HseDeposit=0;$KPLC=0;$Waterbill=0;$Arrears=0;
            $Excess=0;$Equity=0;$Cooperative=0;$Others=0;$PaidUploaded=0;$Penalty=0;
            $allpayments=DB::table('payments')->where([
                'Plot'=>$Plot,
                'Month'=>$month])->get();
            foreach($allpayments as $payment){
                $TotalHousesPayment++;
                $Rent=          $Rent+$payment->Rent;
                $Water=         $Water+$payment->Water;
                $Garbage=       $Garbage+$payment->Garbage;
                $Lease=         $Lease+$payment->Lease;
                $HseDeposit=    $HseDeposit+$payment->HseDeposit;
                $KPLC=          $KPLC+$payment->KPLC;
                $Waterbill=     $Waterbill+$payment->Waterbill;
                $Arrears=       $Arrears+$payment->Arrears;
                $Excess=        $Excess+$payment->Excess;
                $Equity=        $Equity+$payment->Equity;
                $Cooperative=   $Cooperative+$payment->Cooperative;
                $Others=        $Others+$payment->Others;
                $PaidUploaded=  $PaidUploaded + $payment->PaidUploaded;
                $Penalty=       $payment->Penalty;
            }

            $TotalUsed=$Rent+$Water+$Garbage+$Lease+$HseDeposit+$KPLC+$Waterbill+$Arrears+$Penalty;
            $TotalPaid=$Excess+$Equity+$Cooperative+$Others+$PaidUploaded;
            $Balance=$TotalUsed-$TotalPaid;
            $payments[] = array(
                'sno'=>$sno,
                'id' => $Plot,
                'Plotcode' => $properties->Plotcode,
                'Plotname' => $properties->Plotname,
                'Rent' => $Rent,
                'Garbage' => $Garbage,
                'KPLC' => $KPLC,
                'HseDeposit' => $HseDeposit,
                'Water' => $Water,
                'Lease' => $Lease,
                'Month' => $month,
                'curmonth' => $curmonth,
                'WaterbillPrev' => $totals,
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
                'TotalPayment' => $TotalHousesPayment,
                'TotalOccupied' => $TotalHousesOccupied
            );
        }
        $output=$payments;
        return compact('output','curmonth','month');
       
    }

    public static function getTotalHousesPayment($id){
        $Totals=DB::table('payments')->where([
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

    public static function getTotalBillsMsgSentOnceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=$house->id;
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

    public static function getTotalBillsMsgSentTwiceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=$house->id;
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

    public static function getTotalBillsMsgSentThriceHse($id,$month){
        $houses=House::where('Plot',$id)->get();
        $Totals=0;
        foreach($houses as $house){
            $hse=$house->id;
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

    public static function getTotalUnits($id,$month){
        $Units=DB::table('waters')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->sum('Units');
        return $Units;
    }

    public static function getTotalPrevious($id,$month){
        $Previous=DB::table('waters')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->sum('Previous');
        return $Previous;
    }
    
    public static function getTotalCurrent($id,$month){
        $Current=DB::table('waters')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->sum('Current');
        return $Current;
    }

    public static function getTotalTotal($id,$month){
        $Total=DB::table('waters')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->sum('Total');
        return $Total;
    }

    public static function getTotalTotal_OS($id,$month){
        $Total_OS=DB::table('waters')->where([
            'Plot'=>$id,
            'Month'=>$month
        ])->sum('Total_OS');
        return $Total_OS;
    }

    public function getTenantsDetails(){
        $tenants=Tenant::orderByDesc('id')->get();
        return json_encode($tenants);
    }

    public function getPropertyDetails(){
        $property=Property::orderByDesc('id')->get();
        return json_encode($property);
    }

    public function getHousesDetails(){
        $houses=House::orderByDesc('id')->get();
        return json_encode($houses);
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

    public function updateTenantReassign($tid,$hid,$fromhid){
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

    

    public static function getPropertyId($id){
        $results = House::where('id',$id)->get();
        $resultname='';
            foreach($results as $result){
               $resultname= $result['Plot'];
            }
        return $resultname;
    }

}