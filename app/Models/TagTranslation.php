<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    protected $fillable = ['tag_id', 'lang_id', 'name'];

    protected $table = 'tags_translation';

    public function tag() {

        return $this->belongsTo(Tag::class);
    }
}
