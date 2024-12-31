<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    //public $timestamps = false;


    protected $primaryKey = 'file_upload_id';

    protected $table = 'mp_file_upload';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
