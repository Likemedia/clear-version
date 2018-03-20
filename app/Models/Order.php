<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'basket_id', 'user_id', 'amount'
    ];

    public function orderUser()
    {
        return $this->hasOne('App\FrontUser', 'id', 'user_id');
    }

    public function orderBasket()
    {
        return $this->hasOne('App\Models\Basket', 'id', 'basket_id');
    }
}
