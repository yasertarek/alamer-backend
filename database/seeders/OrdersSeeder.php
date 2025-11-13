<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = \App\Models\User::all();
        $services = \App\Models\Service::all();
        foreach ($users as $user) {
            // Each user places between 1 to 3 orders
            $orderCount = rand(1, 3);
            for ($i = 0; $i < $orderCount; $i++) {
                $service = $services->random();
                $order = \App\Models\Order::create([
                    'user_id' => $user->id,
                    'service_id' => $service->id,
                    'status' => 'pending',
                ]);

                // Optionally, create a rating for completed orders
                if (rand(0, 1)) { // 50% chance to create a rating
                    \App\Models\Rating::create([
                        'order_id' => $order->id,
                        'rating' => rand(1, 5),
                        'comment' => 'Sample comment for order #' . $order->id,
                    ]);
                }
            }
        }
    }
}
