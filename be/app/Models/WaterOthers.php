<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterOthers extends Model
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
		'Tenant',
	];
}
