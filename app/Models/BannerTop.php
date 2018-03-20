<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerTop extends Model
{

    protected $table = 'banner_top';

    protected $fillable = [
        'banner_top_id', 'lang_id', 'img', 'name', 'title_h1', 'title_h2', 'alt', 'title', 'link'
    ];

    public function bannerTopId()
    {
        return $this->hasOne('App\Models\BannerTopId', 'id', 'banner_top_id');
    }

    public function lang()
    {
        return $this->hasOne('App\Models\Lang', 'id', 'lang_id');
    }
}
