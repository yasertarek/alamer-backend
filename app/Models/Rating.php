<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'rating',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    // Shortcut to get the user who created the order
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'id',        // Order.id (local key on orders)
            'id',        // User.id (local key on users)
            'order_id',  // Rating.order_id (FK to orders)
            'user_id'    // Order.user_id (FK to users)
        );
    }
}
