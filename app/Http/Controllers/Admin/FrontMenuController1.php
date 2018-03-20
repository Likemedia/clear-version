<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserActionPermision;
use App\Models\Module;
use App\FrontUser;
use Illuminate\Http\Request;
use App\Models\AdminUserGroup;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Hash;
use App\Models\FrontMenu;
use App\Models\FrontMenuId;
use App\Models\Lang as AllLangs;


class FrontMenuController  extends Controller
{
    public function index()
    {
        $frontMenuIds = FrontMenuId::where('p_id', 0)->get();

        $frontMenus = [];
        if (!empty($frontMenuIds)) {
            foreach ($frontMenuIds as $key => $frontMenuId) {
                $frontMenus[] = FrontMenu::where('lang_id', $this->lang()['lang_id'])
                                        ->where('front_menu_id', $frontMenuId->id)
                                        ->first();
            }
        }

        $data['frontMenus'] =  array_filter($frontMenus);
    	$data['frontMenuIds'] =  $frontMenuIds;
    	return view('admin.frontMenu.index', $data);
    }

    public function create()
    {
    	$data = [];
    	return view('admin.frontMenu.create', $data);
    }

    public function save()
    {
        $langs = AllLangs::get();

    	$item = Validator::make(Input::all(), [
                'name' => 'required',
                'link' => 'required'
            ]);

    	if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = FrontMenuId::max('position');

        if (is_null($maxPosition)) {
            $maxPosition = 0;
        }

        $frontMenuId = FrontMenuId::create([
                            'p_id' => '0',
                            'position' => $maxPosition,
                            'target' => Input::get('target')
                        ]);

        foreach ($langs as $key => $lang) {
            if (Input::get('lang') == $lang->id) {
                FrontMenu::create([
                    'front_menu_id' => $frontMenuId->id,
                    'lang_id' => Input::get('lang'),
                    'name' => Input::get('name'),
                    'link' => Input::get('link'),
                ]);
            }else{
                FrontMenu::create([
                    'front_menu_id' => $frontMenuId->id,
                    'lang_id' => $lang->id,
                    'name' => '',
                    'link' => '',
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'messages' => ['Saved'],
            'redirect' => url($this->lang()['lang_id'].'/back/front_menu')
        ]);

    }

    public function changePosition()
    {
        dd(Input::all());
    }
}
