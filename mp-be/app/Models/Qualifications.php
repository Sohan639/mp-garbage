<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualifications extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'qualification_id';

    protected $table = 'mp_qualifications';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
