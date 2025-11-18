<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageSection;

class LandingPageSectionsSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'key' => 'header',
                'order' => 1,
                'data' => [
                    'backgroundImage' => 'default/landing-head.webp',
                    'heading' => '          مؤسسة رؤية الخليج لجميع أنواع العزل المائي<br />
          معًا، نضمن لكم حماية دائمة لمنازلكم...<br />
          خدمات عزل مبتكرة تحت رعايتكم',
                    'phoneNumber' => '+966507228651',
                    'phoneText' => 'اتصل بنا',
                ],
            ],
            [
                'key' => 'cta',
                'order' => 2,
                'data' => [
                    'title' => 'لحل مشاكل تسربات المياه وعزل الاسطح تواصل معنا الآن !',
                    'phoneNumber' => '+966507228651',
                    'phoneText' => 'اتصل بنا الآن',
                    'whatsappNumber' => '+966507228651',
                    'whatsappText' => 'تواصل عبر واتساب',
                    'backgroundImage' => 'default/landing-head.webp',
                ],
            ],
            [
                'key' => 'about_article_1',
                'order' => 3,
                'data' => [
                    [
                        'subtitle' => 'من نحن',
                        'title' => 'نحن فريق عمل خبير بعزل الأسطح وكشف التسربات بأفضل الأسعار',
                        'description' => 'أفضل طرق كشف التسربات بأحدث الاجهزة التكنولوجية المتخصصة في الكشف عن التسربات المائية في المباني والمنشئات.',
                        'link' => 'http://roayet-elkhaleej.com/about',
                    ],
                    [
                        'subtitle' => 'مـهـمـتـنـا',
                        'title' => 'مباني ومنازل آمنة من مخاطر تسربات المياه والحرارة العالية',
                        'description' => 'في مؤسسة رؤية الخليج نهتم بتنفيذ أفضل الحلول لعزل الاسطح والمباني من المياه والحرارة وكشف التسربات دون تكسير او تكلفة زائدة.',
                    ]
                ],
            ],
            [
                'key' => 'about_article_2',
                'order' => 4,
                'data' => [
                    'image' => '',
                    'subtitle' => 'لماذا بدأنا مؤسسة رؤية الخليج',
                    'title' => 'أنشأنا رؤية الخليج لنضمن لك بيتاً آمناً وخالياً من التسربات. نحن خبراء العزل والكشف الدقيق لجودة حياة أفضل',
                    'description' => 'لا خوف عليكم، مؤسسة رؤية الخليج لديكم تقدم أفضل الحلول لجميع انواع مشاكل تسربات المياه وارتفاع حرارة المنازل بتطبيق احدث الوسائل التكنولوجية المطورة خصيصا لمواكبة بيئة المملكة العربية السعودية.',
                    'buttonText' => 'إكتشف قصتنا',
                    'image' => 'default/landing-cta-bg.webp',
                ],
            ],
        ];

        foreach ($sections as $section) {
            LandingPageSection::create($section);
        }
    }
}
