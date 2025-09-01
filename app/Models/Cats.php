<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
class Cats extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function blog(): MorphToMany
    {
        return $this->belongsToMany(Blog::class, 'cats_blogs');
    }
}
