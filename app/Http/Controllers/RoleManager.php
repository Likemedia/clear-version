<?php

namespace App\Http\Controllers;

use App\Models\AdminUserActionPermision;
use App\Models\AdminUserGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests;
use App\Models\Module;
use App\Models\ModuleSubMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class RoleManager extends Controller
{
    public function __construct()
    {
        Route::currentRouteAction();
    }

    public function routeResponder($lang, $module, $submenu = null, $action = 'index', $id = null, $lang_id = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $module = Module::where('src', $module)->first();

        $submodule = ModuleSubMenu::where('src', $submenu)->first();

        if (!empty($submodule->controller)) {
            $controller = app()->make('App\Http\Controllers\Admin\\' . $submodule->controller);
            return app()->call([$controller, $action], [$id, $lang_id]);
        } elseif (!empty($module->controller)) {
            $controller = app()->make('App\Http\Controllers\Admin\\' . $module->controller);
            return app()->call([$controller, $action], [$id, $lang_id]);
        } else {
            return redirect($lang . '/back');
        }
    }
}
