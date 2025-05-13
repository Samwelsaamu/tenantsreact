<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property;

class Water extends Model
{
    use HasFactory;

    protected $fillable=[
    	'DateTrans',
		'Description',
		'Month',
		'Previous',
		'Current',
		'Cost',
		'Units',
		'Total',
		'Total_OS',
		'Plot',
		'House',
		'Tenant',
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
}
