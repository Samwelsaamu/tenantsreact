<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property;

class Payment extends Model
{
    use HasFactory;

    protected $fillable=[
		'Plot',
    	'House',
		'Tenant',
		'Excess',
		'Arrears',
		'Month',
		'Rent',
		'Garbage',
		'KPLC',
		'HseDeposit',
		'Water',
		'Lease',
		'Waterbill',
		'Equity',
		'Cooperative',
		'Others',
		'PaidUploaded',
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
