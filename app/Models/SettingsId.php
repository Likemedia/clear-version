<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SettingsId extends Model
{
    protected $table = 'settings_id';

    protected $fillable = [
        'alias', 'set_type'
    ];

}