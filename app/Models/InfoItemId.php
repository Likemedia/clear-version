<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoItemId extends Model
{
    protected $table = 'info_item_id';

    protected $fillable = [
        'info_line_id', 'alias', 'is_public', 'active', 'deleted', 'img', 'add_date'
    ];

    public function infoLineId()
    {
        return $this->hasMany('App\Models\InfoLineId', 'id', 'info_line_id');
    }
}