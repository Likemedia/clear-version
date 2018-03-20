<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientId extends Model
{
    protected $table = 'clients_id';

    protected $fillable = [
        'position', 'active', 'img', 'alias'
    ];
}
