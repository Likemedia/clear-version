<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Router;


class LanguagesController extends Controller
{

    public function index()
    {


// dd(app('router')->getRoutes());

        $languages = Lang::all();

        return view('admin.languages.index', compact('languages'));
    }

    public function delete($id)
    {
        Lang::findOrFail($id)->delete();

        return back();
    }

    public function create()
    {
        // dd(app('router')->getRoutes());
        $langs = Lang::get();

        return view('admin.languages.create', compact('langs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|size:2|alpha',
            'description' => 'required|alpha'
        ]);

        $language = new Lang();
        $language->lang = $request->name;
        $language->descr = $request->description;
        $language->active = 1;
        $language->save();

        return redirect()->route('languages.index');

        dd($request->all());
    }

    public function destroy($id)
    {
        $lang = Lang::findOrFail($id);

        if ($lang->default == 1) {
            session('flash', "Can't delete default language");

            return back();
        }
        $lang->delete();

        return back();
    }

    public function default($id)
    {
        $current = Lang::where('default_lang', '1')->first();
        $current->default_lang = 0;
        $current->save();

        $new = Lang::findOrFail($id);
        $new->default_lang = 1;
        $new->save();

        return back();
    }

}
