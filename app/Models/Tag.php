<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function translations() {
        return $this->hasMany(TagTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(TagTranslation::class)->where('lang_id', $lang);
    }

    public function articles()
    {
        return $this->belongsToMany(Post::class);
    }
}
