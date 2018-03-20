<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::with('translation')->get();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag();
        $tag->save();

        foreach ($this->langs as $lang):
            $tag->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::with('translation')->findOrFail($id);

        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $tag->translations()->delete();

        foreach ($this->langs as $lang):
            $tag->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'Item has been updated!');

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('tags.index');
    }
}
