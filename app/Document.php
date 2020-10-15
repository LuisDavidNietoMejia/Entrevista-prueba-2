<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Document extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'content','user_id','created_by', 'modified_by',
    ];

}
