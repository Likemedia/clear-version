<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsItemId extends Model
{
    protected $table = 'goods_item_id';

    protected $fillable = [
        'goods_subject_id', 'alias', 'active', 'deleted', 'price', 'old_price', 'discount', 'position', 'add_date'
    ];

}
