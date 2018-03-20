<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryId;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use DB;

class GalleryController extends Controller
{
    //base views
    public $createView = 'admin.gallery.create';
    public $showView   = 'admin.gallery.show';
    public $editView   = 'admin.gallery.edit';

    public $foreignKey = 'gallery_id';
    public $modelTrans = 'App\Models\Gallery';
    public $model      = 'App\Models\GalleryId';

    // show  list method
    public function index()
    {
        $view = $this->showView;
        $lang_id = $this->lang()['lang_id'];
        $rowIds = $this->model::orderBy('position', 'asc')->get();

        $array = [];
        foreach($rowIds as $key => $rowId){
            $array[$key] = $this->modelTrans::where($this->foreignKey, $rowId->id)
                                    ->first();
        }

        $rows = array_filter( $array, 'strlen' );
        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k=>$v) {
            $id = str_replace("tablelistsorter[]=","", $v);
            if(!empty($id)){
                $this->model::where('id', $id)->update(['position' => $i]);
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

        $this->model::where('id', $id)->update(['active' => $change_active]);

    }

    // create new item  (get)
    public function create()
    {
        $view = $this->createView;
        $curr_page_id = $this->model::where('alias', Request::segment(4))
            ->first();

        if(!is_null($curr_page_id)){
            $curr_page_id = $curr_page_id->id;
        }
        else {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());
    }

    // edit item (get)
    public function edit($id, $edited_lang_id)
    {
        $view = $this->editView;

        $item_id = $this->model::find($id);
        $item = $this->modelTrans::where('lang_id', $edited_lang_id)
                            ->where($this->foreignKey, $id)
                            ->first();

        if (is_null($item)) {
            $item = $this->modelTrans::create([
                'lang_id' => $edited_lang_id,
                $this->foreignKey => $id,
            ]);
        }
        return view($view, get_defined_vars());
    }

    // save post (update or create)
    public function save($id, $updated_lang_id)
    {
        $validate = Validator::make(Input::all(), [
            'title' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'messages' => $validate->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('clients_id');
        $img = basename(Input::get('file'));

        if(is_null($id)){
            $data = [
                'alias' => Input::get('alias'),
                'position' => $maxPosition + 1,
                'active' => 1,
            ];

            $modelId = $this->model::create($data);

            $data = array_filter([
                $this->foreignKey => $modelId->id,
                'lang_id' => Input::get('lang'),
                'title' => Input::get('title'),
                'img' => $img,
                'img_alt' => Input::get('img_alt'),
                'img_title' => Input::get('img_title'),
            ]);

            $this->modelTrans::create($data);
        }
        else {
            $modelId = $this->model::find($id);

            $model = $this->modelTrans::where($this->foreignKey,
            $modelId->id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = array_filter([
                'alias' => Input::get('alias'),
            ]);

            $modelIdUploaded = $this->model::where('id', $id)
                                        ->update($data);

            $data = array_filter([
                $this->foreignKey => $id,
                'lang_id' => Input::get('lang'),
                'title' => Input::get('title'),
                'img' => $img,
                'img_alt' => Input::get('img_alt'),
                'img_title' => Input::get('img_title'),
            ]);

            if(!is_null($model)){
                $this->modelTrans::where($this->foreignKey, $id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                // $this->modelTrans::create($data);
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
            'redirect' => urlForLanguage($this->lang()['lang'], 'edit/'.$id.'/'.$updated_lang_id)
        ]);
    }


    public function delete($id)
    {
        $item = $this->modelTrans::findOrFail($id);
        $itemId = $this->model::findOrFail($item->{$this->foreignKey});

        if (!is_null($itemId)) {
            if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/s/' . $item->img))
                File::delete('upfiles/' . $this->menu()['modules_name']->src . '/s/' . $item->img);

            if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/m/' . $item->img))
                File::delete('upfiles/' . $this->menu()['modules_name']->src . '/m/' . $item->img);

            if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/' . $item->img))
                File::delete('upfiles/' . $this->menu()['modules_name']->src . '/' . $item->img);

            Session::flash('message', $item->title . '<br />was successful deleted! ');

            $this->modelTrans::where($this->foreignKey, $id)->delete();
            $this->model::where('id', $id)->delete();
        }
        return redirect()->back();
    }

    public function getTableCols($table)
    {
        $colums = [];
        $tables = ['tablename'];

        foreach($tables as $table){
            $table_info_columns = \DB::select( DB::raw('SHOW COLUMNS FROM'.$table));

            foreach($table_info_columns as $key => $column){
                if (($column->Field !== 'id') && ($column->Field !== 'created_at') && ($column->Field !== 'updated_at')) {
                    $colums[$key][] = $column->Field;
                    $colums[$key][] = $column->Type;
                }
            }
        }
        return $colums;
    }

}
