<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Language;
use App\Models\ServiceTranslation;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Auth;


class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $languageCode = $request->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->first();
        $searchQuery = $request->input('search');
        $perPage = $request->input('per_page', 10);
        if (!$language) {
            return response()->json(['message' => 'Language not supported.'], 400);
        }


        $query = Service::with([
            'translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            },
            'user',
            'rates'
        ])->select('services.*');


        if ($searchQuery) {
            $query->whereHas('translations', function ($q) use ($language, $searchQuery) {
                $q->where('language_id', $language->id)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', "%$searchQuery%")
                            ->orWhere('subtitle', 'like', "%$searchQuery%")
                            ->orWhere('content', 'like', "%$searchQuery%");
                    });
            });
        }

        $services = $query->paginate($perPage);

        return ServiceResource::collection($services);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'picture' => 'nullable|image|max:2048',
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255|unique:service_translations,title',
            'translations.*.subtitle' => 'required|string|max:255',
            'translations.*.content' => 'required|string',
        ]);

        $service = new Service();

        $service->user_id = Auth::id();

        if ($request->hasFile('picture')) {
            $service->picture = $request->file('picture')->store('service_pictures', 'public');
        }

        $service->save();

        foreach ($validatedData['translations'] as $translation) {
            $translation['service_id'] = $service->id;
            ServiceTranslation::create($translation);
        }

        return response()->json(["data" => $service->load('translations'), "message" => "تم إنشاء الخدمة بنجاح !"], 201);
    }

    public function show($slug, Request $request)
    {
        $languageCode = $request->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->firstOrFail();

        $serviceTranslation = ServiceTranslation::where('slug', $slug)
            ->where('language_id', $language->id)
            ->firstOrFail();

        $service = Service::with(['translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }])
            ->findOrFail($serviceTranslation->service_id);

        return new ServiceResource($service);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'picture' => 'nullable|image|max:2048',
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.subtitle' => 'required|string|max:255',
            'translations.*.content' => 'required|string',
        ]);

        $service = Service::findOrFail($id);

        if ($request->hasFile('picture')) {
            $service->picture = $request->file('picture')->store('service_pictures', 'public');
        }

        $service->save();

        foreach ($validatedData['translations'] as $translation) {
            $serviceTranslation = ServiceTranslation::where('service_id', $service->id)
                                                    ->where('language_id', $translation['language_id'])
                                                    ->first();

            if ($serviceTranslation) {
                $serviceTranslation->update($translation);
            } else {
                $translation['service_id'] = $service->id;
                ServiceTranslation::create($translation);
            }
        }

        return response()->json(["data" => new ServiceResource($service->load('translations')), "message" => "تم تحديث الخدمة بنجاح !"], 200);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $languageCode = $request->header('Language-Code');
        $perPage = $request->input('per_page', 10); // New line to get per_page parameter, default to 10 if not provided
        $language = Language::where('code', $languageCode)->first();

        if (!$language) {
            return response()->json(['message' => 'Language not supported'], 400);
        }

        $services = Service::whereHas('translations', function ($query) use ($language, $search) {
            $query->where('language_id', $language->id)
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('subtitle', 'like', "%$search%")
                        ->orWhere('content', 'like', "%$search%");
                });
        })
            ->with([
                'translations' => function ($query) use ($language) {
                    $query->where('language_id', $language->id);
                },
            ])
            ->paginate($perPage);

        if ($services->isEmpty()) {
            return response()->json(['message' => 'No results found'], 404);
        }

        return ServiceResource::collection($services);
    }
}
