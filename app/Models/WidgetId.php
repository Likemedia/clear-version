<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetId extends Model
{
    protected $table = 'widgets_id';

    protected $fillable = [
        'short_code'
    ];
}
