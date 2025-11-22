<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'language_id',
        'title',
        'subtitle',
        'content',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'address',
    ];

    protected $casts = [
        'content' => 'array',
    ];
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($serviceTranslation) {
            if (!$serviceTranslation->slug) {
                $serviceTranslation->slug = static::generateSlug($serviceTranslation->title);
            }
        });
    }

    private static function generateSlug($title)
    {
        $slug = preg_replace('/\s+/u', '-', trim($title));
        $slug = preg_replace('/-+/', '-', $slug);
        return mb_strtolower($slug, 'UTF-8');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
