<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\BlogTranslation;
use App\Models\Language;

class BlogTranslationSeeder extends Seeder
{
    public function run()
    {
        $blog = Blog::first();
        $languages = Language::all();

        if (!$blog) {
            $this->command->error('No blog found. Please ensure the BlogSeeder has been run and a blog exists.');
            return;
        }

        foreach ($languages as $language) {
            $lang = $language->code;
            $txtContent = [
                "ar" => [
                    'title' => 'تقنيات حديثة في كشف تسربات المياه: من الاستشعار إلى التصوير الحراري',
                    'subTitle' => 'نبذة: تستعرض المقالة أحدث التقنيات المستخدمة في كشف تسربات المياه، مثل أجهزة الاستشعار والتصوير الحراري، وتشرح كيفية عملها وأهميتها في الحفاظ على البنية التحتية وتقليل الفاقد من المياه.',
                    'content' => '
يعتبر كشف تسربات المياه من أهم الخدمات التي تحافظ على البنية التحتية للمباني، سواء كانت سكنية أو تجارية. تتسبب تسربات المياه في أضرار كبيرة قد تشمل تلف الأسطح والخزانات والجدران، مما يؤدي إلى تكاليف باهظة للإصلاح والصيانة. لحسن الحظ، تطورت التقنيات الحديثة بشكل كبير في مجال كشف التسربات، من بينها تقنيات الاستشعار والتصوير الحراري.

تقنيات الاستشعار في كشف تسربات المياه
تقنيات الاستشعار تعد من الأدوات الأساسية في الكشف المبكر عن تسربات المياه. تعتمد هذه التقنية على استخدام أجهزة استشعار حساسة يمكنها اكتشاف أدنى تغييرات في رطوبة الأسطح والجدران. تساهم هذه الأجهزة في تحديد موقع التسرب بدقة دون الحاجة إلى تكسير أو حفر في المبنى، مما يوفر وقتًا وجهدًا كبيرين.

التصوير الحراري: الحل الأمثل لكشف التسربات
التصوير الحراري يعتبر من التقنيات المتقدمة والفعالة في كشف تسربات المياه. يعتمد هذا النوع من التصوير على استخدام كاميرات حرارية تستطيع رصد الفروقات في درجات الحرارة على سطح الجدران والأسطح. تظهر التسربات على هيئة بقع باردة أو حارة، مما يمكن الفرق الفنية من تحديد موقعها بدقة وسرعة.

دور التكنولوجيا في حماية المباني في الرياض
مدينة الرياض، كواحدة من أكبر المدن في المملكة العربية السعودية، تشهد تطورًا عمرانيًا كبيرًا، مما يزيد من الحاجة إلى خدمات كشف تسربات المياه. استخدام التقنيات الحديثة في هذا المجال يساعد في حماية المباني من الأضرار المحتملة الناجمة عن التسربات. بالإضافة إلى ذلك، فإن استخدام تقنيات العزل المائي والحراري يلعب دورًا مهمًا في الحفاظ على سلامة المباني.

العزل المائي والحراري: الوقاية خير من العلاج
العزل المائي والحراري من الخطوات الوقائية الأساسية التي تساهم في حماية المباني من تسربات المياه. يتم تطبيق العزل المائي على الأسطح والخزانات لمنع تسرب المياه إلى داخل المبنى. أما العزل الحراري فيساعد في الحفاظ على درجات الحرارة الداخلية، مما يقلل من تأثير التغيرات المناخية ويحافظ على البنية الإنشائية.

عزل الأسطح والخزانات في الرياض
عزل الأسطح والخزانات في الرياض أصبح ضرورة ملحة بسبب الظروف المناخية القاسية التي تشهدها المنطقة. استخدام مواد عزل عالية الجودة وتقنيات تطبيق متقدمة يضمن حماية فعالة ضد تسربات المياه. يُنصح دائماً بالاستعانة بشركات متخصصة في هذا المجال لضمان الحصول على أفضل النتائج.

خاتمة
تقنيات كشف تسربات المياه الحديثة من الاستشعار إلى التصوير الحراري تقدم حلولاً فعالة لحماية المباني من الأضرار الناجمة عن التسربات. في الرياض، تعتبر هذه التقنيات ضرورة لضمان الحفاظ على البنية التحتية للمباني. إضافةً إلى ذلك، فإن العزل المائي والحراري يعدان من الإجراءات الوقائية الهامة التي يجب اتباعها لضمان سلامة المباني على المدى الطويل. استخدام هذه التقنيات المتقدمة سيساهم بالتأكيد في تقليل التكاليف وزيادة العمر الافتراضي للمباني.'
                ],
                "en" => [
                    'title' => 'Modern Technologies in Water Leak Detection: From Sensing to Thermal Imaging',
                    'subTitle' => 'Abstract: The article reviews the latest technologies used in water leak detection, such as sensors and thermal imaging, and explains how they work and their importance in maintaining infrastructure and reducing water loss.',
                    'content' => 'Water leak detection is one of the most important services that maintain the infrastructure of buildings, whether residential or commercial. Water leaks cause significant damage that may include damage to surfaces, tanks and walls, leading to high costs for repair and maintenance. Fortunately, modern technologies have developed significantly in the field of leak detection, including sensing and thermal imaging technologies.

Sensing technologies in water leak detection
Sensing technologies are one of the basic tools in the early detection of water leaks. This technology relies on the use of sensitive sensors that can detect the slightest changes in the humidity of surfaces and walls. These devices help to accurately locate the leak without the need to break or drill into the building, saving a lot of time and effort.

Thermal imaging: the ideal solution for detecting leaks
Thermal imaging is one of the advanced and effective technologies in detecting water leaks. This type of imaging relies on the use of thermal cameras that can detect temperature differences on the surface of walls and surfaces. Leaks appear as cold or hot spots, which enables technical teams to accurately and quickly locate them.

The role of technology in protecting buildings in Riyadh
Riyadh, as one of the largest cities in the Kingdom of Saudi Arabia, is witnessing significant urban development, which increases the need for water leak detection services. The use of modern technologies in this field helps protect buildings from potential damage caused by leaks. In addition, the use of water and thermal insulation techniques plays an important role in maintaining the safety of buildings.

Water and thermal insulation: Prevention is better than cure
Water and thermal insulation are basic preventive steps that contribute to protecting buildings from water leaks. Water insulation is applied to roofs and tanks to prevent water from leaking into the building. As for thermal insulation, it helps maintain internal temperatures, which reduces the impact of climate change and preserves the structural structure.

Roof and tank insulation in Riyadh
Roof and tank insulation in Riyadh has become an urgent necessity due to the harsh climatic conditions witnessed by the region. The use of high-quality insulation materials and advanced application techniques ensures effective protection against water leaks. It is always advisable to use companies specialized in this field to ensure obtaining the best results.

Conclusion
Modern water leak detection technologies from sensing to thermal imaging offer effective solutions to protect buildings from damage caused by leaks. In Riyadh, these technologies are essential to ensure the preservation of building infrastructure. In addition, waterproofing and thermal insulation are important preventive measures that must be followed to ensure the long-term safety of buildings. The use of these advanced technologies will certainly contribute to reducing costs and increasing the lifespan of buildings.'
                ],
            ];
            $langContent = $txtContent[$lang];
            BlogTranslation::create([
                'blog_id' => $blog->id,
                'language_id' => $language->id,
                'title' => $langContent['title'],
                'subtitle' => $langContent['subTitle'],
                'content' => $langContent['subTitle'],
            ]);
        }
    }
}
