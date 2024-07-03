<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'picture', 'is_featured'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user');
    }

    public function reactions(){
        return $this->hasMany(Reaction::class)->with('user');
    }

    public function getReactionsCountAttribute()
    {
        return $this->reactions()->count();
    }

    public function getLikesCountAttribute()
    {
        return $this->reactions()->where('type', 'like')->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

}
