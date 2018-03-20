<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\FrontMenuId;
use App\Models\FrontMenu;
use View;

class DefaultController extends Controller
{
   public function __construct()
   {
       // $this->middleware('auth', ['except' => '/']);
    //    View::share('menus', $this->getMenu());
   }

   // public function getMenu()
   // {
   //      $frontMenus = FrontMenuId::where('p_id', 0)->orderBy('position', 'asc')->get();
   //      if (!empty($frontMenus)) {
   //          $menus = [];
   //          foreach ($frontMenus as $key => $frontMenu) {
   //              $menus[] = FrontMenu::where('lang_id', $this->lang()['lang_id'])
   //                                  ->where('front_menu_id', $frontMenu->id)
   //                                  ->first();
   //          }
   //      }
   //
   //      return $menus;
   // }
}
