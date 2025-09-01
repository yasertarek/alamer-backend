<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarTranslation extends Model
{
    protected $fillable = ['title', 'navbar_id', 'language_id'];
    use HasFactory;

    public function navbar()
    {
        return $this->belongsTo(Navbar::class);
    }
        public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
