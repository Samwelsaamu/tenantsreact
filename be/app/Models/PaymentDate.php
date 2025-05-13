<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property;

class PaymentDate extends Model
{
    use HasFactory;
    protected $fillable=[
        'Plot',
        'House',
        'Tenant',
        'Amount',
        'Month',
        'Date_Transacted',
        'Through',
        'Pid',
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
