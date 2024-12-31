<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'citizen_scheme_id';

    protected $table = 'mp_citizen_scheme';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
