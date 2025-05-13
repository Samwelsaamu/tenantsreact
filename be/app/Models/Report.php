<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Report extends Model
{
    use HasFactory;

    protected $fillable=[
    	'DateTrans',
		'Filename',
		'Type',
		'ReportTo',
    ];


	protected $casts = [
        'created_at'=>'datetime:D, M d, Y, H:i:s',
		'updated_at'=>'datetime:D, M d, Y, H:i:s'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

	public function getCreatedAtAttribute($value){	
		if($value){
			$timezone= 'Africa/Nairobi'; 
			return Carbon::parse($value)->setTimezone($timezone)->format('M d, Y, H:i:s');
		}
		else{
			return null;
		}
	}


	public function getUpdatedAtAttribute($value){	
		if($value){
			$timezone= 'Africa/Nairobi'; 
			return Carbon::parse($value)->setTimezone($timezone)->format('M d, Y, H:i:s');
		}
		else{
			return null;
		}
	}
    
}
