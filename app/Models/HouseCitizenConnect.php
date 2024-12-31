<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseCitizenConnect extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'house_citizen_id';

    protected $table = 'mp_house_citizen';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
