<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedForm;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class FeedFormController extends Controller
{
    public function index()
    {
        $view = 'admin.feedform.feedform-list';

        $feedform = FeedForm::orderBy('created_at', 'desc')
            ->paginate(20);

        return view($view, get_defined_vars());
    }

    public function addFeedForm($id)
    {
        $view = 'admin.feedform.answer-feedform';

        $feedform = FeedForm::find($id);

        return view($view, get_defined_vars());
    }

    public function save($id)
    {
        if(!is_null($id)){
            $data = array_filter([
               'answer' => Input::get('body')
            ]);

            FeedForm::where('id', $id)
                ->update($data);

            return response()->json([
                'status' => true,
                'messages' => ['Sent'],
                'redirect' => urlForLanguage($this->lang()['lang'], 'addfeedform/'.$id)
            ]);
        }
        else {
            return redirect()->back();
        }
    }

    public function destroyFeedForm($id)
    {
        if(!is_null($id)){
            $feedform = FeedForm::find($id);
            Session::flash('message', $feedform->name . '<br />was successful deleted! ');

            FeedForm::destroy($id);
            return redirect()->back();
        }
        else {
            return redirect()->back();
        }
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