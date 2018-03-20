<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\InfoItem;
use App\Models\InfoLine;
use App\Models\InfoLineId;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\InfoItemId;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class InfoLineController extends Controller
{
    public function index()
    {
        $view = 'admin.infoline.infoline-list';

        $lang_id = $this->lang()['lang_id'];

        $info_line_id = InfoLineId::where('deleted', 0)
            ->orderBy('position', 'asc')
            ->paginate(20);

        $info_line = [];
        foreach($info_line_id as $key => $one_info_line_id){
            $info_line[$key] = InfoLine::where('info_line_id', $one_info_line_id->id)
                ->first();
        }

        //Remove all null values --start
        $info_line = array_filter( $info_line, 'strlen' );
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
                InfoLineId::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    // ajax response for active
    public function changeActive()
    {
        $active = Input::get('active');
        $id = Input::get('id');
        $action = Input::get('action');

        if($active == 1) {
            $change_active = 0;
        }
        else{
            $change_active = 1;
        }
        if($action != ''){
            InfoItemId::where('id', $id)->update(['active' => $change_active]);
        }
        else{
            InfoLineId::where('id', $id)->update(['active' => $change_active]);
        }
    }

    public function createInfoLine()
    {
        $view = 'admin.infoline.create-infoline';

        return view($view, get_defined_vars());
    }

    public function editInfoLine($id, $edited_lang_id)
    {
        $view = 'admin.infoline.edit-infoline';

        $info_line_without_lang = InfoLine::find($id);

        $info_line = InfoLine::where('lang_id', $edited_lang_id)
            ->where('info_line_id', $info_line_without_lang->info_line_id)
            ->first();

        if(!is_null($info_line_without_lang)){
            $info_line_id = InfoLineId::where('id', $info_line_without_lang->info_line_id)
                ->first();
        }
        elseif(!is_null($info_line)){
            $info_line_id = InfoLineId::where('id', $info_line->info_line_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function infoLineCart()
    {
        $view = 'admin.infoline.infoline-cart';

        $lang_id = $this->lang()['lang_id'];

        $deleted_info_line_id = InfoLineId::where('deleted', 1)
            ->where('active', 0)
            ->get();

        $deleted_info_line = [];

        foreach($deleted_info_line_id as $key => $one_deleted_info_line_id){
            $deleted_info_line[$key] = InfoLine::where('info_line_id', $one_deleted_info_line_id->id)
                ->first();
        }

        $deleted_info_line = array_filter( $deleted_info_line, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function saveInfoLine($id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:info_line_id',
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

        $maxPosition = GetMaxPosition('info_line_id');

        if(is_null($id)){
            $data = [
                'position' => $maxPosition + 1,
                'active' => 1,
                'deleted' => 0,
                'alias' => Input::get('alias'),
                'image' => Input::get('file')
            ];

            $info_line_id = InfoLineId::create($data);

            $data = array_filter([
                'info_line_id' => $info_line_id->id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'descr' => Input::get('descr'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description'),
            ]);

            InfoLine::create($data);
        }
        else {
            $exist_info_line = InfoLine::find($id);

            $exist_info_line_by_lang = InfoLine::where('info_line_id', $exist_info_line->info_line_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = array_filter([
                'alias' => Input::get('alias'),
                'image' => Input::get('file')
            ]);

            InfoLineId::where('id', $exist_info_line->info_line_id)
                ->update($data);

            $data = array_filter([
                'info_line_id' => $exist_info_line->info_line_id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'descr' => Input::get('descr'),
                'page_title' => Input::get('title'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description'),
            ]);

            if(!is_null($exist_info_line_by_lang)){
                InfoLine::where('info_line_id', $exist_info_line->info_line_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                InfoLine::create($data);
            }
        }

        if(is_null($id)){
            return response()->json([
                'status' => true,
                'messages' => ['Save'],
                'redirect' => urlForFunctionLanguage($this->lang()['lang'], '')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'editinfoline/'.$id.'/'.$updated_lang_id)
        ]);

    }

    public function destroyInfoLine($id)
    {
        $info_line = InfoLine::findOrFail($id);
        if(!is_null($info_line_id = InfoLineId::findOrFail($info_line->info_line_id))){
            if($info_line_id->deleted == 1 && $info_line_id->active == 0){
                Session::flash('message', $info_line->name . '<br />was successful deleted! ');

                InfoLineId::destroy($info_line->info_line_id);
                InfoLine::destroy($id);
            }
            elseif($info_line_id->deleted == 0){
                Session::flash('message', $info_line->name . '<br />was successful added to cart! ');

                InfoLineId::where('id', $info_line->info_line_id)
                    ->update(['active' => 0, 'deleted' => 1]);
            }
        }

        return redirect()->back();
    }

    public function restoreInfoLine($id)
    {
        $info_line = InfoLine::findOrFail($id);
        Session::flash('message', $info_line->name . '<br />was successful restored! ');

        InfoLineId::where('id', $info_line->info_line_id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }

    public function membersList()
    {
        $view = 'admin.infoline.infoitems-list';

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;


        $lang_id = $this->lang()['lang_id'];

        $info_line_id = InfoLineId::where('alias', Request::segment(4))
            ->first();

        if(is_null($info_line_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        $info_items_list = InfoItemId::where('deleted', 0)
            ->where('info_line_id', $info_line_id->id)
            ->paginate(20);

        $info_item = [];
        foreach($info_items_list as $key => $one_info_tem){
            $info_item[$key] = InfoItem::where('info_item_id', $one_info_tem->id)
                ->first();
        }

        $topTitle = 'Категория - ' . getInfoLineName(Request::segment(4), $lang_id);
        $topSrc = url($lang.'/back/info_line/'.Request::segment(4).'/editinfoline/'.getInfoLineId(Request::segment(4), $lang_id).'/'.$lang_id);

        //Remove all null values --start
        $info_item = array_filter( $info_item, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

     public function all()
    {
        $view = 'admin.infoline.infoitems-list';

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;


        $lang_id = $this->lang()['lang_id'];

        $info_items_list = InfoItemId::where('deleted', 0)
            ->paginate(20);

        $info_item = [];
        foreach($info_items_list as $key => $one_info_tem){
            $info_item[$key] = InfoItem::where('info_item_id', $one_info_tem->id)
                ->first();
        }

        $topTitle = "Все новости";
        $topSrc = url($lang.'/back/info_line');

        //Remove all null values --start
        $info_item = array_filter( $info_item, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function createInfoItem()
    {
        $view = 'admin.infoline.create-infoitem';

        return view($view, get_defined_vars());
    }

    public function editInfoItem($id, $edited_lang_id)
    {
        $view = 'admin.infoline.edit-infoitem';

        $info_item_without_lang = InfoItem::find($id);

        $info_item = InfoItem::where('lang_id', $edited_lang_id)
            ->where('info_item_id', $info_item_without_lang->info_item_id)
            ->first();

        if(!is_null($info_item_without_lang)){
            $info_item_id = InfoItemId::where('id', $info_item_without_lang->info_item_id)
                ->first();
            $info_line_id = InfoLineId::where('id', $info_item_id->info_line_id)->first();
        }
        elseif(!is_null($info_item)){
            $info_item_id = InfoItemId::where('id', $info_item->info_item_id)
                ->first();
            $info_line_id = InfoLineId::where('id', $info_item_id->info_line_id)->first();
        }

        return view($view, get_defined_vars());
    }

    public function infoItemsCart()
    {
        $view = 'admin.infoline.infoitems-cart';

        $lang_id = $this->lang()['lang_id'];

        $deleted_info_item_id = InfoItemId::where('deleted', 1)
            ->where('active', 0)
            ->get();

        $deleted_info_item = [];

        foreach($deleted_info_item_id as $key => $one_deleted_info_item_id){
            $deleted_info_item[$key] = InfoItem::where('info_item_id', $one_deleted_info_item_id->id)
                ->first();
        }

        $deleted_info_item = array_filter( $deleted_info_item, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function saveInfoItem($id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:info_item_id'
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

        $info_line_id = InfoLineId::where('alias', Request::segment(4))
            ->first()->id;

        if(is_null($info_line_id))
            return App::abort(503, 'Unauthorized action.');

        $img = basename(Input::get('file'));
        if(!empty(Input::get('add_date'))){
            $add_date = date('Y-m-d', strtotime(Input::get('add_date')));
        }
        else{
            $add_date = '';
        }

        if(is_null($id)){
            $data = [
                'info_line_id' => $info_line_id,
                'alias' => Input::get('alias'),
                'is_public' => Input::get('is_public') == 'on' ? 1 : 0,
                'active' => 1,
                'deleted' => 0,
                'add_date' => $add_date,
                'img' => $img,
            ];

            $info_item_id = InfoItemId::create($data);

            $data = array_filter([
                'info_item_id' => $info_item_id->id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'descr' => Input::get('descr'),
                'body' => Input::get('body'),
                'author' => Input::get('author'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description'),
                'tag1' => Input::get('tag1'),
                'tag2' => Input::get('tag2'),
                'tag3' => Input::get('tag3'),
                'tag4' => Input::get('tag4'),
                'tag5' => Input::get('tag5'),
            ]);
            InfoItem::create($data);
        }
        else {
            $exist_info_item = InfoItem::find($id);

            $exist_info_item_by_lang = InfoItem::where('info_item_id', $exist_info_item->info_item_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = [
                'alias' => Input::get('alias'),
                'is_public' => Input::get('is_public') == 'on' ? 1 : 0,
                'add_date' => $add_date,
                'img' => $img,
            ];

            InfoItemId::where('id', $exist_info_item->info_item_id)
                ->update($data);

            $data = array_filter([
                'info_item_id' => $exist_info_item->info_item_id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'descr' => Input::get('descr'),
                'body' => Input::get('body'),
                'author' => Input::get('author'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description'),
                'tag1' => Input::get('tag1'),
                'tag2' => Input::get('tag2'),
                'tag3' => Input::get('tag3'),
                'tag4' => Input::get('tag4'),
                'tag5' => Input::get('tag5'),
            ]);

            if(!is_null($exist_info_item_by_lang)){
                InfoItem::where('info_item_id', $exist_info_item->info_item_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                InfoItem::create($data);
            }
        }

        if(is_null($id)){
            return response()->json([
                'status' => true,
                'messages' => ['Save'],
                'redirect' => urlForLanguage($this->lang()['lang'], 'memberslist')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'editinfoitem/'.$id.'/'.$updated_lang_id)
        ]);
    }

    public function destroyInfoItem($id)
    {
        $info_item = InfoItem::findOrFail($id);
        $info_item_id = InfoItemId::findOrFail($info_item->info_item_id);
        if(!is_null($info_item_id)){
            if($info_item_id->deleted == 1 && $info_item_id->active == 0){
                if(File::exists('upfiles/'.$this->menu()['modules_name']->src.'/s/'.$info_item_id->img))
                    File::delete('upfiles/'.$this->menu()['modules_name']->src.'/s/'.$info_item_id->img);

                if(File::exists('upfiles/'.$this->menu()['modules_name']->src.'/m/'.$info_item_id->img))
                    File::delete('upfiles/'.$this->menu()['modules_name']->src.'/m/'.$info_item_id->img);

                if(File::exists('upfiles/'.$this->menu()['modules_name']->src.'/'.$info_item_id->img))
                    File::delete('upfiles/'.$this->menu()['modules_name']->src.'/'.$info_item_id->img);

                Session::flash('message', $info_item->name . '<br />was successful deleted! ');

                InfoItemId::destroy($info_item->info_item_id);
                InfoItem::destroy($id);
            }
            elseif($info_item_id->deleted == 0){
                Session::flash('message', $info_item->name . '<br />was successful added to cart! ');

                InfoItemId::where('id', $info_item->info_item_id)
                    ->update(['active' => 0, 'deleted' => 1]);
            }
        }

        return redirect()->back();
    }

    public function restoreInfoItem($id)
    {
        $info_item = InfoItem::findOrFail($id);
        Session::flash('message', $info_item->name . '<br />was successful restored! ');

        InfoItemId::where('id', $info_item->info_item_id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }

}
