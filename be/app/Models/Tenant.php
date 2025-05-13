<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable=[
    	'Fname',
		'Oname',
		'Gender',
		'IDno',
		'HudumaNo',
		'Phone',
		'Email',
		'Status',
    ];

	public function getIdAttribute($value){	
		return Property::encryptText($value);
	}
}
