<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GoodsItem;
use App\Models\GoodsItemId;
use App\Models\CategoryTranslation;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Menu;
use App\Models\Menu_id;
use App\Models\Promotion;
use App\Models\PromotionId;
use App\Models\Service;
use App\Models\ServiceId;
use App\Models\Portfolio;
use App\Models\PortfolioId;
use App\Models\Gallery;
use App\Models\GalleryId;
use App\Models\Client;
use App\Models\ClientId;
use App\Models\BannerTop;
use App\Models\BannerTopId;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Front\DefaultController;
use View;

class PagesController extends DefaultController
{
    public function __construct ()
    {
        $categsId = Category::orderBy('position', 'asc')->get();
        $categs = [];

        if (!empty($categsId)) {
            foreach ($categsId as $key => $categId) {
                $categs[] = CategoryTranslation::where('goods_subject_id', $categId->id)->where('lang_id', $this->lang()['lang_id'])->first();
            }
        }

        View::share('categs', array_filter($categs));
    }

    public function index()
    {

    }

}
