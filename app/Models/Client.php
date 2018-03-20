<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'client_id', 'lang_id', 'title', 'link', 'img_title', 'img_alt'
    ];

    public function clientId()
    {
        return $this->hasOne('App\Models\ClientId', 'id', 'client_id');
    }

    public function itemId()
    {
        return $this->hasOne('App\Models\ClientId', 'id', 'client_id');
    }
}
