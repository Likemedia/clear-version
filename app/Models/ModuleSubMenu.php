<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSubMenu extends Model
{
    protected $table = 'modules_submenu';

    public function modules(){
        return $this->hasOne('App\Models\Module', 'id', 'module_id');
    }

}
