<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lang;

class Module extends Model
{
    protected $fillable = [
        'src', 'controller', 'position', 'table_name', 'icon'
    ];

    public function submenu()
    {
        return $this->hasMany(ModuleSubMenu::class, 'module_id', 'id');
    }

    public function translations()
    {
        return $this->hasMany(ModuleTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ModuleTranslation::class)->where('lang_id', $lang);
    }




    public function modulesPermission()
    {
        return $this->hasMany(AdminUserActionPermision::class, 'module_id', 'id');
    }


}
