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


class HouseController extends Controller
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
        $storeData = $request->validate([
            'Housename' => ['required', 'string', 'max:150'],
            'Plot' => ['required', 'integer'],
            'Rent' => ['required', 'integer'],
            'Deposit' => ['required', 'integer'],   
            'Kplc' => ['required', 'integer'],
            'Water' => ['required', 'integer'],
            'Lease' => ['required', 'integer'],
            'Garbage' => ['required', 'integer'],
            'DueDay' => ['required', 'integer', 'max:31'],
        ]);
        $plotid=$request->input('Plot');
        $plotcode=$request->input('Plotcode');
        Property::setUserLogs('Saving New House for Property '. $plotcode);
         try { 
            $houseinfo = new House;
            $houseinfo->Housename =$plotcode.'-'.$request->input('Housename');
            $houseinfo->Plot =$request->input('Plot');
            $houseinfo->Rent =$request->input('Rent');
            $houseinfo->Deposit =$request->input('Deposit');
            $houseinfo->Kplc =$request->input('Kplc');
            $houseinfo->Lease =$request->input('Lease');
            $houseinfo->Water =$request->input('Water');
            $houseinfo->Garbage =$request->input('Garbage');
            $houseinfo->DueDay =$request->input('DueDay');
            $houseinfo->save();
            Property::setUserLogs('New House '.$request->input('Housename').' Information Saved for Property '. $plotcode);
                return redirect("/properties/newhouse/{$plotid}")->with('success', 'House Information Saved!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                Property::setUserLogs('New House '.$request->input('Housename').' Information Not Saved for Property '. $plotcode.' with Error: '.$ex->getMessage());
                return redirect("/properties/newhouse/{$plotid}")->with('dbError', $ex->getMessage());
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
        $thishouse = House::findOrFail($id);
        Property::setUserLogs('Viewing House '.Property::getHouseName($id).' to Update');
        return view('updatehouse', compact('propertyhouses','propertyinfo','tenantsinfo','thishouse'));
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
            Property::setUserLogs('Updating House '.Property::getHouseName($id).' to Update');
         $updateHouse = $request->validate([
            'Housename' => ['required', 'string', 'max:150'],
            'Plot' => ['required', 'integer'],
            'Rent' => ['required', 'integer'],
            'Deposit' => ['required', 'integer'],   
            'Kplc' => ['required', 'integer'],
            'Water' => ['required', 'integer'],
            'Lease' => ['required', 'integer'],
            'Garbage' => ['required', 'integer'],
            'DueDay' => ['required', 'integer', 'max:31'],
        ]);
            House::whereId($id)->update($updateHouse);
            Property::setUserLogs('House '.Property::getHouseName($id).' Updated');
            return redirect('/house/'.$id.'/edit')->with('success', 'House has been updated');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            Property::setUserLogs('House '.Property::getHouseName($id).' Not Updated');
            return redirect('/house/'.$id.'/edit')->with('dbError', $ex->getMessage());
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
        $property = House::findOrFail($id);
        $property->delete();
        Property::setUserLogs('House '.Property::getHouseName($id).' Deleted');
        return redirect('/properties/houses')->with('success', 'House has been deleted');
    }

    public function saveHouse(Request $request)
    {
        $storeData = $request->validate([
            'Housename' => ['required', 'string', 'max:150'],
            'Plotid' => ['required', 'integer'],
            'Rent' => ['required', 'integer'],
            'Deposit' => ['required', 'integer'],   
            'Kplc' => ['required', 'integer'],
            'Water' => ['required', 'integer'],
            'Lease' => ['required', 'integer'],
            'Garbage' => ['required', 'integer'],
            'DueDay' => ['required', 'integer', 'max:31'],
        ]);

        $plotid=$request->input('Plotid');
        $plotcode=$request->input('Plotcode');
        Property::setUserLogs('Saving New House for Property '. $plotcode);

         try { 
            $houseinfo = new House;
            $houseinfo->Housename =$request->input('Housename');
            $houseinfo->Plot =$request->input('Plotid');
            $houseinfo->Rent =$request->input('Rent');
            $houseinfo->Deposit =$request->input('Deposit');
            $houseinfo->Kplc =$request->input('Kplc');
            $houseinfo->Lease =$request->input('Lease');
            $houseinfo->Water =$request->input('Water');
            $houseinfo->Garbage =$request->input('Garbage');
            $houseinfo->DueDay =$request->input('DueDay');
            if($houseinfo->save()){
                Property::setUserLogs('New House '.$request->input('Housename').' Information Saved for Property '. $plotcode);
                $success='<span>House Information Saved!</br>';
                return compact('success');
            }
            else{
                $error='<span>Information for House Not Saved for Property '. $plotcode.'</br>';
                return compact('error');
            }
        } 
        catch(\Illuminate\Database\QueryException $ex){ 
            $errors=$ex->getMessage();
            $beingusederror='1062 ';
            $error='<span>House Was Not Saved</br>'.$ex->getMessage();
            if (preg_match("/$beingusederror/i", $errors)) {
                $error='<span>House is Already Saved</br>';
            }
            return compact('error');
        }
        catch(\Exception $ex){ 
            $error='<span>House Not Saved</br>'.$ex->getMessage();
            return compact('error');
        }
    }
}
