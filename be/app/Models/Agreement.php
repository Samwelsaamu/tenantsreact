<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable=[
    	'plot',
		'house',
		'tenant',
		'DateAssigned',
		'DateTo',
		'DateVacated',
		'Month',
		'Deposit',
		'Arrears',
		'Damages',
		'Transaction',
		'Refund',
		'Phone',
		'UniqueID',
    ];

	public function getIdAttribute($value){	
		return Property::encryptText($value);
	}

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
