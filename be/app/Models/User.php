<?php

namespace App\Models;

use Cache;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Property;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens,HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'Fullname',
        'Username',
        'Phone',
        'Idno',
        'Userrole',
        'last_login_at',
        'current_login_at',
        'current_activity_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'last_login_at',
        'current_login_at',
        'current_activity_at',

    ];

    // public function getIdAttribute($value){	
	// 	return Property::encryptText($value);
	// }

    public function getCurrentLoginAtAttribute($value){	
        if($value){
          return  Carbon::parse($value)->diffForHumans();
        }
        else{
          return null;
        }
      }
  
      public function getLastLoginAtAttribute($value){	
        if($value){
          return  Carbon::parse($value)->diffForHumans();
        }
        else{
          return null;
        }
      }
  
      public function getCurrentActivityAtAttribute($value){	
        if($value){
          return  Carbon::parse($value)->diffForHumans();
        }
        else{
          return null;
        }
      }
  
      public function getEmailVerifiedAtAttribute($value){	
        if($value){
          return  Carbon::parse($value)->diffForHumans();
        }
        else{
          return null;
        }
      }
  
      public function getUserOnlineAttribute($value){	
        $thisid=Property::decryptText($this->id);
          $is= (Cache::has('this-user-is-online-'.$thisid))?1:0;
  
          User::where('id',$thisid)->update(['user_online'=>$is]);
          return $is;
      }


    //check if user is online
    public function isOnline(){
        $thisid=Property::decryptText($this->id);
        return Cache::has('this-user-is-online-'.$thisid);
    }

}
