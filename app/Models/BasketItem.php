<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasketItem extends Model
{

    protected $table = 'basket_items';

    protected $fillable = [
        'basket_id', 'goods_item_id', 'qty', 'price'
    ];

    public function basket()
    {
        return $this->hasOne('App\Models\Basket', 'basket_id', 'id');
    }

	public function basketGoodId()
    {
        return $this->hasOne('App\Models\GoodsItemId', 'id', 'good_item_id');
    }    
}
