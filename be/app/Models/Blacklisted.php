<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklisted extends Model
{
    use HasFactory;

    protected $fillable=[
        'Phone',
        'Status',
        'House',
        'Tenant',
        'First_Blacklisted',
    ];
}
