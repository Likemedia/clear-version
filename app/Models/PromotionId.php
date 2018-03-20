<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionId extends Model
{
    protected $table = 'promotions_id';

    protected $fillable = [
        'alias', 'position', 'active'
    ];
}
