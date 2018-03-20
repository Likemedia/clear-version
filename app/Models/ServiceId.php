<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceId extends Model
{
    protected $table = 'services_id';

    protected $fillable = [
        'alias', 'position', 'active'
    ];
}
