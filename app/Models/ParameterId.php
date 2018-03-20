<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterId extends Model
{
    protected $table = 'parameter_id';

    protected $fillable = [
        'type'
    ];

    public function parameter()
    {
        return $this->hasMany('App\Models\Parameter', 'parameter_id', 'id');
    }
}
