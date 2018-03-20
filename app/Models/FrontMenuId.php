<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FrontMenuId extends Model
{
    protected $table = 'front_menu_id';

    protected $fillable = [
        'p_id', 'position', 'target'
    ];

}