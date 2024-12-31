<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthStatus extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'health_status_id';

    protected $table = 'mp_health_status';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
