<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';

    protected $fillable = [
        'gallery_id', 'lang_id', 'title', 'img', 'img_title', 'img_alt'
    ];

    public function galleryId()
    {
        return $this->hasOne('App\Models\GalleryId', 'id', 'gallery_id');
    }

    public function itemId()
    {
        return $this->hasOne('App\Models\GalleryId', 'id', 'gallery_id');
    }
}
