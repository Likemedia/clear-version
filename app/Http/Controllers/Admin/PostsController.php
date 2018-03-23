<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('translation')->get();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('level', 1)->get();

        $tags = Tag::with('translation')->get();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $name = time() . '-' . $request->image->getClientOriginalName();
//        $request->image->move('images/posts', $name);

        $post = new Post();
        $post->category_id = $request->category_id;
//        $post->image = $name;
        $post->save();

        foreach ($this->langs as $lang):
            $post->translations()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
                'url' => request('url_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang),
            ]);

//            if ($request->tags)
        endforeach;

        if ($request->tags != null) {

            foreach ($request->tags as $tag):
                $post->tags()->attach($tag);
            endforeach;

        }



        session()->flash('message', 'New item has been created!');

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ( file_exists('/images/posts'.$post->image)) {
            unlink('/images/posts'.$post->image);
        }

        $post->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('posts.index');
    }

    public function getPostsByCategory($categoryId)
    {
      $posts = Post::where('category_id', $categoryId)->with('translation')->get();

      return view('admin.posts.index', compact('posts'));
    }
}
