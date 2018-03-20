<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\RoleManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class DefaultController extends RoleManager
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => '/']);
    }

    public function index()
    {
        return view('admin.welcome');
    }

}
