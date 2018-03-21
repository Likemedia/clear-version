<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Lang;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\User;
use App\Models\AdminUserActionPermision;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests,  DispatchesJobs, ValidatesRequests;

    protected $langs;

    protected $lang;

    public function __construct()
    {
        $this->langs = Lang::all();

        $this->lang = Lang::where('lang', session('applocale') ?? 'ro')->first()->id;

        if (!Auth::check()) {
            return redirect('/auth/login');
        }
    }

    public function getLangById($id)
    {
        $lang_name = Lang::findOrFail($id)->lang;

        return $lang_name;
    }

}
