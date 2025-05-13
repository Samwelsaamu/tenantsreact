<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\Property;


class AgencyController extends Controller
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
        
        Property::setUserLogs('Viewing Agency Information Page');
        $agencyinfo = Agency::all();
        $propertyinfo = Property::all();
        return view('agencyinfo',compact('agencyinfo','propertyinfo'));
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
            'Names' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'string', 'email', 'max:255'],
            'Phone' => ['required', 'string', 'min:9'],
            'Address' => ['required', 'string', 'max:255'],
            'Town' => ['required', 'string', 'max:255'],
        ]);
        Property::setUserLogs('Saving agency Information');
         try { 
            $agencyinfo = new Agency;
            $agencyinfo->Names =$request->input('Names');
            $agencyinfo->Email =$request->input('Email');
            $agencyinfo->Phone =$request->input('Phone');
            $agencyinfo->Address =$request->input('Address');
            $agencyinfo->Town =$request->input('Town');
            $agencyinfo->save();
            Property::setUserLogs('Agency Information Saved');
                return redirect('/agencyinfo')->with('success', 'Agency Information Saved!');
            } catch(\Illuminate\Database\QueryException $ex){ 
              // dd($ex->getMessage()); 
                Property::setUserLogs('Agency Information not Saved with Error : '.  $ex->getMessage());
                return redirect('/agencyinfo')->with('dbError', $ex->getMessage());
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
        
        try { 
            Property::setUserLogs('Updating agency Information');
           $updateData = $request->validate([
                'Names' => ['required', 'string', 'max:255'],
                'Email' => ['required', 'string', 'email', 'max:255'],
                'Phone' => ['required', 'string', 'min:9'],
                'Address' => ['required', 'string', 'max:255'],
                'Town' => ['required', 'string', 'max:255'],
            ]);
            Agency::whereId($id)->update($updateData);
            Property::setUserLogs('Agency Information Updated');
            return redirect('/agencyinfo')->with('success', 'Agency Information updated!');
        } catch(\Illuminate\Database\QueryException $ex){ 
          // dd($ex->getMessage()); 
            Property::setUserLogs('Agency Information not Updated with Error : '.  $ex->getMessage());
            return redirect('/agencyinfo')->with('dbError', $ex->getMessage());
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
        //
    }
}
