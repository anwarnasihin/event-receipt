<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [

        'app_name',

        'company_name',

        'app_url',

        'timezone',

        'logo',

        'qr_size',

        'enable_webcam',

        'auto_capture',

        'capture_delay',

    ];
}
