<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['picture', 'is_featured'];

    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }
}
