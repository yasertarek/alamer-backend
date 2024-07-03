<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use voku\helper\ASCII;


class BlogTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['blog_id', 'language_id', 'title', 'subtitle', 'content', 'slug'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
