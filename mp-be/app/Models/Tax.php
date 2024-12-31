<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'tax_id';

    protected $table = 'mp_taxes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
