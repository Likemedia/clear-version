<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserActionPermision;
use App\Models\Module;
use App\FrontUser;
use Illuminate\Http\Request;
use App\Models\AdminUserGroup;
use App\Models\User;
use App\Models\Lang;
use App\Models\Menu;
use App\Models\Menu_id;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Hash;


class PlatformController  extends Controller
{
    public function index()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://'.getenv('PLATFORM_SUBDOMAIN').'.likemedia.md/api/1f62aea509b84b02cfef4c1ae83329a0fd889a6fad1ceb75c8791b0d99e0fe85'
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $array = json_decode($resp);

        if (!empty($array)) {
            if (!empty($array->langs)) {
                foreach ($array->langs as $key => $lang) {
                    $checkLang = Lang::where('lang', $lang->name)->first();
                    if (is_null($checkLang)) {
                        echo $key;
                        if ($key === 0) {
                            Lang::create([
                                'lang' => $lang->name,
                                'default_lang' => 1,
                                'descr' => $this->getLangDescr($lang->name),
                                'active' => 1,
                                'position' => $key + 1,
                            ]);
                        }else{
                            Lang::create([
                                'lang' => $lang->name,
                                'default_lang' => 0,
                                'descr' => $this->getLangDescr($lang->name),
                                'active' => 1,
                                'position' => $key + 1,
                            ]);
                        }
                    }
                }
            }
        }

        $langs = Lang::get();

        if (!empty($array->pages)) {
            foreach ($array->pages as $key => $page) {
                $checkMenu = Menu_id::where('alias', $page->slug)->first();
                if (is_null($checkMenu)) {
                    $menuId = Menu_id::create([
                        'alias' => $page->slug,
                        'position' => $key + 1,
                        'level' => 1,
                        'active' => 1,
                        'deleted' => 0
                    ]);
                    foreach ($langs as $key => $lang) {
                        Menu::create([
                            'menu_id' => $menuId->id,
                            'lang_id' => $lang->id,
                            'name' => $page->slug,
                            'slug' => $page->alias->{Lang::find($lang->id)->lang},
                            'meta_title' => $page->title->{Lang::find($lang->id)->lang},
                            'meta_keywords' => $page->keywords->{Lang::find($lang->id)->lang},
                            'meta_description' => $page->description->{Lang::find($lang->id)->lang},
                        ]);
                    }
                }
            }
        }
    }

    public function update(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://'.getenv('PLATFORM_SUBDOMAIN').'.likemedia.md/get/content/api'
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($resp);

        $menuId = Menu_id::where('alias', $request->slug)->first();
        if (!is_null($menuId)) {
            $page = $data->{$request->slug};
            $langs = Lang::get();
            foreach ($langs as $key => $lang) {
                $row = $page->content->{Lang::find($lang->id)->lang};
                Menu::where('menu_id', $menuId->id)
                    ->where('lang_id', $lang->id)
                        ->update([
                            'body' => $row->text,
                            'meta_title' => $row->title,
                            'meta_keywords' => $row->seoKeywords,
                            'meta_description' => $row->seoDesrc
                        ]);
            }
        }

    }

    private function getLangDescr($lang)
    {
        $langsDescr = [
            'ru' => 'По русски',
            'ro' => 'In romana',
            'en' => 'In english',
            'es' => 'Español',
        ];
        foreach ($langsDescr as $key => $item) {
            if ($key == $lang) {
                return $item;
            }
        }
        return "unknown";
    }
}
