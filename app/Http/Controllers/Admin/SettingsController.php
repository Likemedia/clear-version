<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\SettingsId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class SettingsController extends Controller
{
    public function index()
    {
        $view = 'admin.settings.settings-list';

        $lang_id = $this->lang()['lang_id'];

        $settings_list_id = SettingsId::orderBy('id', 'asc')
            ->paginate(20);

        $settings_list = [];
        foreach($settings_list_id as $key => $one_settings_list_id){
            $settings_list[$key] = Settings::where('settings_id', $one_settings_list_id->id)
                ->first();
        }

        //Remove all null values --start
        $settings_list = array_filter( $settings_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function createItem()
    {
        $view = 'admin.settings.create-setting';


        return view($view, get_defined_vars());
    }


    public function editItem($id, $edited_lang_id)
    {
        $view = 'admin.settings.edit-setting';

        $settings_without_lang = Settings::where('id', $id)
            ->first();

        $settings = Settings::where('settings_id', $settings_without_lang->settings_id)
            ->where('lang_id', $edited_lang_id)
            ->first();

        if(!is_null($settings_without_lang)){
            $settings_id = SettingsId::where('id', $settings_without_lang->settings_id)
                ->first();
        }
        elseif(!is_null($settings)){
            $settings_id = SettingsId::where('id', $settings->settings_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function save($id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required|unique:settings_id'
            ]);
        }
        else {
            $item = Validator::make(Input::all(), [
                'name' => 'required',
                'alias' => 'required'
            ]);
        }


        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $lang_id = $this->lang()['lang_id'];

        $data = array_filter([
            'alias' => Input::get('alias'),
            'set_type' => Input::get('set_type')
        ]);

        if(is_null($id)){
            $settings_id = SettingsId::create($data);

            if(!empty(Input::get('body'))){
                $data = array_filter([
                    'name' => Input::get('name'),
                    'body' => Input::get('body'),
                    'lang_id' => $lang_id,
                    'settings_id' => $settings_id->id
                ]);
            }
            elseif(!empty(Input::get('textarea'))){
                $data = array_filter([
                    'name' => Input::get('name'),
                    'body' => Input::get('textarea'),
                    'lang_id' => $lang_id,
                    'settings_id' => $settings_id->id
                ]);
            }
            elseif(!empty(Input::get('input'))){
                $data = array_filter([
                    'name' => Input::get('name'),
                    'body' => Input::get('input'),
                    'lang_id' => $lang_id,
                    'settings_id' => $settings_id->id
                ]);
            }
            else {
                $data = array_filter([
                    'name' => Input::get('name'),
                    'lang_id' => $lang_id,
                    'settings_id' => $settings_id->id
                ]);
            }

            Settings::create($data);
        }
        else {
            $settings = Settings::findOrFail($id);

            SettingsId::where('id', $settings->settings_id)
                ->update($data);

            $exist_settings_by_lang = Settings::where('settings_id', $settings->settings_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

            if(!empty(Input::get('body'))){
                $data = array_filter([
                    'name' => Input::get('name'),
                    'body' => Input::get('body'),
                    'lang_id' => $updated_lang_id,
                    'settings_id' => $settings->settings_id
                ]);
            }
            elseif(!empty(Input::get('textarea'))){
                $data = array_filter([
                    'name' => Input::get('name'),
                    'body' => Input::get('textarea'),
                    'lang_id' => $updated_lang_id,
                    'settings_id' => $settings->settings_id
                ]);
            }
            elseif(!empty(Input::get('input'))){
                $data = array_filter([
                    'name' => Input::get('name'),
                    'body' => Input::get('input'),
                    'lang_id' => $updated_lang_id,
                    'settings_id' => $settings->settings_id
                ]);
            }
            else {
                $data = array_filter([
                    'name' => Input::get('name'),
                    'lang_id' => $updated_lang_id,
                    'settings_id' => $settings->settings_id
                ]);
            }

            if(!is_null($exist_settings_by_lang)){
                Settings::where('settings_id', $settings->settings_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else{
                Settings::create($data);
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

    public function destroySetting($id)
    {
        $settings = Settings::findOrFail($id);
        Session::flash('message', $settings->name . '<br />was successful deleted! ');
        SettingsId::destroy($settings->settings_id);
        Settings::destroy($id);

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