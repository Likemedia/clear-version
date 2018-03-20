<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterGoods extends Model
{
    protected $table = 'parameter_goods';

    protected $fillable = [
        'goods_id', 'lang_id', 'param_id', 'param_value'
    ];

    public function goodId()
    {
        return $this->hasOne('App\Models\GoodsItemId', 'parameter_id', 'id');
    }
}
