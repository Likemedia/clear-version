<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Menu_id;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index()
    {
        $view = 'admin.menu.elements-list';

        $lang_id = $this->lang()['lang_id'];

        $menu_id_elements = Menu_id::where('level', 1)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();

        $menu_elements = [];
        foreach($menu_id_elements as $key => $menu_id_element){
            $menu_elements[$key] = Menu::where('menu_id', $menu_id_element->id)
                ->first();

        }

        //Remove all null values --start
        $menu_elements = array_filter( $menu_elements, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition()
    {
        $neworder = Input::get('neworder');

        $i = 0;
        $neworder = explode("&", $neworder);
        foreach ($neworder as $k=>$v) {
            $id = str_replace("tablelistsorter[]=","", $v);
            if(!empty($id)){
                Menu_id::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    // ajax response for active
    public function changeActive()
    {
        $active = Input::get('active');
        $id = Input::get('id');

        if($active == 1) {
            $change_active = 0;
        }
        else{
            $change_active = 1;
        }

        Menu_id::where('id', $id)->update(['active' => $change_active]);

    }

    public function createMenu()
    {
        $view = 'admin.menu.create-menu';

        $curr_page_id = Menu_id::where('alias', Request::segment(4))
            ->first();

        if(!is_null($curr_page_id)){
            $curr_page_id = $curr_page_id->id;
        }
        else {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());
    }

    public function editMenu($id, $edited_lang_id)
    {
        $view = 'admin.menu.edit-menu';

        $menu_without_lang = Menu::find($id);

        $menu_elems = Menu::where('lang_id', $edited_lang_id)
            ->where('menu_id', $menu_without_lang->menu_id)
            ->first();

        if(!is_null($menu_without_lang)){
            $menu_id = Menu_id::where('id', $menu_without_lang->menu_id)
                ->first();
        }
        elseif(!is_null($menu_elems)){
            $menu_id = Menu_id::where('id', $menu_elems->menu_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function saveMenu($id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:menu_id'
            ]);
        }
        else {
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required'
            ]);
        }

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('menu_id');
        $level = GetLevel(Input::get('p_id'), 'menu_id');
        $img = basename(Input::get('file'));

        if(is_null($id)){
            $data = [
                'p_id' => Input::get('p_id'),
                'level' => $level + 1,
                'alias' => Input::get('alias'),
                'position' => $maxPosition + 1,
                'active' => 1,
                'deleted' => 0
            ];

            $menu_id = Menu_id::create($data);

            $data = array_filter([
                'menu_id' => $menu_id->id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'body' => Input::get('body'),
                'body2' => Input::get('body2'),
                'body3' => Input::get('body3'),
                'img' => $img,
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description')
            ]);

            Menu::create($data);

        }
        else {
            $exist_menu = Menu::find($id);

            $exist_menu_by_lang = Menu::where('menu_id', $exist_menu->menu_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = array_filter([
                'alias' => Input::get('alias'),
                'p_id' => Input::get('p_id'),
                'level' => $level + 1,
                'page_type' => Input::get('page_type'),
                'controller' => Input::get('controller')
            ]);

            $menu_id = Menu_id::where('id', $exist_menu->menu_id)
                ->update($data);

            $data = array_filter([
                'menu_id' => $exist_menu->menu_id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'link' => Input::get('link'),
                'body' => Input::get('body'),
                'body2' => Input::get('body2'),
                'body3' => Input::get('body3'),
                'img' => $img,
                'page_title' => Input::get('title'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description')
            ]);

            if(!is_null($exist_menu_by_lang)){
                Menu::where('menu_id', $exist_menu->menu_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                Menu::create($data);
            }
        }

        if(is_null($id)){
            if($menu_id->level == 1){
                return response()->json([
                    'status' => true,
                    'messages' => ['Save'],
                    'redirect' => urlForFunctionLanguage($this->lang()['lang'], '')
                ]);
            }
            else {
                return response()->json([
                    'status' => true,
                    'messages' => ['Save'],
                    'redirect' => urlForFunctionLanguage($this->lang()['lang'], GetParentAlias($menu_id->id, 'menu_id').'/memberslist')
                ]);
            }

        }
        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'editmenu/'.$id.'/'.$updated_lang_id)
        ]);
    }

    public function membersList()
    {
        $view = 'admin.menu.child-list';

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;

        $menu_list_id = Menu_id::where('alias', Request::segment(4))
            ->first();

        if(is_null($menu_list_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        $child_menu_list_id = Menu_id::where('p_id', $menu_list_id->id)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();


        $child_menu_list = [];
        foreach($child_menu_list_id as $key => $one_menu_elem){
            $child_menu_list[$key] = Menu::where('menu_id', $one_menu_elem->id)
                ->first();
        }

        //Remove all null values --start
        $child_menu_list = array_filter( $child_menu_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function menuCart()
    {
        $view = 'admin.menu.menu-cart';

        $lang_id = $this->lang()['lang_id'];

        $deleted_elems_by_alias = Menu_id::where('alias', Request::segment(4))
            ->first();

        if(is_null($deleted_elems_by_alias)){
            $deleted_menu_id_elems = Menu_id::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', 0)
                ->get();
        }
        else{
            $deleted_menu_id_elems = Menu_id::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', $deleted_elems_by_alias->id)
                ->get();
        }

        $deleted_menu_elems = [];

        foreach($deleted_menu_id_elems as $key => $one_deleted_menu_elem){
            $deleted_menu_elems[$key] = Menu::where('menu_id', $one_deleted_menu_elem->id)
                ->first();
        }

        $deleted_menu_elems = array_filter( $deleted_menu_elems, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function destroyMenu($id)
    {
        $menu_elems_list = Menu::findOrFail($id);
        $menu_elems_list_id = Menu_id::findOrFail($menu_elems_list->menu_id);
        if (!is_null($menu_elems_list_id)) {
            if (empty(IfHasChild($menu_elems_list_id->id, 'menu_id'))) {
                if ($menu_elems_list_id->deleted == 1 && $menu_elems_list_id->active == 0) {
                    if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/s/' . $menu_elems_list->img))
                        File::delete('upfiles/' . $this->menu()['modules_name']->src . '/s/' . $menu_elems_list->img);

                    if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/m/' . $menu_elems_list->img))
                        File::delete('upfiles/' . $this->menu()['modules_name']->src . '/m/' . $menu_elems_list->img);

                    if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/' . $menu_elems_list->img))
                        File::delete('upfiles/' . $this->menu()['modules_name']->src . '/' . $menu_elems_list->img);

                    Session::flash('message', $menu_elems_list->name . '<br />was successful deleted! ');

                    Menu_id::destroy($menu_elems_list->menu_id);
                    Menu::destroy($id);

                } elseif ($menu_elems_list_id->deleted == 0) {
                    Session::flash('message', $menu_elems_list->name . '<br />was successful added to cart! ');

                    Menu_id::where('id', $menu_elems_list->menu_id)
                        ->update(['active' => 0, 'deleted' => 1]);
                }
            } else {
                Session::flash('error-message', $menu_elems_list->name . '<br />have children! ');
            }
        }

        return redirect()->back();
    }

    public function restoreMenu($id)
    {
        $menu_elems_list = Menu::findOrFail($id);
        Session::flash('message', $menu_elems_list->name . '<br />was successful restored! ');

        Menu_id::where('id', $menu_elems_list->menu_id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }

}
