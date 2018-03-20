<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormTranslation;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Widget;
use App\Models\WidgetId;
use App\Models\Form;
use View;
use DB;


class FormsController extends Controller
{
    public function index()
    {
        $forms = Form::with(['translation' => function($query) {
            $query->where('lang_id', $this->lang);
        }])->get();

        return view('admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'short_code' => 'required'
        ]);

        $form = new Form();
        $form->short_code = $request->short_code;
        $form->save();

        foreach ($this->langs as $lang) :
            $form->translation()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'description' => request('body_' . $lang->lang),
                'code' => request('build_wrap_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('forms.index');
    }

    public function edit($id) {

        $form = Form::with('translation')->findOrFail($id);

        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'short_code' => 'required'
        ]);

        $form = Form::findOrFail($id);
        $form->short_code = $request->short_code;
        $form->save();

        $form->translation()->delete();

        foreach ($this->langs as $lang) :
            $form->translation()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'description' => request('body_' . $lang->lang),
                'code' => request('build_wrap_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'Item has been updated!');

        return redirect()->route('forms.index');
    }

    public function destroy($id) {
        Form::findOrFail($id)->delete();

        Session::flash('message', 'Item has been deleted!');

        return redirect()->route('forms.index');
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
        return $content_new;
    }

}
