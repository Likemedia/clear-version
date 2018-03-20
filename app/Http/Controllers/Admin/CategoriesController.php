<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lang;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class CategoriesController extends Controller
{
    public function __construct()
    {
        $categories = \App\Models\Category::all();
        foreach ($categories as $category):

            $c = \App\Models\Category::where('parent_id', $category->id)->first();

            if (is_null($c)) {
                \App\Models\Category::find($category->id)->update([
                    'level' => 1,
                ]);
            } else {
                \App\Models\Category::find($category->id)->update([
                    'level' => 0,
                ]);
            }
        endforeach;
    }

    public function index()
    {

        return view('admin.categories.index');
    }

    public function create()
    {
        $categories = Category::with('translation')->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {

        $name = time() . '-' . $request->image->getClientOriginalName();
        $request->image->move('images/categories', $name);

        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->image = $name;
        $category->save();

        foreach ($this->langs as $lang):
            $category->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang),
                'alt_attribute' => request('alt_text_' . $lang->lang),
                'image_title' => request('title_' . $lang->lang)
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('categories.index');
    }

    public function edit($id)
    {

        $category = Category::with('translations')->findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->image != null) {
            if (file_exists('/images/categories/' . $category->image)) {
                unlink('/images/categories/' . $category->image);
            }
            $name = time() . '-' . $request->image->getClientOriginalName();
            $request->image->move('images/categories', $name);

            $category->image = $name;
        }

        $category->translations()->delete();

        foreach ($this->langs as $lang):
            $category->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang),
                'alt_attribute' => request('alt_text_' . $lang->lang),
                'image_title' => request('title_' . $lang->lang)
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('categories.index');

        dd($id);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $categories = Category::all();

        foreach ($categories as $cat):
            if ($category->id == $cat->parent_id) {
                session()->flash('message', 'Can\'t delete this item, because it has children.');

                return redirect()->route('categories.index');
            }
        endforeach;

        if (file_exists('/images/categories/' . $category->image)) {
            unlink('/images/categories/' . $category->image);
        }

        $category->delete();

        return redirect()->back();
    }

    public function partialSave(Request $request)
    {
        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->save();

        foreach ($this->langs as $lang):
            $category->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('categories.index');
    }


    public function change()
    {
        $list = Input::get('list');
        $positon = 1;

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $positon++;
                Category::where('id', $value['id'])->update(['parent_id' => 0, 'position' => $positon]);

                if (array_key_exists('children', $value)) {
                    foreach ($value['children'] as $key1 => $value1) {
                        $positon++;
                        Category::where('id', $value1['id'])->update(['parent_id' => $value['id'], 'position' => $positon]);

                        if (array_key_exists('children', $value1)) {
                            foreach ($value1['children'] as $key2 => $value2) {
                                $positon++;
                                Category::where('id', $value2['id'])->update(['parent_id' => $value1['id'], 'position' => $positon]);

                                if (array_key_exists('children', $value2)) {
                                    foreach ($value2['children'] as $key3 => $value3) {
                                        $positon++;
                                        Category::where('id', $value3['id'])->update(['parent_id' => $value2['id'], 'position' => $positon]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }











//    public function store()
//    {
//        if (is_null($id)) {
//            $data = [
//                'title' => Input::get('title'),
//                'slug' =>  Input::get('slug')
//            ];
//
//            $validate = Validator::make($data, [
//                'title.*' => 'required',
//                'slug.*' => 'required|unique:pages,slug'
//            ]);
//        }else{
//            $validate = Validator::make(Input::all(), [
//                'title.*' => 'required',
//                'slug.*' => 'required'
//            ]);
//        }
//
//        if($validate->fails()){
//            Session::flash('errors', $validate->errors()->all());
//            return redirect()->back()->withInput();
//        }
//
//        $maxPosition = GetMaxPosition('pages_id');
//
//        if(is_null($id)){
//            $data = [
//                'alias' => str_slug(Input::get('title')['ru']),
//                'position' => $maxPosition + 1,
//                'active' => 1,
//            ];
//
//            $modelId = GoodsSubjectId::create($data);
//
//
//            if (!empty($this->langs)) {
//                foreach ($this->langs as $key => $lang) {
//                    $key ++;
//                    $data = array_filter([
//                        'goods_subject_id' => $modelId->id,
//                        'lang_id' => $key,
//                        'name' => Input::get('title')[$lang],
//                        'slug' => Input::get('slug')[$lang],
//                    ]);
//
//                    GoodsSubject::create($data);
//                }
//            }
//
//            Session::flash('success', 'Элемент добавлен!');
//            return redirect('ru/back/categories');
//        }
//        else {
//            $modelId = GoodsSubjectId::find($id);
//
//            $data = array_filter([
//                'alias' => str_slug(Input::get('title')['ru']),
//            ]);
//
//            $modelIdUploaded = GoodsSubjectId::where('id', $id)
//                                        ->update($data);
//
//            if (!empty($this->langs)) {
//                foreach ($this->langs as $key => $lang) {
//                    $img = basename(Input::get('file-'.$key));
//
//                    $data = array_filter([
//                        'lang_id' => $lang,
//                        'name' => Input::get('name')[$key],
//                        'descr' => Input::get('descr')[$key],
//                        'slug' => Input::get('slug')[$key],
//                        'body' => Input::get('body')[$key],
//                        'img' => $img,
//                        'meta_title' => Input::get('meta_title')[$key],
//                        'meta_keywords' => Input::get('meta_keywords')[$key],
//                        'meta_descr' => Input::get('meta_descr')[$key]
//                    ]);
//
//                    GoodsSubject::where('goods_subject_id', $id)
//                        ->where('lang_id', $lang)
//                        ->update($data);
//                }
//            }
//        }
//        Session::flash('success', 'Информация обновлена!');
//        return redirect()->back();
//    }


    public function saveSubject1($id, $updated_lang_id)
    {
        dd(Input::all());
        if (is_null($id)) {
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:goods_subject_id',
            ]);
        }

        if ($item->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('goods_subject_id');
        $level = GetLevel(Input::get('parent_id'), 'goods_subject_id');

        if (is_null($id)) {
            $data = [
                'parent_id' => Input::get('parent_id'),
                'level' => $level + 1,
                'alias' => Input::get('alias'),
                'position' => $maxPosition + 1,
                'active' => 1,
                'deleted' => 0,
            ];

            $subject_id = Category::create($data);

            $data = array_filter([
                'goods_subject_id' => $subject_id->id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'body' => Input::get('body'),
                'page_title' => Input::get('title'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description')
            ]);

            CategoryTranslation::create($data);

        }
    }
}
