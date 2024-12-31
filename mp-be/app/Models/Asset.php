<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'citizen_assets_id';

    protected $table = 'mp_citizen_assets';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
