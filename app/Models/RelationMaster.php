<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationMaster extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'relation_id';

    protected $table = 'mp_relationship_master';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
