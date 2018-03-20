<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoItem extends Model
{
    protected $table = 'info_item';

    protected $fillable = [
        'info_item_id', 'lang_id', 'name', 'descr', 'body', 'author', 'h1_title', 'meta_title', 'meta_keywords', 'meta_description', 'tag1', 'tag2', 'tag3', 'tag4', 'tag5',  
    ];

    public function infoItemId()
    {
        return $this->hasOne('App\Models\InfoItemId', 'id', 'info_item_id');
    }
}
