<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Labels;
use App\Models\LabelsId;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LabelsController extends Controller
{

    public function index()
    {
        $view = 'admin.labels.labels-list';

        $lang_id = $this->lang()['lang_id'];

        $labels_list_id = LabelsId::orderBy('id', 'asc')
            ->paginate(20);

        $labels_list = [];
        foreach($labels_list_id as $key => $one_label_id){
            $labels_list[$key] = Labels::where('labels_id' ,$one_label_id->id)
                ->first();
        }
        //Remove all null values --start
        $labels_list = array_filter( $labels_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function createItem()
    {
        $view = 'admin.labels.create-label';

        return view($view, get_defined_vars());
    }

    public function editItem($id, $edited_lang_id)
    {
        $view = 'admin.labels.edit-label';

        $labels_without_lang = Labels::where('id', $id)
            ->first();

        $labels = Labels::where('labels_id', $labels_without_lang->labels_id)
            ->where('lang_id', $edited_lang_id)
            ->first();

        return view($view, get_defined_vars());
    }

    public function save($id, $updated_lang_id)
    {
        $item = Validator::make(Input::all(), [
            'name' => 'required',
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }
        $lang_id = $this->lang()['lang_id'];
        $labels_id = LabelsId::max('id');

        $data_labels_id = array_filter([
            'id' => $labels_id+1
        ]);

        if(is_null($id)){
            $next_label_id = LabelsId::create($data_labels_id);
            $data = array_filter([
                'name' => Input::get('name'),
                'lang_id' => $lang_id,
                'labels_id' => $next_label_id->id
            ]);
            Labels::create($data);
        }
        else{
            $curr_labels_id = Labels::findOrFail($id);

            $exist_labels_by_lang = Labels::where('labels_id', $curr_labels_id->labels_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            $data = array_filter([
                'name' => Input::get('name'),
                'lang_id' => $updated_lang_id,
                'labels_id' => $curr_labels_id->labels_id
            ]);

            if(!is_null($exist_labels_by_lang)){
                Labels::where('labels_id', $curr_labels_id->labels_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else{
                Labels::create($data);
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

    public function destroyLabel($id)
    {
        $labels = Labels::findOrFail($id);
        Session::flash('message', $labels->name . '<br />was successful deleted! ');
        LabelsId::destroy($labels->labels_id);
        Labels::destroy($id);

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