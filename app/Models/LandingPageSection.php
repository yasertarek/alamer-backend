<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageSection extends Model
{
    protected $fillable = ['key', 'data', 'order'];

    protected $casts = [
        'data' => 'array',
    ];
}
