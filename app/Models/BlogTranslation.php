<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'title', 'subtitle', 'content'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
