<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'health_id';

    protected $table = 'mp_health_data';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}