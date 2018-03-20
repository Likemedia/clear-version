<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_id extends Model
{
    protected $table = 'menu_id';

    protected $fillable = [
        'level', 'alias', 'position', 'level', 'active', 'deleted', 'p_id'
    ];


    public function menu(){
        return $this->hasMany('App\Models\Menu', 'menu_id', 'id');
    }
}
