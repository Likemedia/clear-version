<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoLineId extends Model
{
    protected $table = 'info_line_id';

    protected $fillable = [
        'alias', 'active', 'deleted', 'image', 'position'
    ];
}