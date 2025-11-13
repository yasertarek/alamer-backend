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
                    'subtitle' => 'نقدم خدمات عزل الأسطح ضد تسربات المياه بأحدث مواد العزل المعتمدة في السعودية. نوفر عزلًا حراريًا ومائيًا يحافظ على المبنى ويمنع الرطوبة والحرارة لسنوات طويلة.',
                    'content' => 'عزل الأسطح هو أول خطوة لحماية منزلك من مشاكل الرطوبة وتسرب المياه. نحن في رؤية الخليج نوفر حلولًا متكاملة لعزل الأسطح باستخدام مواد عالية الجودة مثل البولي يوريثان، البيتومين، والفوم الحراري.
فريقنا يستخدم تقنيات حديثة تضمن عزلًا محكمًا ومظهرًا جماليًا للسطح، مع ضمان يمتد لسنوات طويلة.
سواء كنت تعاني من تسربات أو ترغب في الوقاية، نقدم لك استشارة مجانية لتحديد نوع العزل المناسب لمنزلك.
                    '
                ],
                [
                    'title' => 'عزل الخزانات الأرضية والعلوية في السعودية – حماية مضمونة للمياه والخزان',
                    'slug' => 'عزل_الخزانات_الأرضية_والعلوية_في_السعودية_–_حماية_مضمونة_للمياه_والخزان',
                    'subtitle' => 'احصل على خدمة عزل الخزانات الداخلية والخارجية بمواد إيبوكسي آمنة ومعتمدة. حماية كاملة من التسربات ونمو الطحالب لضمان مياه نظيفة وصحية.',
                    'content' => 'تتعرض الخزانات بمرور الوقت للتشققات والتسربات، ما يؤدي إلى تلوث المياه وتلف الخرسانة.
نحن في رؤية الخليج نقدم خدمات عزل الخزانات من الداخل والخارج باستخدام مواد إيبوكسية آمنة ومعتمدة من وزارة المياه.
نحرص على تنظيف الخزان جيدًا، ومعالجة أي تشققات قبل تطبيق مادة العزل لضمان التصاق مثالي.
العزل لدينا يحافظ على جودة المياه ويطيل عمر الخزان دون الحاجة للصيانة المتكررة.
                    '
                ],
                [
                    'title' => 'كشف تسربات المياه بدون تكسير في السعودية – باستخدام أحدث أجهزة الاستشعار الحراري',
                    'slug' => 'tqnyat_hadetha_fi_kshf_tsrubat_almiyah_min_alesteshar_ela_altswier_alharary',
                    'subtitle' => 'نوفر خدمة كشف تسربات المياه في الأسطح والخزانات والأنابيب بدون تكسير، باستخدام أجهزة ألمانية دقيقة تعمل بالاستشعار والتصوير الحراري لتحديد مكان التسرب بدقة.',
                    'content' => 'مشاكل تسربات المياه قد تكون خفية داخل الجدران أو تحت الأرض، وتسبب ارتفاع فواتير المياه وتلف التشطيب.
نقدم لك في رؤية الخليج خدمة كشف تسربات المياه بدون تكسير باستخدام أجهزة الاستشعار الحراري والصوتي الألمانية لتحديد موقع التسرب بدقة دون أي ضرر للمكان.
فريقنا مدرّب على التعامل مع جميع أنواع التسربات سواء في المنازل، الفلل أو الخزانات.
نوفر لك تقريرًا دقيقًا وصورًا توضح مكان التسرب قبل البدء بأي إصلاح.'
                ],

            ],
            'en' => [
                [
                    'title' => $faker->sentence,
                    'slug' => $faker->unique()->sentence(),
                    'subtitle' => $faker->sentence,
                    'content' => $faker->paragraphs(3, true)
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
                ]);
            }
        }
    }
}
