<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garbage extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'mp_garbage';

    // Specify the primary key
    protected $primaryKey = 'garbage_id';

    // Indicate if the model should be timestamped (created_at and updated_at are already present in the table)
    public $timestamps = true;

    // Specify the fillable columns
    protected $fillable = [
        'house_id',
        'status',
        'latitude',
        'longitude',
        'user_id', // Add user_id to fillable
        'created_at',
        'updated_at'
    ];

    const UPDATED_AT = null;

    // Optionally, customize the date format (if required by your application)
    // protected $dateFormat = 'Y-m-d H:i:s'; // Default Laravel format
}