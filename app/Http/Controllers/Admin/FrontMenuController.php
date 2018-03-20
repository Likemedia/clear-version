<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserActionPermision;
use App\Models\Module;
use App\FrontUser;
use App\Models\AdminUserGroup;
use App\Models\Menu_id;
use App\Models\Menu;
use App\Models\FrontMenuId;
use App\Models\FrontMenu;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Hash;


class FrontMenuController extends Controller
{
    public function index()
    {
      if(is_null(Request::segment(4))){
          return redirect(Request::url() . '/list');
      }

      $pageIds = Menu_id::where('active', 1)
                    ->where('deleted', 0)
                    ->get();

      $pages = [];

      foreach ($pageIds as $key => $value) {
          $pages[] = Menu::where('lang_id', $this->lang()['lang_id'])
                          ->where('menu_id', $value->id)
                          ->first();
      }

      $menuId = FrontMenuId::where('p_id', 0)->orderBy('position', 'asc')->get();

      $menu = [];

      foreach ($menuId as $key => $value) {
          $menu[] = FrontMenu::with('frontMenuId')
                             ->where('lang_id', $this->lang()['lang_id'])
                             ->where('front_menu_id', $value->id)
                             ->first();
      }

      $data['frMenu'] = $menu;
      $data['pages']  = $pages;

      return view('admin.front-menu.front-menu', $data);
    }

    public function saveitem()
    {
        $langsArr = [];
        $inputs = [];
        foreach (Input::all() as $key => $value) {
            if(strrpos($key, 'name_') !== false){
                $langsArr[] = $key;
                $inputs[$key] = 'required';
            }
        }

        $inputs['p_id'] = 'required';

        $validate = Validator::make(Input::all(), $inputs);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'messages' => $validate->messages(),
            ]);
        }

        $maxPos = FrontMenuId::max('position');

        $data = [ 'p_id' => 0, 'position' =>  $maxPos + 1];
        $frMenuId = FrontMenuId::create($data);

        foreach ($langsArr as $key => $langArr) {
            $lang = str_replace('name_', '', $langArr);
            FrontMenu::create([
                'front_menu_id' => $font_menu_id->id,
                'lang_id' =>  $lang,
                'name' => Input::get($langArr),
                'page_id' => Input::get('p_id')
            ]);
        }

        return response()->json([
            'status' => true,
            'messages' => ['Saved'],
            'redirect' => url('/' . $this->lang()['lang'] . '/back/front-menu/list'),
        ]);
    }

    public function ajaxrequest()
    {
        $info = json_decode(Input::get('info'));
        // first level
        foreach ($info as $key => $value) {
            FrontMenuId::where('id', $value->id)
                        ->update(['p_id' => 0, 'position' => $key + 1]);
            // second level
            if(isset($value->children)){
                foreach ($value->children as $key1 => $value1) {
                    FrontMenuId::where('id', $value1->id)
                                ->update(['p_id' => $value->id, 'position' => $key1 + 1 ]);
                    // thrid level
                    if(isset($value1->children)){
                        foreach ($value1->children as $key2 => $value2) {
                            FrontMenuId::where('id', $value2->id)
                                        ->update(['p_id' => $value1->id, 'position' => $key2 + 1]);
                            // fourth level
                            if(isset($value2->children)){
                                foreach ($value2->children as $key3 => $value3) {
                                    FrontMenuId::where('id', $value3->id)
                                                ->update(['p_id' => $value2->id, 'position' => $key3 + 1]);
                                    // fifth level
                                    if(isset($value3->children)){
                                        foreach ($value3->children as $key4 => $value4) {
                                            FrontMenuId::where('id', $value4->id)
                                                        ->update(['p_id' => $value3->id, 'position' => $key4 + 1]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function ajaxDeleteMenu()
    {
        $id = Input::get('id');

        FrontMenuId::destroy($id);
        FrontMenu::where('front_menu_id', $id)
                ->delete();

        return response()->json([
            'status' => true,
            'messages' => ['deleted'],
            'redirect' => url('/' . $this->lang()['lang'] . '/back/front-menu/list'),
        ]);
    }

    public function ajaxEditMenu()
    {
        $id = Input::get('id');
        $val = Input::get('val');

        FrontMenu::where('id', $id)
                ->update(['name' => $val]);

        return response()->json([
            'status' => true,
            'messages' => ['deleted'],
            'redirect' => url('/' . $this->lang()['lang'] . '/back/front-menu/list'),
        ]);
    }

}
