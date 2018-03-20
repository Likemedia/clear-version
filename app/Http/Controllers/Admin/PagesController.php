<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Lang;
use App\Models\Widget;
use App\Models\WidgetId;
use DB;


class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::with('translation')->orderBy('position', 'asc')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $re = '/\[[a-z_0-9-]+\]/';
        $a = request('body_ru');
        $replacement = "<?php Form::render(''); ?>";

        $aa = preg_replace($re, $replacement, $a);
        dd($aa);


//        $page = new Page();
//        $page->alias = $request->alias;
//        $page->active = $request->active;
//        $page->position = $request->active;
//        $page->save();
//
//        foreach ($this->langs as $lang):
//            $page->translations()->create([
//                'lang_id' => $lang->id,
//                'slug' => request('slug_' . $lang->lang),
//                'title' => request('title_' . $lang->lang),
//                'body' => request('body_' . $lang->lang),
//                'image' => 'tmp',
//                'meta_title' => request('meta_title_' . $lang->lang),
//                'meta_keywords' => request('meta_keywords_' . $lang->lang),
//                'meta_description' => request('meta_description_' . $lang->lang)
//            ]);
//        endforeach;
//
//        Session::flash('message', 'New item has been created!');
//
//        return redirect()->route('pages.index');
    }

    public function edit($id)
    {
//        if (!empty($this->langs)) {
//            foreach ($this->langs as $key => $lang) {
//                $item_id = $this->model::find($id);
//                $oneItem = $this->modelTrans::where('lang_id', $lang->id)
//                    ->where($this->foreignKey, $id)
//                    ->first();
//                if (!is_null($oneItem)) {
//                    $item[$lang->lang] = $oneItem;
//                } else {
//                    $this->modelTrans::create([$this->foreignKey => $id, 'lang_id' => $lang->id]);
//                    $item[$lang->lang] = $this->modelTrans::where('lang_id', $lang->id)
//                        ->where($this->foreignKey, $id)
//                        ->first();
//                }
//            }
//        }
        $page = Page::with('translations')->findOrFail($id);

        return view('admin.pages.edit', compact('page'));
    }






    // ajax response for position
    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            if (!empty($id)) {
                $this->model::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $page = Page::findOrFail($id);


        if ($page->active == 1) {
            $page->active = 0;
        } else {
            $page->active = 1;
        }

        $page->save();

        return redirect()->route('pages.index');
    }


    public function addWidget($contents)
    {
        $wigetsId = WidgetId::get();
        $content_new = $contents;

        if (!empty($wigetsId)) {
            foreach ($wigetsId as $key => $widgetId) {
                if (!empty($contents)) {
                    foreach ($contents as $key => $content) {
                        echo "[" . $widgetId->short_code . "]";
                        $lang = Lang::where('lang', $key)->first();
                        $widget = Widget::where('lang_id', $lang->id)->where('widget_id', $widgetId->id)->first();
                        if (!is_null($widget)) {
                            if (strpos($content, "[$widgetId->short_code]") == true) {
                                // $content_new[$key] = str_replace("[". $widgetId->short_code ."]", 'vdf,l', '<p>[short_code1]</p>');
                                $content_new[$key] = str_replace("[$widgetId->short_code]", $widget->content, $content);
                            }
                            // else{
                            //     $content_new[$key] = $content;
                            // }

                        }
                    }
                }
            }
        }

        // dd($content_new);
        return $content_new;
    }


    // save post (update or create)
    public function save($id, $updated_lang_id)
    {
        $content = $this->addWidget(Input::get('body'));

        // dd($content);

        $langs = Input::get('lang');

        if (is_null($id)) {
            $data = [
                'title' => Input::get('title'),
                'slug' => Input::get('slug')
            ];

            $validate = Validator::make($data, [
                'title.*' => 'required',
                'slug.*' => 'required|unique:pages,slug'
            ]);
        } else {
            $validate = Validator::make(Input::all(), [
                'title.*' => 'required',
                'slug.*' => 'required'
            ]);
        }

        if ($validate->fails()) {
            Session::flash('errors', $validate->errors()->all());
            return redirect()->back()->withInput();
        }

        $maxPosition = GetMaxPosition('pages_id');

        if (is_null($id)) {
            $data = [
                'alias' => str_slug(Input::get('title')['ro']),
                'position' => $maxPosition + 1,
                'active' => 1,
            ];

            $modelId = $this->model::create($data);

            if (!empty($langs)) {
                foreach ($langs as $key => $lang) {
                    $img = basename(Input::get('file-' . $key));

                    $data = array_filter([
                        $this->foreignKey => $modelId->id,
                        'lang_id' => $lang,
                        'title' => Input::get('title')[$key],
                        'descr' => Input::get('descr')[$key],
                        'slug' => Input::get('slug')[$key],
                        'body' => $content[$key],
                        'img' => $img,
                        'meta_title' => Input::get('meta_title')[$key],
                        'meta_keywords' => Input::get('meta_keywords')[$key],
                        'meta_descr' => Input::get('meta_descr')[$key]
                    ]);

                    $this->modelTrans::create($data);
                }
            }

            Session::flash('success', 'Элемент добавлен!');
            return redirect('ru/back/pages');
        } else {
            $modelId = $this->model::find($id);
            $data = array_filter([
                'alias' => str_slug(Input::get('title')['ro']),
            ]);

            $modelIdUploaded = $this->model::where('id', $id)
                ->update($data);

            if (!empty($langs)) {
                foreach ($langs as $key => $lang) {
                    $img = basename(Input::get('file-' . $key));

                    $data = array_filter([
                        'lang_id' => $lang,
                        'title' => Input::get('title')[$key],
                        'descr' => Input::get('descr')[$key],
                        'slug' => Input::get('slug')[$key],
                        'body' => $content[$key],
                        'img' => $img,
                        'meta_title' => Input::get('meta_title')[$key],
                        'meta_keywords' => Input::get('meta_keywords')[$key],
                        'meta_descr' => Input::get('meta_descr')[$key]
                    ]);

                    $this->modelTrans::where($this->foreignKey, $id)
                        ->where('lang_id', $lang)
                        ->update($data);
                }
            }
        }
        Session::flash('success', 'Информация обновлена!');
        return redirect()->back();
    }


    public function delete($id)
    {
        $itemId = $this->model::findOrFail($id);
        if (!is_null($itemId)) {
            $items = $this->modelTrans::where($this->foreignKey, $itemId->id)->get();
            if (!empty($items)) {
                foreach ($items as $key => $item) {
                    if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/s/' . $item->img))
                        File::delete('upfiles/' . $this->menu()['modules_name']->src . '/s/' . $item->img);

                    if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/m/' . $item->img))
                        File::delete('upfiles/' . $this->menu()['modules_name']->src . '/m/' . $item->img);

                    if (File::exists('upfiles/' . $this->menu()['modules_name']->src . '/' . $item->img))
                        File::delete('upfiles/' . $this->menu()['modules_name']->src . '/' . $item->img);

                    $this->modelTrans::where('id', $item->id)->delete();
                }
                $this->model::where('id', $id)->delete();
                Session::flash('success', 'Элемент "' . $item->title . '" удален!');
            }
        }

        return redirect()->back();
    }

    public function getTableCols($table)
    {
        $colums = [];
        $tables = ['tablename'];

        foreach ($tables as $table) {
            $table_info_columns = \DB::select(DB::raw('SHOW COLUMNS FROM' . $table));

            foreach ($table_info_columns as $key => $column) {
                if (($column->Field !== 'id') && ($column->Field !== 'created_at') && ($column->Field !== 'updated_at')) {
                    $colums[$key][] = $column->Field;
                    $colums[$key][] = $column->Type;
                }
            }
        }
        return $colums;
    }

}
