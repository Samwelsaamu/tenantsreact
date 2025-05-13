<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    use HasFactory;

    protected $fillable=[
    	'Bank',
		'Trans_Date',
		'Value_Date',
		'Bank_Ref_No',
		'Customer_Ref_No',
		'Description',
		'Debit',
		'Credit',
		'House',
		'UniqTrans',
    ];
}
