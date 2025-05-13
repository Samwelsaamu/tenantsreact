<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserLogs extends Model
{
    use HasFactory;

    protected $fillable=[
    	'User',
		'Message',
    ];

    

    // public function getCreatedAtAttribute($value){	
    //   if($value){
    //     return  Carbon::parse($value)->diffForHumans();
    //   }
    //   else{
    //     return null;
    //   }
    // }

    public function getCreatedAtAttribute($value){	
      if($value){
        $timezone= 'Africa/Nairobi'; 
        // 2025-03-15 19:30:56
        return Carbon::parse($value)->setTimezone($timezone)->format('Y-m-d H:i:s');
      }
      else{
        return null;
      }
    }
}
