<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{

    protected $table = 'basket';

    protected $fillable = [
        'user_id', 'user_ip', 'amount'
    ];

    public function basketItems()
    {
        return $this->hasMany('App\Models\BasketItem', 'basket_id', 'id');
    }
}
