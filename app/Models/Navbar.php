<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    protected $fillable = ['link', 'group', 'order'];

    public function translations()
    {
        return $this->hasMany(NavbarTranslation::class);
    }

    use HasFactory;
}
