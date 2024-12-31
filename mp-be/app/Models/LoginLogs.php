<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLogs extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'id';

    protected $table = 'mp_login_logs';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
