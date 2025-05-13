<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class WaterMessage extends Model
{
    use HasFactory;
    
    protected $fillable=[
    	'House',
		'Tenant',
		'Message',
		'To',
    ];

	public function getHouseAttribute($value){	
		return Property::encryptText($value);
	}

	public function getTenantAttribute($value){	
		return Property::encryptText($value);
	}
}
