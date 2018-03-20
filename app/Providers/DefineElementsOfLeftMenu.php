<?php

namespace App\Providers;

use App\Models\Lang;
use function foo\func;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\AdminUserActionPermision;

class DefineElementsOfLeftMenu extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['view']->composer('*', function ($view) {

            $lang_list = Lang::where('active', 1)->get();

            if (Auth::check()) {
                    $user_group_id = Auth::user()->admin_user_group_id;

                    $menu = Module::with(['translation'])->get();


                    $modules_name = Module::where(
                        'src', Request::segment(3))
                        ->first();


                    $modules_sumbenu_name = Module::where(
                        'src', Request::segment(4))
                        ->first();

                    if (!is_null($modules_name)) {
                        $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
                            ->where('modules_id', $modules_name->id)
                            ->first();
                    } elseif (!is_null($modules_sumbenu_name)) {
                        $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
                            ->where('modules_id', $modules_sumbenu_name->id)
                            ->first();
                    } else {
                        $groupSubRelations = [];
                    }

            } else {
                $menu = [];
                $modules_name = [];
                $modules_sumbenu_name = [];
                $groupSubRelations = [];
            }
            $view->menu = $menu;
            $view->modules_name = $modules_name;
            $view->modules_submenu_name = $modules_sumbenu_name;
            $view->lang_list = $lang_list;
            $view->groupSubRelations = $groupSubRelations;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
