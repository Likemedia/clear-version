<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolio';

    protected $fillable = [
        'portfolio_id', 'lang_id', 'title', 'video',
    ];

    public function portfolioId()
    {
        return $this->hasOne('App\Models\PortfolioId', 'id', 'portfolio_id');
    }

    public function itemId()
    {
        return $this->hasOne('App\Models\PortfolioId', 'id', 'portfolio_id');
    }
}
