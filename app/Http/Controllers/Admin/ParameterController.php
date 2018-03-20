<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserActionPermision;
use App\Models\Module;
use App\FrontUser;
use Illuminate\Http\Request;
use App\Models\AdminUserGroup;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Hash;

use App\Models\Parameter;
use App\Models\ParameterId;
use App\Models\Lang;

class ParameterController  extends Controller
{
    public function index()
    {
    	$paramsId = ParameterId::where('type', '!=', 'discount')
    							->get();

    	$params = [];
    	if (!empty($paramsId)) {
    		foreach ($paramsId as $key => $paramId) {
    			$params[$key] = Parameter::where('parameter_id', $paramId->id)
    									->where('lang_id', $this->lang()['lang_id'])
    									->first();
    		}
    	}

    	$data['lang'] = $this->lang()['lang'];
    	$data['lang_id'] = $this->lang()['lang_id'];
    	$data['paramsId'] = $paramsId;
    	$data['params'] = array_filter($params);

    	return view('admin.parameters.index', $data);
    }

    public function discount()
    {
    	$checkParam = ParameterId::where('type', 'discount')
    							->first();

    	if (is_null($checkParam)) {
    		return abort('404');
    	}

    	$param = Parameter::where('lang_id', $this->lang()['lang_id'])
    						->where('parameter_id', $checkParam->id)
    						->first();

    	if (is_null($param)) {
    		return abort('404');
    	}

    	$vals = json_decode($param->value);

    	$data['param']  = $param;
    	$data['values'] = $vals;

    	return view('admin.parameters.discount', $data);
    }

    public function create()
    {	
    	return view('admin.parameters.create');
    }

    public function saveParameter()
    {
    	$item = Validator::make(Input::all(), [
                'name' => 'required|unique:parameter',
                'type' => 'required'
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $paramId = ParameterId::create([
        			'type' => Input::get('type')
        		]);

        $langs = Lang::get();
        $val = [];


        if (!empty($langs)) {
        	foreach ($langs as $key => $lang) {
				Parameter::create([
						'parameter_id' => $paramId->id,
						'lang_id' => $lang->id,
						'name' => Input::get('name'),
						'value' => json_encode($val)
					]);        		
        	}
        }

        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => '/'.$this->lang()['lang'].'/back/goods/options'
        ]);
    }

    public function edit($paramId, $langId)
    {
    	$checkParam = ParameterId::where('id', $paramId)
    							->first();

    	if (is_null($checkParam)) {
    		return abort('404');
    	}

    	$param = Parameter::where('lang_id', $langId)
    						->where('parameter_id', $checkParam->id)
    						->first();

    	if (is_null($param)) {
    		return abort('404');
    	}

    	$vals = json_decode($param->value);

    	$data['param']  = $param;
    	$data['values'] = $vals;

    	return view('admin.parameters.edit', $data);
    }

    public function updateParameter($id, $lang)
    {
        // dd(collect(Input::get('value'))->toJson());
    	$item = Validator::make(Input::all(), [
                'name' => 'required',
                'type' => 'required'
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        ParameterId::where('id', Input::get('itemId'))
        				->update([
        					'type' => Input::get('type')
        				]);

        Parameter::where('parameter_id', Input::get('itemId'))
        			->where('lang_id', Input::get('lang'))
        			->update([
        					'name' => Input::get('name'),
        					'value' => json_encode(array_filter(Input::get('value')))
        				]);


         return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => '/'.$this->lang()['lang'].'/back/goods/options/edit/'.Input::get('itemId').'/'.Input::get('lang')
        ]);
    }

    public function updateDiscount()
    {
    	$langs = Lang::get();

    	foreach ($langs as $key => $lang) {
    		Parameter::where('parameter_id', Input::get('itemId'))
        			->where('lang_id', $lang->id)
        			->update([
        					'value' => json_encode(array_filter(Input::get('value')))
        				]);
    	}
        
        return response()->json([
            'status' => true,
            'messages' => ['Updated'],
            'redirect' => '/'.$this->lang()['lang'].'/back/goods/options/discount/'
        ]);
    }

    public function delete($id)
    {
    	$checkParam = ParameterId::where('id', $id)
    							->first();

    	if (is_null($checkParam)) {
    		return abort('404');
    	}

    	ParameterId::where('id', $id)
    					->delete();

    	Parameter::where('parameter_id', $id)
    					->delete();

    	return redirect($this->lang()['lang']. '/back/goods/options/');
    }
}














