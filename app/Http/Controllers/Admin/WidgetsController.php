<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - index()
* - changePosition()
* - changeActive()
* - create()
* - edit()
* - save()
* - delete()
* - getTableCols()
* Classes list:
* - WidgetsController extends Controller
*/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Lang;
use View;
use DB;

class WidgetsController extends Controller
{
    //base views
    public $createView = 'admin.widgets.create';
    public $showView = 'admin.widgets.show';
    public $editView = 'admin.widgets.edit';

    public $foreignKey = 'widget_id';
    public $modelTrans = 'App\Models\Widget';
    public $model = 'App\Models\WidgetId';

    public function __construct()
    {
        $langs = Lang::get();
        View::share('langs', $langs);
    }

    // show  list method
    public function index()
    {
        $view = $this->showView;
        $lang_id = $this->lang() ['lang_id'];
        $rowIds = $this->model::get();

        $array = [];
        foreach ($rowIds as $key => $rowId)
        {
            $array[$key] = $this->modelTrans::where($this->foreignKey, $rowId->id)
                ->first();
        }

        $rows = array_filter($array, 'strlen');
        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v)
        {
            $id = str_replace("tablelistsorter[]=", "", $v);
            if (!empty($id))
            {
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

        if ($active == 1)
        {
            $change_active = 0;
        }
        else
        {
            $change_active = 1;
        }

        $this->model::where('id', $id)->update(['active' => $change_active]);

    }

    // create new item  (get)
    public function create()
    {
        $view = $this->createView;
        $curr_page_id = $this->model::where('short_code', Request::segment(4))
            ->first();

        if (!is_null($curr_page_id))
        {
            $curr_page_id = $curr_page_id->id;
        }
        else
        {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());
    }

    // edit item (get)
    public function edit($id, $edited_lang_id)
    {
        $langs = Lang::get();
        $view = $this->editView;

        if (!empty($langs))
        {
            foreach ($langs as $key => $lang)
            {
                $item_id = $this->model::find($id);
                $oneItem = $this->modelTrans::where('lang_id', $lang->id)
                    ->where($this->foreignKey, $id)->first();
                if (!is_null($oneItem))
                {
                    $item[$lang->lang] = $oneItem;
                }
                else
                {
                    $this->modelTrans::create([$this->foreignKey => $id, 'lang_id' => $lang->id]);
                    $item[$lang
                        ->lang] = $this->modelTrans::where('lang_id', $lang->id)
                        ->where($this->foreignKey, $id)->first();
                }
            }
        }

        return view($view, get_defined_vars());
    }

    // save post (update or create)
    public function save($id, $updated_lang_id)
    {
        // dd(Input::all());
        $langs = Input::get('lang');

        if (is_null($id))
        {
            $data = ['title' => Input::get('title'), 'short_cut' => Input::get('short_cut') ];

            $validate = Validator::make($data, ['title.*' => 'required', 'short_cut.*' => 'required|unique:widgets_id,short_cut']);
        }
        else
        {
            $validate = Validator::make(Input::all(), ['title.*' => 'required', 'short_cut.*' => 'required']);
        }

        if ($validate->fails())
        {
            Session::flash('errors', $validate->errors()
                ->all());
            return redirect()
                ->back()
                ->withInput();
        }

        $maxPosition = GetMaxPosition('pages_id');

        if (is_null($id))
        {
            $data = ['short_code' => Input::get('short_code'), ];

            $modelId = $this->model::create($data);

            if (!empty($langs))
            {
                foreach ($langs as $key => $lang)
                {
                    $img = basename(Input::get('file-' . $key));

                    $data = array_filter([$this->foreignKey => $modelId->id, 'lang_id' => $lang, 'title' => Input::get('title') [$key], 'content' => Input::get('body') [$key], ]);

                    $this->modelTrans::create($data);
                }
            }

            Session::flash('success', 'Элемент добавлен!');
            return redirect('ru/back/widgets');
        }
        else
        {
            $modelId = $this->model::find($id);
            $data = array_filter(['short_code' => Input::get('short_code'), ]);

            $modelIdUploaded = $this->model::where('id', $id)->update($data);

            if (!empty($langs))
            {
                foreach ($langs as $key => $lang)
                {
                    $data = array_filter(['lang_id' => $lang, 'title' => Input::get('title') [$key], 'content' => Input::get('body') [$key], ]);

                    $this->modelTrans::where($this->foreignKey, $id)->where('lang_id', $lang)->update($data);
                }
            }
        }
        Session::flash('success', 'Информация обновлена!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $itemId = $this->model::findOrFail($id);
        if (!is_null($itemId))
        {
            $items = $this->modelTrans::where($this->foreignKey, $itemId->id)
                ->get();
            if (!empty($items))
            {
                foreach ($items as $key => $item)
                {
                    if (File::exists('upfiles/' . $this->menu() ['modules_name']->src . '/s/' . $item->img)) File::delete('upfiles/' . $this->menu() ['modules_name']->src . '/s/' . $item->img);

                    if (File::exists('upfiles/' . $this->menu() ['modules_name']->src . '/m/' . $item->img)) File::delete('upfiles/' . $this->menu() ['modules_name']->src . '/m/' . $item->img);

                    if (File::exists('upfiles/' . $this->menu() ['modules_name']->src . '/' . $item->img)) File::delete('upfiles/' . $this->menu() ['modules_name']->src . '/' . $item->img);

                    $this->modelTrans::where('id', $item->id)
                        ->delete();
                }
                $this->model::where('id', $id)->delete();
                Session::flash('success', 'Элемент "' . $item->title . '" удален!');
            }
        }

        return redirect()
            ->back();
    }

    public function getTableCols($table)
    {
        $colums = [];
        $tables = ['tablename'];

        foreach ($tables as $table)
        {
            $table_info_columns = \DB::select(DB::raw('SHOW COLUMNS FROM' . $table));

            foreach ($table_info_columns as $key => $column)
            {
                if (($column->Field !== 'id') && ($column->Field !== 'created_at') && ($column->Field !== 'updated_at'))
                {
                    $colums[$key][] = $column->Field;
                    $colums[$key][] = $column->Type;
                }
            }
        }
        return $colums;
    }

}
