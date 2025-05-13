<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentsOthers extends Model
{
    use HasFactory;

    protected $fillable=[
		'Tenant',
		'Excess',
		'Arrears',
		'Month',
		'Waterbill',
		'Equity',
		'Cooperative',
		'Others',
		'PaidUploaded',
    ];
}
