<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'promotion_id', 'lang_id', 'title', 'img', 'body', 'meta_title', 'meta_descr', 'meta_keywords',
    ];

    public function promotionsId()
    {
        return $this->hasOne('App\Models\PromotionId', 'id', 'promotion_id');
    }

    public function itemId()
    {
        return $this->hasOne('App\Models\PromotionId', 'id', 'promotion_id');
    }
}
