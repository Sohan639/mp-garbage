<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseSurvey extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'house_id';

    protected $table = 'mp_house';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
