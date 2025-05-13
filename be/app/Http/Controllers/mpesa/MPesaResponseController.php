<?php

namespace App\Http\Controllers\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Property;

class MPesaResponseController extends Controller
{
    //
    public function validation(Request $request){
        Log::Info('Validation Endpoint hit');
        Log::Info($request->all());
    }

    public function confirmation(Request $request){
        Log::Info('Confirmation Endpoint hit');
        Log::Info($request->all());
    }
}
