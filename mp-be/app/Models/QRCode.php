<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'mp_qr_code';
    protected $primaryKey = 'qr_code_id';
    public $timestamps = false;

    protected $fillable = [
        'qr_id', 'house_id', 'status', 'created_at', 'created_by'
    ];
}