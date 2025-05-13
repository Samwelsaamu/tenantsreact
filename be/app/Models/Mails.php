<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    use HasFactory;

    protected $fillable=[
        'mailid',
        'subject',
        'message',
        'from',
        'attachments',
        'folder',
        'thread',
        'status',
    ];
    // public function getIdAttribute($value){	
	// 	return Property::encryptText($value);
	// }

    // public function getFolderAttribute($value){	
	// 	return Property::encryptText($value);
	// }

    // public function getThreadAttribute($value){	
	// 	return Property::encryptText($value);
	// }
}

