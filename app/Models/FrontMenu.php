<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FrontMenu extends Model
{
    protected $table = 'front_menu';

    protected $fillable = [
        'front_menu_id', 'lang_id', 'name', 'link'
    ];

    public function frontMenuId()
    {
       return $this->hasOne('App\Models\FrontMenuId', 'id', 'front_menu_id');
    }
}
