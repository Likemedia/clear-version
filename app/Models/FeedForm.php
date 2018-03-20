<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FeedForm extends Model
{
    protected $table = 'feedform';

    protected $fillable = [
        'name', 'email', 'phone', 'city', 'ip', 'question', 'answer', 'recipient'
    ];

}