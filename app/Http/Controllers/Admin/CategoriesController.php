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

        parent::__construct();

        $categories = Category::all();
        foreach ($categories as $category):

            $c = Category::where('parent_id', $category->id)->first();

            if (is_null($c)) {
                Category::find($category->id)->update([
                    'level' => 1,
                ]);
            } else {
                Category::find($category->id)->update([
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

}
