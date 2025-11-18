<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\User;
use App\Models\Language;
use Faker\Factory as Faker;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $user = User::first();
        $languages = Language::whereIn('code', ['en', 'ar'])->get();
        $pictures = [
            's1.jpeg',
            's2.png',
            's3.png',
            's4.jpg',
        ];

        $services = [
            'ar' => [
                [
                    'title' => 'عزل الأسطح من تسربات المياه في السعودية – حماية شاملة لمنزلك',
                    'slug' => 'عزل_الأسطح_من_تسربات_المياه_في_السعودية_–_حماية_شاملة_لمنزلك',
                    'subtitle' => 'نقدم خدمات عزل الأسطح ضد تسربات المياه...',
                    'content' => 'عزل الأسطح هو أول خطوة...',
                    "meta_title" => "خدمات عزل الأسطح في السعودية - حماية فعالة ضد تسربات المياه",
                    "meta_description" => "احصل على أفضل خدمات عزل الأسطح...",
                    "meta_keywords" => "عزل الأسطح, تسربات المياه, عزل حراري, عزل مائي, السعودية",
                    "address" => "123 شارع العزل، الرياض، السعودية"
                ],
                [
                    'title' => 'عزل الخزانات الأرضية والعلوية في السعودية – حماية مضمونة للمياه والخزان',
                    'slug' => 'عزل_الخزانات_الأرضية_والعلوية_في_السعودية_–_حماية_مضمونة_للمياه_والخزان',
                    'subtitle' => 'احصل على خدمة عزل الخزانات الداخلية...',
                    'content' => 'تتعرض الخزانات بمرور الوقت...',
                    "meta_title" => "عزل الخزانات الأرضية والعلوية في السعودية | عزل إيبوكسي معتمد – رؤية الخليج",
                    "meta_description" => "عزل الخزانات الأرضية والعلوية بمواد إيبوكسي معتمدة...",
                    "meta_keywords" => "عزل الخزانات, عزل الخزانات الأرضية...",
                    "address" => "رؤية الخليج – خدمات عزل الخزانات في جميع مدن السعودية"
                ],
                [
                    'title' => 'كشف تسربات المياه بدون تكسير في السعودية – باستخدام أحدث أجهزة الاستشعار الحراري',
                    'slug' => 'كشف_تسربات_المياه_بدون_تكسير_في_السعودية_–_باستخدام_أحدث_أجهزة_الاستشعار_الحراري',
                    'subtitle' => 'نوفر خدمة كشف تسربات المياه...',
                    'content' => 'مشاكل تسربات المياه قد تكون خفية...',
                    "meta_title" => "كشف تسربات المياه بدون تكسير في السعودية | أجهزة حرارية وصوتية دقيقة – رؤية الخليج",
                    "meta_description" => "كشف تسربات المياه في المنازل والفلل والخزانات...",
                    "meta_keywords" => "كشف تسربات المياه, كشف تسربات بدون تكسير...",
                    "address" => "رؤية الخليج – خدمات كشف التسربات بأحدث التقنيات في السعودية"
                ]
            ],
            'en' => [
                [
                    'title' => $faker->sentence,
                    'slug' => $faker->unique()->sentence(),
                    'subtitle' => $faker->sentence,
                    'content' => $faker->paragraphs(3, true),
                    "meta_title" => "",
                    "meta_description" => "",
                    "meta_keywords" => "",
                    "address" => "",
                ]
            ]
        ];
        for ($i = 0; $i < 3; $i++) {
            $service = Service::create([
                'picture' =>  'service_pictures/' . $pictures[$i],
                'is_featured' => $faker->randomKey([true, false]),
                'user_id' => $user->id,
            ]);

            foreach ($languages as $language) {
                $langCode = $language->code;
                ServiceTranslation::create([
                    'service_id' => $service->id,
                    'language_id' => $language->id,
                    'title' => $language->code === 'en'
                        ? $faker->unique()->sentence()
                        : $services['ar'][$i]['title'],
                    'subtitle' => $language->code === 'en'
                        ? $services['en'][0]['subtitle']
                        : $services['ar'][$i]['subtitle'],
                    'content' => $language->code === 'en'
                        ? $services['en'][0]['content']
                        : $services['ar'][$i]['content'],
                    'slug' => $language->code === 'en'
                        ? $faker->unique()->sentence()
                        : $services['ar'][$i]['slug'],
                    'meta_title' => $services['ar'][$i]['meta_title'],
                    'meta_description' => $services['ar'][$i]['meta_description'],
                    'meta_keywords' => $services['ar'][$i]['meta_keywords'],
                    'address' => $services['ar'][$i]['address'],
                ]);
            }
        }
    }
}
