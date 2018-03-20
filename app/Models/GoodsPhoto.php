<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsPhoto extends Model
{
    protected $table = 'goods_foto';

    protected $fillable = [
        'goods_item_id', 'img', 'position', 'active', 'add_date'
    ];

    public function goodsItemId(){
        return $this->hasOne('App\Models\GoodsItemId', 'id', 'goods_item_id');
    }

    public function goodsItem(){
        return $this->belongsToMany('App\Models\GoodsItem', 'id', 'goods_item_id');
    }
}
