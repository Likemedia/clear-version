<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsItem;
use App\Models\GoodsItemId;
use App\Models\CategoryTranslation;
use App\Models\Category;
use App\Models\ParameterId;
use App\Models\Parameter;
use App\Models\ParameterGoods;
use App\Models\Lang;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\GoodsPhoto;
use Illuminate\Support\Facades\File;
use View;

class GoodsController extends Controller
{
    // //base views
    // public $createView = 'admin.goods.create';
    // public $showView   = 'admin.goods.show';
    // public $editView   = 'admin.goods.edit';
    //
    // public $foreignKey = 'page_id';
    // public $modelTrans = 'App\Models\Page';
    // public $model      = 'App\Models\PageId';

    public function __construct()
    {
        $langs = Lang::get();
        View::share('langs', $langs);
    }

    public function memberlist()
    {
        $view = 'admin.goods.goods-list';

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;

        $goods_subject_id_list = Category::where('deleted', 0)
            ->where('level', 1)
            ->orderBy('position', 'asc')
            ->paginate(20);

        $goods_subject_list = [];
        foreach($goods_subject_id_list as $key => $one_goods_subject_id_list){
            $goods_subject_list[$key] = CategoryTranslation::where('goods_subject_id', $one_goods_subject_id_list->id)
                ->first();

        }

        //Remove all null values --start
        $goods_subject_list = array_filter( $goods_subject_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $action = Input::get('action');
        $i = 0;
        $neworder = explode("&", $neworder);
        foreach ($neworder as $k=>$v) {
            $id = str_replace("tablelistsorter[]=","", $v);
            if(!empty($id)){
                if($action == 'item')
                    GoodsItemId::where('id', $id)->update(['position' => $i]);
                elseif($action == 'subject')
                    Category::where('id', $id)->update(['position' => $i]);
                elseif($action == 'gallery')
                    GoodsPhoto::where('id', $id)->update(['position' => $i]);

                $i++;
            }
        }
    }

    // ajax response for active
    public function changeActive()
    {
        $active = Input::get('active');
        $id = Input::get('id');
        $action = Input::get('action');

        if($active == 1) {
            $change_active = 0;
        }
        else{
            $change_active = 1;
        }

        if($action == 'item')
            GoodsItemId::where('id', $id)->update(['active' => $change_active]);
        elseif($action == 'subject')
            Category::where('id', $id)->update(['active' => $change_active]);
        elseif($action == 'gallery')
            GoodsPhoto::where('id', $id)->update(['active' => $change_active]);

    }

    public function createGoodsSubject()
    {
        $view = 'admin.goods.create-goods-subject';

        $curr_page_id = Category::where('alias', Request::segment(4))
            ->first();

        if(!is_null($curr_page_id)){
            $curr_page_id = $curr_page_id->id;
        }
        else {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());
    }

    public function editGoodsSubject($id, $edited_lang_id)
    {

        $langs = Lang::get();

        $view = 'admin.goods.edit-goods-subject';

        $goods_without_lang = CategoryTranslation::find($id);

        $goods_elems = CategoryTranslation::where('lang_id', $edited_lang_id)
            ->where('goods_subject_id', $goods_without_lang->goods_subject_id)
            ->first();

        if(!is_null($goods_without_lang)){
            $goods_subject_id = Category::where('id', $goods_without_lang->goods_subject_id)
                ->first();
        }
        elseif(!is_null($goods_elems)){
            $goods_subject_id = Category::where('id', $goods_elems->goods_subject_id)
                ->first();
        }

        // cndj

        $langs = Lang::get();
        $view = 'admin.goods.edit-goods-subject';

        if (!empty($langs)) {
            foreach ($langs as $key => $lang) {
                $item_id = Category::find($id);
                 $oneItem = CategoryTranslation::where('lang_id', $lang->id)
                                    ->where('goods_subject_id', $id)
                                    ->first();
                if (!is_null($oneItem)) {
                    $item[$lang->lang] = $oneItem;
                }else{
                     CategoryTranslation::create([ 'goods_subject_id' => $id, 'lang_id' => $lang->id ]);
                     $item[$lang->lang] = CategoryTranslation::where('lang_id', $lang->id)
                                        ->where('goods_subject_id', $id)
                                        ->first();
                }
            }
        }

        return view($view, get_defined_vars());

    }

    public function saveSubject($id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:goods_subject_id',
            ]);
        }
        else {
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required',
            ]);
        }

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('goods_subject_id');
        $level = GetLevel(Input::get('p_id'), 'goods_subject_id');

        if(is_null($id)){
            $data = [
                'p_id' => Input::get('p_id'),
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
        else {
            $exist_subject = CategoryTranslation::find($id);

            $exist_subject_by_lang = CategoryTranslation::where('goods_subject_id', $exist_subject->goods_subject_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = array_filter([
                'alias' => Input::get('alias'),
                'p_id' => Input::get('p_id'),
                'level' => $level + 1,
            ]);

            $subject_id = Category::where('id', $exist_subject->goods_subject_id)
                ->update($data);

            $data = array_filter([
                'goods_subject_id' => $exist_subject->goods_subject_id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'body' => Input::get('body'),
                'page_title' => Input::get('title'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description')
            ]);

            if(!is_null($exist_subject_by_lang)){
                CategoryTranslation::where('goods_subject_id', $exist_subject->goods_subject_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                CategoryTranslation::create($data);
            }
        }

        if(is_null($id)){
            if($subject_id->level == 1){
                return response()->json([
                    'status' => true,
                    'messages' => ['Save'],
                    'redirect' => urlForFunctionLanguage($this->lang()['lang'], '')
                ]);
            }
            else {
                return response()->json([
                    'status' => true,
                    'messages' => ['Save'],
                    'redirect' => urlForFunctionLanguage($this->lang()['lang'], GetParentAlias($subject_id->id, 'goods_subject_id').'/memberslist')
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'editgoodssubject/'.$id.'/'.$updated_lang_id)
        ]);
    }

    public function goodsSubjectCart()
    {
        $view = 'admin.goods.subject-cart';

        $lang_id = $this->lang()['lang_id'];

        $deleted_elems_by_alias = Category::where('alias', Request::segment(4))
            ->first();

        if(is_null($deleted_elems_by_alias)){
            $deleted_subject_id_elems = Category::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', 0)
                ->get();
        }
        else{
            $deleted_subject_id_elems = Category::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', $deleted_elems_by_alias->id)
                ->get();
        }

        $deleted_subject_elems = [];
        foreach($deleted_subject_id_elems as $key => $one_deleted_subject_elem){
            $deleted_subject_elems[$key] = CategoryTranslation::where('goods_subject_id', $one_deleted_subject_elem->id)
                ->first();
        }

        $deleted_subject_elems = array_filter( $deleted_subject_elems, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function destroyGoodsSubject($id)
    {
        $goods_subject_elems = CategoryTranslation::findOrFail($id);
        $goods_subject_elems_id = Category::findOrFail($goods_subject_elems->goods_subject_id);
        if (!is_null($goods_subject_elems_id)) {
            if(empty(IfHasChildUniv($goods_subject_elems->goods_subject_id, 'goods_subject')) && empty(CheckIfSubjectHasItems('goods', $goods_subject_elems->goods_subject_id))){
                if ($goods_subject_elems_id->deleted == 1 && $goods_subject_elems_id->active == 0) {

                    Session::flash('message', $goods_subject_elems->name . '<br />was successful deleted! ');

                    Category::destroy($goods_subject_elems->goods_subject_id);
                    CategoryTranslation::destroy($id);

                } elseif ($goods_subject_elems_id->deleted == 0) {
                    Session::flash('message', $goods_subject_elems->name . '<br />was successful added to cart! ');

                    Category::where('id', $goods_subject_elems->goods_subject_id)
                        ->update(['active' => 0, 'deleted' => 1]);
                }
            } else {
                Session::flash('error-message', $goods_subject_elems->name . '<br />have children! ');
            }
        }

        return redirect()->back();
    }

    public function restoreGoodsSubject($id)
    {
        $goods_subject = CategoryTranslation::findOrFail($id);
        Session::flash('message', $goods_subject->name . '<br />was successful restored! ');

        Category::where('id', $goods_subject->goods_subject_id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }

    public function all()
    {
        $view = 'admin.goods.child-list';

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;

        $goods_list_id = Category::where('alias', Request::segment(4))
            ->first();

        if(is_null($goods_list_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        if(empty(CheckIfSubjectHasItems('goods', $goods_list_id->id))){
            $child_goods_list_id = Category::where('p_id', $goods_list_id->id)
                ->where('deleted', 0)
                ->orderBy('position', 'asc')
                ->get();
            $child_goods_list = [];
            foreach($child_goods_list_id as $key => $one_goods_elem){
                $child_goods_list[$key] = CategoryTranslation::where('goods_subject_id', $one_goods_elem->id)
                    ->first();
            }

            $child_goods_list = array_filter( $child_goods_list, 'strlen' );
            $child_goods_item_list = [];
        }
        else {
            $child_goods_item_list_id = GoodsItemId::where('goods_subject_id', $goods_list_id->id)
                ->where('deleted', 0)
                ->orderBy('position', 'asc')
                ->get();
            $child_goods_item_list = [];
            foreach($child_goods_item_list_id as $key => $one_goods_elem){
                $child_goods_item_list[$key] = GoodsItem::where('goods_item_id', $one_goods_elem->id)
                    ->first();
            }

            $child_goods_item_list = array_filter( $child_goods_item_list, 'strlen' );
            $child_goods_list = [];
        }

        return view($view, get_defined_vars());
    }

    public function index()
    {
        $view = 'admin.goods.child-list';

        $goods_list_id = Category::first();

        if(is_null($goods_list_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;


        $child_goods_item_list_id = GoodsItemId::orderBy('position', 'asc')
                                            ->get();

        $child_goods_item_list = [];
        foreach($child_goods_item_list_id as $key => $one_goods_elem){
            $child_goods_item_list[$key] = GoodsItem::where('goods_item_id', $one_goods_elem->id)
                ->first();
        }

        $child_goods_item_list = array_filter( $child_goods_item_list, 'strlen' );
        $child_goods_list = [];


        return view($view, get_defined_vars());
    }

    public function createGoodsItem()
    {
        $view = 'admin.goods.create-goods-item';

        $curr_page_id = Category::where('alias', Request::segment(4))
            ->first();

        if(!is_null($curr_page_id)){
            $curr_page_id = $curr_page_id->id;
        }
        else {
            $curr_page_id = null;
        }

        // get parameters
        $paramsId = ParameterId::where('type', '!=', 'discount')->get();
        $params = [];
        if (!is_null($paramsId)) {
            foreach ($paramsId as $key => $paramId) {
                 $params[$key] = Parameter::where('lang_id', $this->lang()['lang_id'])
                                        ->where('parameter_id', $paramId->id)
                                        ->first();
             }
        }

        // get discount
        $discountId = ParameterId::where('type', 'discount')->first();
        $discount = null;
        if (!is_null($discountId)) {
            $discount = Parameter::where('lang_id', $this->lang()['lang_id'])
                                ->where('parameter_id', $discountId->id)
                                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function editGoodsItem($id, $edited_lang_id)
    {
        $view = 'admin.goods.edit-goods-item';

        $goods_without_lang = GoodsItem::find($id);

        $goods_elems = GoodsItem::where('lang_id', $edited_lang_id)
            ->where('goods_item_id', $goods_without_lang->goods_item_id)
            ->first();

        if(!is_null($goods_without_lang)){
            $goods_item_id = GoodsItemId::where('id', $goods_without_lang->goods_item_id)
                ->first();
        }
        elseif(!is_null($goods_elems)){
            $goods_item_id = GoodsItemId::where('id', $goods_elems->goods_item_id)
                ->first();
        }

        // get parameters
        $paramsId = ParameterId::where('type', '!=', 'discount')->get();
        $params = [];
        if (!is_null($paramsId)) {
            foreach ($paramsId as $key => $paramId) {
                 $params[$key] = Parameter::where('lang_id', $this->lang()['lang_id'])
                                        ->where('parameter_id', $paramId->id)
                                        ->first();
             }
        }

        // get discount
        $discountId = ParameterId::where('type', 'discount')->first();
        $discount = null;
        if (!is_null($discountId)) {
            $discount = Parameter::where('lang_id', $this->lang()['lang_id'])
                                ->where('parameter_id', $discountId->id)
                                ->first();
        }

        $goods_subject_id = Category::where('id', $goods_item_id->goods_subject_id)->first();

        return view($view, get_defined_vars());
    }

    public function saveItem($id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:goods_subject_id',
                'price' => 'numeric|min:0|required'
            ]);
        }
        else {
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required',
                'price' => 'numeric|min:0|required'
            ]);
        }

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('goods_item_id');

        if(is_null($id)){
            $data = [
                'goods_subject_id' => Input::get('p_id'),
                'alias' => Input::get('alias'),
                'position' => $maxPosition + 1,
                'active' => 1,
                'deleted' => 0,
                'price' => Input::get('price'),
                'old_price' => Input::get('price_old'),
                'discount' => Input::get('discount')
            ];

            $item_id = GoodsItemId::create($data);

            // foreach (Input::get('value') as $paramName => $value) {
            //             ParameterGoods::create([
            //                     'goods_id' => $item_id->id,
            //                     'lang_id'  => Input::get('lang'),
            //                     'param_id' => $paramName,
            //                     'param_value' => $value,
            //                 ]);
            // }

            $data = array_filter([
                'goods_item_id' => $item_id->id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'short_descr' => Input::get('short_descr'),
                'body' => Input::get('body'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description')
            ]);

            GoodsItem::create($data);
        }
        else {
            $exist_item = GoodsItem::find($id);

            $exist_item_by_lang = GoodsItem::where('goods_item_id', $exist_item->goods_item_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = array_filter([
                'goods_subject_id' => Input::get('p_id'),
                'alias' => Input::get('alias'),
                'price' => Input::get('price'),
                'old_price' => Input::get('old_price'),
                'discount' => Input::get('discount')
            ]);

            $item_id = GoodsItemId::where('id', $exist_item->goods_item_id)
                ->update($data);

            $data = array_filter([
                'goods_item_id' => $exist_item->goods_item_id,
                'lang_id' => Input::get('lang'),
                'name' => Input::get('name'),
                'short_descr' => Input::get('short_descr'),
                'body' => Input::get('body'),
                'h1_title' => Input::get('h1_title'),
                'meta_title' => Input::get('meta_title'),
                'meta_keywords' => Input::get('meta_keywords'),
                'meta_description' => Input::get('meta_description')
            ]);

            if(!is_null($exist_item_by_lang)){
                GoodsItem::where('goods_item_id', $exist_item->goods_item_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                GoodsItem::create($data);
            }

             foreach (Input::get('value') as $paramName => $value) {
                        ParameterGoods::where('id', $paramName)
                            ->update([
                                'param_value' => $value,
                            ]);
            }
        }

        if(is_null($id)){
            return response()->json([
                'status' => true,
                'messages' => ['Save'],
                'redirect' => url($this->lang()['lang'].'/back/goods')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'editgoodsitem/'.$id.'/'.$updated_lang_id)
        ]);
    }

    public function goodsItemCart()
    {
        $view = 'admin.goods.item-cart';

        $lang_id = $this->lang()['lang_id'];

        $deleted_elems_by_alias = Category::where('alias', Request::segment(4))
            ->first();

            $deleted_item_id_elems = GoodsItemId::where('deleted', 1)
                ->where('active', 0)
                ->where('goods_subject_id', $deleted_elems_by_alias->id)
                ->get();

        $deleted_item_elems = [];
        foreach($deleted_item_id_elems as $key => $one_deleted_item_elem){
            $deleted_item_elems[$key] = GoodsItem::where('goods_item_id', $one_deleted_item_elem->id)
                ->first();
        }

        $deleted_item_elems = array_filter( $deleted_item_elems, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function destroyGoodsItem($id)
    {
        $goods_item_elems = GoodsItem::findOrFail($id);
        $goods_item_elems_id = GoodsItemId::findOrFail($goods_item_elems->goods_item_id);
        if (!is_null($goods_item_elems_id)) {
            if ($goods_item_elems_id->deleted == 1 && $goods_item_elems_id->active == 0) {
                $goods_photo = GoodsPhoto::where('goods_item_id', $goods_item_elems_id->id)->get();
                if(!is_null($goods_photo)){
                    foreach($goods_photo as $one_goods_photo){
                        if (File::exists('upfiles/gallery/s/' . $one_goods_photo->img))
                            File::delete('upfiles/gallery/s/' . $one_goods_photo->img);

                        if (File::exists('upfiles/gallery/m/' . $one_goods_photo->img))
                            File::delete('upfiles/gallery/m/' . $one_goods_photo->img);

                        if (File::exists('upfiles/gallery/' . $one_goods_photo->img))
                            File::delete('upfiles/gallery/' . $one_goods_photo->img);
                    }
                }

                Session::flash('message', $goods_item_elems->name . '<br />was successful deleted! ');

                GoodsItemId::destroy($goods_item_elems->goods_item_id);
                GoodsItem::destroy($id);

            } elseif ($goods_item_elems_id->deleted == 0) {
                Session::flash('message', $goods_item_elems->name . '<br />was successful added to cart! ');

                GoodsItemId::where('id', $goods_item_elems->goods_item_id)
                    ->update(['active' => 0, 'deleted' => 1]);
            }
        }

        return redirect()->back();
    }

    public function restoreGoodsItem($id)
    {
        $goods_item = GoodsItem::findOrFail($id);
        Session::flash('message', $goods_item->name . '<br />was successful restored! ');

        GoodsItemId::where('id', $goods_item->goods_item_id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }

    public function itemsPhoto($id)
    {
        $view = 'admin.goods.items-photo';

        $lang = $this->lang()['lang'];
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->src;

        $goods_item = GoodsItem::findOrFail($id);

        if(!is_null($goods_item)){
            $goods_item_id = GoodsItemId::where('id', $goods_item->id)->first();
        }

        $goods_photo = GoodsPhoto::where('goods_item_id', $id)
            ->orderBy('position', 'asc')
            ->get();

        return view($view, get_defined_vars());
    }

    public function destroyGoodsPhoto($id)
    {
        $goods_photo = GoodsPhoto::findOrFail($id);
        if(!is_null($goods_photo)){
            if (File::exists('upfiles/gallery/s/' . $goods_photo->img))
                File::delete('upfiles/gallery/s/' . $goods_photo->img);

            if (File::exists('upfiles/gallery/m/' . $goods_photo->img))
                File::delete('upfiles/gallery/m/' . $goods_photo->img);

            if (File::exists('upfiles/gallery/' . $goods_photo->img))
                File::delete('upfiles/gallery/' . $goods_photo->img);

            Session::flash('message', 'The photo was successful deleted!');
            GoodsPhoto::destroy($id);
        }

        return redirect()->back();
    }
}
