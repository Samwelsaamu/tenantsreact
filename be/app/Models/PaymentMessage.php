<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMessage extends Model
{
    use HasFactory;

    protected $fillable=[
        'Message',
		'To',
		'Status',
		'Cost',
		'Code',
		'MessageId',
		'PatchNo',
		'msgtype',
		'DateSent',
    ];
}
