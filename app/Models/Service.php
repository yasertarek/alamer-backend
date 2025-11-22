<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['picture', 'is_featured', 'user_id'];

    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }
    // public function rates()
    // {
    //     return $this->hasMany(ServicesRate::class);
    // }
    public function rates()
    {
        return $this->hasManyThrough(
            Rating::class,
            Order::class,
            'service_id', // Foreign key on orders table
            'order_id',   // Foreign key on ratings table
            'id',         // Local key on services table
            'id'          // Local key on orders table
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // ✅ Accessor for median rating
    public function getMedianRatingAttribute()
    {
        $ratings = $this->rates()->pluck('rating')->sort()->values();

        $count = $ratings->count();

        if ($count === 0) {
            return 0; // no ratings yet
        }

        $middle = intdiv($count, 2);

        if ($count % 2 === 0) {
            // even number of ratings → average of two middle values
            return round(($ratings[$middle - 1] + $ratings[$middle]) / 2, 2);
        } else {
            // odd number → middle value
            return $ratings[$middle];
        }
    }
    // ✅ Accessor for rating total count
    public function getCountRatingAttribute()
    {
        $ratings = $this->rates()->pluck('rating')->sort()->values();

        $count = $ratings->count();

        return $count;
    }
    public function getRatingMetricsAttribute()
    {
        // Get all rating values (e.g., from related rates)
        $ratings = $this->rates()->pluck('rating');

        // Initialize array for 1–5 star counts
        $metrics = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];

        // Count each rating occurrence
        foreach ($ratings as $rating) {
            $rounded = (int) round($rating);
            if ($rounded >= 1 && $rounded <= 5) {
                $metrics[$rounded]++;
            }
        }

        // Return array indexed from 0 (to match JS style [1★, 2★, ..., 5★])
        return array_values($metrics);
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ordersCount()
    {
        return $this->hasMany(Order::class)
            ->selectRaw('service_id, COUNT(*) as aggregate')
            ->groupBy('service_id');
    }

}
