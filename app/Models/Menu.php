<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'menu_id', 'lang_id', 'name', 'slug','img', 'body', 'body1', 'body2', 'body3', 'h1_title', 'meta_title', 'meta_keywords', 'meta_description'
    ];

    public function menuId(){
        return $this->hasOne('App\Models\Menu_id', 'id', 'menu_id');
    }


}
