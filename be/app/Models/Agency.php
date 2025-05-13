<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agency;

use App\Models\Property;

class Agency extends Model
{
	
    use HasFactory;

    protected $fillable=[
	    'Names',
		'Address',
		'Town',
		'Phone',
		'Email',
        'islive',
        'apiKey',
        'username',
        'sendfrom',
        'sandapiKey',
        'sandusername',
        'sandsendfrom'
	];

    // public function getApiKeyAttribute($value){	
	// 	return Property::decryptText($value);
	// }
    

	public static function getAgencyName(){
        try { 
            $results = Agency::all();
            $resultname='';
                foreach($results as $result){
                   $resultname= $result['Names'];
                }
            return $resultname;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return 'Oops.Internal Server Error.';
        }
        
    }

    public static function getAgencyEmail(){
        try { 
            $results = Agency::all();
            $resultname='';
                foreach($results as $result){
                   $resultname= $result['Email'];
                }
            return $resultname;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return 'Oops.Internal Server Error.';
        }
    }

    public static function getAgencyPhone(){
        try { 
            $results = Agency::all();
            $resultname='';
                foreach($results as $result){
                   $resultname= $result['Phone'];
                }
            return $resultname;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return 'Oops.Internal Server Error.';
        }
    }

    public static function getAgencyTown(){
        try { 
            $results = Agency::all();
            $resultname='';
                foreach($results as $result){
                   $resultname= $result['Town'];
                }
            return $resultname;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return 'Oops.Internal Server Error.';
        }
    }

    public static function getAgencyAddress(){
        try { 
            $results = Agency::all();
            $resultname='';
                foreach($results as $result){
                   $resultname= $result['Address'];
                }
            return $resultname;
        } catch(\Illuminate\Database\QueryException $ex){ 
            return 'Oops.Internal Server Error.';
        }
    }
    

    public static function getAfricasKey(){
        //  return $apiKey     = "atsk_e2ea0753a6d9a577aa8015915062205f3d9d32f2bbc07f3205dc8dae99195f8b82def8ba";//sandbox
        
        $agencydetail = Agency::first();
        if($agencydetail->islive==0){
            return Property::decryptText($agencydetail->sandapikey);
        }
        else if($agencydetail->islive==1){
            return Property::decryptText($agencydetail->apikey);
        }
        else{
            return '';
        }
    }

    public static function getAfricasUsername(){
        // return $username   = "sandbox";//sandbox

        $agencydetail = Agency::first();
        if($agencydetail->islive==0){
            return Property::decryptText($agencydetail->sandusername);
        }
        else if($agencydetail->islive==1){
            return Property::decryptText($agencydetail->username);
        }
        else{
            return '';
        }

    }

    public static function getAfricasFrom(){
        // return $from   = "28025";//sandbox

        $agencydetail = Agency::first();
        if($agencydetail->islive==0){
            return Property::decryptText($agencydetail->sandsendfrom);
        }
        else if($agencydetail->islive==1){
            return Property::decryptText($agencydetail->sendfrom);
        }
        else{
            return '';
        }
    }
}
