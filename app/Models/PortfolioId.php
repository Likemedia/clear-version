<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioId extends Model
{
    protected $table = 'portfolio_id';

    protected $fillable = [
        'alias', 'category', 'position', 'active'
    ];
}
