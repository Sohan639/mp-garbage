<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaster extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'asset_id';

    protected $table = 'mp_assets';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
