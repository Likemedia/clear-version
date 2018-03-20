<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSubMenuTranslation extends Model
{
    protected $table = 'modules_submenu_translation';

    public function module() {
        return $this->belongsTo(Module::class);
    }
}
