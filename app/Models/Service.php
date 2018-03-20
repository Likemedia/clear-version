<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'services_id', 'lang_id', 'title', 'img', 'body', 'item1', 'item2', 'item3', 'item4', 'meta_title', 'meta_descr', 'meta_keywords',
    ];

    public function servicesId()
    {
        return $this->hasOne('App\Models\ServiceId', 'id', 'services_id');
    }

    public function itemId()
    {
        return $this->hasOne('App\Models\ServiceId', 'id', 'services_id');
    }
}
