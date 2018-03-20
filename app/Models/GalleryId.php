<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryId extends Model
{
    protected $table = 'gallery_id';

    protected $fillable = [
        'position', 'active', 'alias'
    ];
}
