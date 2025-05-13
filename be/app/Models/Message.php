<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\Property;

class Message extends Model
{
    use HasFactory;
    protected $fillable=[
		'Plot',
		'House',
		'Tenant',
		'Month',
    	'Message',
		'To',
		'Status',
		'Cost',
		'Code',
		'MessageId',
		'PatchNo',
		'DateSent',
    ];

	public function getPlotAttribute($value){	
		return Property::encryptText($value);
	}

	public function getHouseAttribute($value){	
		return Property::encryptText($value);
	}

	public function getTenantAttribute($value){	
		return Property::encryptText($value);
	}

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
			return Carbon::parse($value)->setTimezone($timezone)->format('Y-m-d, H:i:s');
		}
		else{
			return null;
		}
	}
}
