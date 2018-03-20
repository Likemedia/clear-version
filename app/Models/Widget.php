<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $fillable = [
        'widget_id', 'lang_id', 'title', 'content'
    ];

    public function itemId()
    {
        return $this->hasOne('App\Models\WidgetId', 'id', 'widget_id');
    }
}
