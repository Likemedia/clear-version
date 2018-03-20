<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\BannerTop;
use App\Models\BannerTopId;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class SliderController extends Controller
{
    public function index()
    {

        if (Request::isMethod('post')){
            return $this->ajaxRequests();
        }

        $view = 'admin.slider.sliders-list';

        $lang_id = $this->lang()['lang_id'];
        $slider_list_ids = BannerTopId::where('deleted', 0)
            ->orderBy('position', 'asc')
            ->paginate(20);

        $banner_list = [];
        foreach($slider_list_ids as $key => $one_slider_ids){
            $banner_list[$key] = BannerTop::with('lang')
                ->where('banner_top_id' ,$one_slider_ids->id)
                ->first();
        }
        //Remove all null values --start
        $banner_list = array_filter( $banner_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function cartItems()
    {
        $view = 'admin.slider.slider-cart';

        $lang_id = $this->lang()['lang_id'];

        $deleted_banner_id = BannerTopId::where('deleted', 1)
            ->where('active', 0)
            ->get();

        $deleted_banner_list = [];

        foreach($deleted_banner_id as $key => $one_deleted_banner_id){
            $deleted_banner_list[$key] = BannerTop::where('banner_top_id', $one_deleted_banner_id->id)
                ->first();
        }

        $deleted_banner_list = array_filter( $deleted_banner_list, 'strlen' );
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
                BannerTopId::where('id', $id)->update(['position' => $i]);
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
        BannerTopId::where('id', $id)->update(['active' => $change_active]);
    }

    public function createItem()
    {
        $view = 'admin.slider.create-slider';


        return view($view, get_defined_vars());
    }

    public function editItem($id, $edited_lang_id)
    {
        $view = 'admin.slider.edit-slider';

        $banner_top_without_lang = BannerTop::where('id', $id)
            ->first();

        $banner_top = BannerTop::where('banner_top_id', $banner_top_without_lang->banner_top_id)
            ->where('lang_id', $edited_lang_id)
            ->first();

        if(!is_null($banner_top_without_lang)){
            $banner_top_id = BannerTopId::where('id', $banner_top_without_lang->banner_top_id)
                ->first();
        }
        elseif(!is_null($banner_top)){
            $banner_top_id = BannerTopId::where('id', $banner_top->banner_top_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function save($id, $updated_lang_id)
    {

        $item = Validator::make(Input::all(), [
            'name' => 'required',
            'link' => 'required',
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $active = Input::get('active') == 'on' ? 1 : 0;
        $maxPosition = GetMaxPosition('banner_top_id');
        $img = basename(Input::get('file'));

        if(is_null($id)){
            $data = [
                'position' => $maxPosition + 1,
                'active' => $active,
                'deleted' => 0
            ];

            $banner_top_id = BannerTopId::create($data);

            $data = array_filter([
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'title_h1' => Input::get('title_h1'),
                'title_h2' => Input::get('title_h2'),
                'alt' => Input::get('alt'),
                'title' => Input::get('title'),
                'link' => Input::get('link'),
                'img' => $img,
                'banner_top_id' => $banner_top_id->id,
            ]);

            BannerTop::create($data);
        }
        else {
            $exist_banner_top = BannerTop::where('id', $id)->first();

            $exist_banner_top_by_lang = BannerTop::where('banner_top_id', $exist_banner_top->banner_top_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            BannerTopId::where('id', $exist_banner_top->banner_top_id)
                ->update(['active' => $active]);

            $data = array_filter([
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'title_h1' => Input::get('title_h1'),
                'title_h2' => Input::get('title_h2'),
                'alt' => Input::get('alt'),
                'title' => Input::get('title'),
                'link' => Input::get('link'),
                'img' => $img,
                'banner_top_id' => $exist_banner_top->banner_top_id
            ]);

            if(!is_null($exist_banner_top_by_lang)){
                BannerTop::where('banner_top_id', $exist_banner_top->banner_top_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                BannerTop::create($data);
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
            'redirect' => urlForLanguage($this->lang()['lang'], 'edititem/'.$id.'/'.$updated_lang_id)
        ]);

    }

    public function destroyBanner($id)
    {
        $banner_top = BannerTop::findOrFail($id);
        $banner_top_id = BannerTopId::findOrFail($banner_top->banner_top_id);
        if(!is_null($banner_top_id)){
            if($banner_top_id->deleted == 1 && $banner_top_id->active == 0){
                if(File::exists('upfiles/'.$this->menu()['modules_name']->src.'/s/'.$banner_top->img))
                    File::delete('upfiles/'.$this->menu()['modules_name']->src.'/s/'.$banner_top->img);

                if(File::exists('upfiles/'.$this->menu()['modules_name']->src.'/m/'.$banner_top->img))
                    File::delete('upfiles/'.$this->menu()['modules_name']->src.'/m/'.$banner_top->img);

                if(File::exists('upfiles/'.$this->menu()['modules_name']->src.'/'.$banner_top->img))
                    File::delete('upfiles/'.$this->menu()['modules_name']->src.'/'.$banner_top->img);

                Session::flash('message', $banner_top->name . '<br />was successful deleted! ');

                BannerTopId::destroy($banner_top->banner_top_id);
                BannerTop::destroy($id);
            }
            elseif($banner_top_id->deteled == 0){
                Session::flash('message', $banner_top->name . '<br />was successful added to cart! ');

                BannerTopId::where('id', $banner_top->banner_top_id)
                    ->update(['active' => 0, 'deleted' => 1]);
            }
        }

        return redirect()->back();
    }

    public function restore($id)
    {
        $banner_top = BannerTop::findOrFail($id);
        Session::flash('message', $banner_top->name . '<br />was successful restored! ');

        BannerTopId::where('id', $banner_top->banner_top_id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }

    /**
     * return to another url, if method membersList does not exist
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function membersList()
    {
        return redirect(urlForFunctionLanguage($this->lang()['lang'], ''));
    }

}


