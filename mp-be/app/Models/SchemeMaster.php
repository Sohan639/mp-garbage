<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeMaster extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'scheme_id';

    protected $table = 'mp_scheme_master';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
