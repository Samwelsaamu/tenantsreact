<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property;

class House extends Model
{
    use HasFactory;

    protected $fillable=[
    	'Housename',
		'plot',
		'Rent',
		'Deposit',
		'Kplc',
		'Water',
		'Lease',
		'Garbage',
		'Info',
		'Status',
		'DueDay',
    ];

	public function getIdAttribute($value){	
		return Property::encryptText($value);
	}

	public function getPlotAttribute($value){	
		return \Crypt::encryptString($value);
	}

}
