<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rating;

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
        // Each user places between 1 to 3 orders
        $ratings = [
            [
                "rating" => 5,
                "comment" => "خدمة فوق الممتازة من رؤية الخليج. جوني بسرعة وفحصوا التسريب بدقة، وشغلهم مرتب ونظيف. فعلاً فرق كبير بعد العزل."
            ],
            [
                "rating" => 5,
                "comment" => "تعامل راقي وشغل احترافي. الفريق عرف المشكلة من أول زيارة وانتهى التسريب تماماً. أنصح فيهم وبقوة."
            ],
            [
                "rating" => 4,
                "comment" => "ما شاء الله تبارك الله شغلهم مره ممتاز. استخدموا مواد قوية واهتموا بكل التفاصيل. التجربة كانت ناجحة جداً."
            ],
            [
                "rating" => 5,
                "comment" => "شركة صادقة ومواعيدهم دقيقة. سووا عزل للسطح عندي وحلّوا مشكلة الحرارة بشكل واضح. شكراً لكم على الجودة."
            ],
            [
                "rating" => 4,
                "comment" => "خدمة رائعة وسريعة. الفنيين محترفين ويشرحون كل خطوة قبل يبدأون. النتائج ممتازة والتسريب اختفى."
            ],
            [
                "rating" => 5,
                "comment" => "أول مرة أتعامل مع رؤية الخليج، وبصراحة ما ندمت. شغلهم نظيف، أسعار مناسبة، والنتيجة مرضية جداً."
            ],
            [
                "rating" => 5,
                "comment" => "أفضل خدمة عزل جربتها. حسّيت بفرق كبير في برودة البيت بعد العزل، والتسريب ما عاد رجع. شغل يستاهل."
            ],
            [
                "rating" => 4,
                "comment" => "الفريق محترم ومتمكن، خلصوا الشغل بسرعة وما تركوا أي فوضى. الجودة واضحة من أول يوم. تجربة ممتازة."
            ],
            [
                "rating" => 5,
                "comment" => "أول مرة أتعامل مع رؤية الخليج، وبصراحة ما ندمت. شغلهم نظيف، أسعار مناسبة، والنتيجة مرضية جداً."
            ],
        ];
        foreach ($users as $key => $value) {
            # code...
            $service = $services->random();
            $order = \App\Models\Order::create([
                'user_id' => $value->id,
                'service_id' => $service->id,
                'status' => 'completed',
            ]);

            Rating::create([
                'order_id' => $order->id,
                'rating' => $ratings[$key]['rating'],
                'comment' => $ratings[$key]['comment'],
            ]);
        }
    }
}
