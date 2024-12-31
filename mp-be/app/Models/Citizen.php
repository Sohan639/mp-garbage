<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Citizen extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    //public $timestamps = false;


    protected $primaryKey = 'citizen_id';

    protected $table = 'mp_citizen';

    protected $fillable = [
        'email_id',
        'citizen_password',
    ];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
