<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Navbar;
use App\Models\NavbarTranslation;
use App\Models\Language;
use App\Http\Resources\NavbarResource;
use Illuminate\Support\Facades\Validator;
// use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Database\QueryException;


class NavbarController extends Controller
{
    public function index(Request $request)
    {
        $languageCode = $request->header('Language-Code', 'ar'); // Default to 'ar' (Arabic)

        $language = Language::where('code', $languageCode)->firstOrFail();

        // Fetch the navbar items with translations for the specific language
        $navbars = Navbar::with([
            'translations' => function ($query) use ($language) {
                $query->whereHas('language', function ($query) use ($language) {
                    $query->where('language_id', $language->id);
                });
            }
        ])->get();

        return NavbarResource::collection($navbars);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nav' => 'required|array',
            'nav.*.link' => 'required|string|max:255',
            'nav.*.group' => 'required',
            'nav.*.order' => 'required',
            'nav.*.translations' => 'required|array',
            'nav.*.translations.*.language_id' => 'required|exists:languages,id',
            'nav.*.translations.*.title' => 'required|string|max:255',
        ], [
            'nav.required' => 'عنصر ال nav مطلوب!',
            'nav.array' => 'عنصر ال nav يجب ان يكون من النوع array!',
            'nav.*.link.required' => 'يجب إدخال الرابط !',
            'nav.*.link.string' => 'يجب أن يكون الرابط نص !',
            'nav.*.link.max:255' => 'يجب أن لا يزيد الرابط عن 255 حرف !',
            'nav.*.group.required' => 'يجب تحديد نوع قائمة الرابط !',
            'nav.*.order.required' => 'يجب ادخال ترتيب الرابط !',
            'nav.*.translations.required' => 'يجب ادخال قائمة الترجمة !',
            'nav.*.translations.array' => 'يجب ان تكون الترجمة من النوع array !',
            'nav.*.translations.*.language_id.required' => 'يجب تحديد ال ID الخاص باللغة',
            'nav.*.translations.*.language_id.exists' => 'الـ ID الذي تم إدخاله غير صالح !',
            'nav.*.translations.*.title.required' => 'يجب إدخال اسم الرابط !',
            'nav.*.translations.*.title.string' => 'يجب أن يكون نوع اسم الرابط نصاً !',
            'nav.*.translations.*.title.max' => 'يجب ان يكون الحد الأقصى لعدد حروف اسم الرابط 255 حرف فقط !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $finalResult = array();

        try {
            foreach ($request->nav as $navItem) {

                $navbar;
                // update or create
                if (isset($navItem['id'])) {
                    $navbar = Navbar::findOrFail($navItem['id']);
                    $navbar->update([
                        "link" => $navItem['link'],
                        "group" => $navItem['group'],
                        "order" => $navItem['order'],
                    ]);
                } else {
                    $navbar = Navbar::create([
                        "link" => $navItem['link'],
                        "group" => $navItem['group'],
                        "order" => $navItem['order'],
                    ]);
                }
                foreach ($navItem['translations'] as $translation) {

                    // $language = Language::where('code', $translation['language_code'])->first();
                    if (isset($translation['id'])) {
                        $NavbarTranslation = NavbarTranslation::findOrFail($translation['id']);
                        $NavbarTranslation->update($translation);
                    } else {
                        $translation['navbar_id'] = $navbar->id;
                        NavbarTranslation::create($translation);
                    }
                }
                array_push($finalResult, new NavbarResource($navbar->load('translations')));
            }

            return response()->json($finalResult, 201);
        } catch (QueryException $error) {
            // Check if the error is due to the unique constraint violation
            if ($error->errorInfo[1] == 1062) { // 1062 is the MySQL error code for a duplicate entry
                return response()->json([
                    'message' => 'The combination of navbar link and title language already exists in an order.',
                    'status' => false,
                ], 409); // 409 Conflict status code
            }

            // Handle other database errors
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $error->getMessage(),
                'status' => false,
            ], 500); // 500 Internal Server Error
        }




    }

    public function destroy($id)
    {
        $navbar = Navbar::find($id);
        $navbar->delete();

        return response()->json(["message" => 'تم حذف الرابط بنجاح'], 201);
    }
}
