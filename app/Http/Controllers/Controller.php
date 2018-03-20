<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Lang;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\User;
use App\Models\AdminUserActionPermision;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests,  DispatchesJobs, ValidatesRequests;

    protected $langs;

    protected $lang;

    public function __construct()
    {
        $this->langs = Lang::all();

        $this->lang = Lang::where('lang', session('applocale') ?? 'ro')->first()->id;

        if (!Auth::check()) {
            return redirect('/auth/login');
        }
    }

//    public function menu()
//    {
//        if (Auth::check()){
//            if(!is_null(Auth::user()->admin_user_group_id)){
//                $user = User::find(Auth::user()->id);
//                $user_group_id = Auth::user()->admin_user_group_id;
//
//                $menuIds = array_keys($user->group()->first()
//                    ->userPermission()->get(['modules_id'])
//                    ->groupBy(['modules_id'])
//                    ->toArray()
//                );
//
//                $menu = Module::with('modules_submenu')
//                    ->orderBy('position', 'asc')
//                    ->findMany($menuIds);
//
//                $modules_name = Module::where(
//                    'src', Request::segment(3))
//                    ->first();
//
//                $modules_sumbenu_name = Module::where(
//                    'src', Request::segment(4))
//                    ->first();
//
////                SubRelations (new, save, active, del_to_rec, del_from_rec)
//                if(!is_null($modules_name)) {
//                    $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
//                        ->where('modules_id', $modules_name->id)
//                        ->first();
//                }
//                elseif(!is_null($modules_sumbenu_name)){
//                    $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
//                        ->where('modules_id', $modules_sumbenu_name->id)
//                        ->first();
//                }
//                else{
//                    $groupSubRelations = [];
//                }
//
//            }
//            else {
//                $menu = [];
//                $modules_name = [];
//                $modules_sumbenu_name = [];
//                $groupSubRelations = [];
//            }
//        }
//
//        return get_defined_vars();
//    }


//    public function lang()
//    {
//        $lang_list = Lang::where('active', 1)
//            ->orderBy('position', 'ASC')
//            ->get();
//        $arr_lang = [];
//        $arr_default_lang_id = [];
//        foreach($lang_list as $key => $one_lang){
//            $arr_lang[$one_lang->lang] = $one_lang->lang;
//            $arr_default_lang_id[$one_lang->lang] = $one_lang->default_lang;
//        }
//
//        $lang = Request::segment(1);
//        $default_lang = Lang::whereIn('id', $arr_default_lang_id)->first();
//        if (array_key_exists($lang, $arr_lang)) {
//            Session::set('applocale', $lang);
//        }
//        else{
//            Session::forget('applocale');
//        }
//
//        if (Session::has('applocale') && array_key_exists(Session::get('applocale'), $arr_lang)) {
//            App::setLocale(Session::get('applocale'));
//        }
//        else {
//            App::setLocale($default_lang->lang);
//        }
//
//        $lang = App::getLocale();
//        $lang_id = Lang::where('lang', $lang)->first()->id;
//        $default_lang_id = Lang::where('lang', $default_lang->lang)->first()->id;
//
//        return get_defined_vars();
//    }

    public function getLangById($id)
    {
        $lang_name = Lang::findOrFail($id)->lang;

        return $lang_name;
    }

}
