<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerTopId extends Model
{

    protected $table = 'banner_top_id';

    protected $fillable = [
        'position', 'active', 'deleted'
    ];

    public function bannerTop()
    {
        return $this->hasOne('App\Models\BannerTop', 'banner_top_id', 'id');
    }
}
