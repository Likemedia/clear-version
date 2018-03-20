<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $table = 'parameter';

    protected $fillable = [
        'parameter_id', 'lang_id', 'name', 'value'
    ];

    public function parameterId()
    {
        return $this->hasOne('App\Models\ParameterId', 'id', 'parameter_id');
    }
}
