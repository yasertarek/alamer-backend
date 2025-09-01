<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SEOController extends Controller
{
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'title' => 'required|string',
            'meta_description' => 'required|string',
            'focus_keywords' => 'required|array',
            'focus_keywords.*' => 'string',
        ]);

        $content = $validated['content'];
        $title = $validated['title'];
        $metaDescription = $validated['meta_description'];
        $focusKeywords = $validated['focus_keywords'];

        $results = [];
        foreach ($focusKeywords as $focusKeyword) {
            $keywordInTitle = stripos($title, $focusKeyword) !== false ? 15 : 0;
            $keywordInMetaDescription = stripos($metaDescription, $focusKeyword) !== false ? 15 : 0;
            $keywordDensity = $this->calculateKeywordDensity($content, $focusKeyword);
            $readabilityScore = $this->calculateReadability($content);

            $totalScore = $keywordInTitle + $keywordInMetaDescription + $keywordDensity['score'] + $readabilityScore['score'];

            $results[] = [
                'keyword' => $focusKeyword,
                'keyword_in_title' => $keywordInTitle,
                'keyword_in_meta_description' => $keywordInMetaDescription,
                'keyword_density' => $keywordDensity,
                'readability_score' => $readabilityScore,
                'total_score' => $totalScore,
            ];
        }

        return response()->json($results);
    }

    private function calculateKeywordDensity($content, $keyword)
    {
        $content = $this->normalizeArabic($content);
        $keyword = $this->normalizeArabic($keyword);

        $wordCount = str_word_count(strip_tags($content), 1, "ءآأإؤئابةتثجحخدذرزسشصضطظعغفقكلمنهوي");
        $totalWords = count($wordCount);

        $keywordCount = mb_substr_count(mb_strtolower($content), mb_strtolower($keyword));

        $density = $totalWords > 0 ? ($keywordCount / $totalWords) * 100 : 0;

        $score = 0;
        if ($density >= 1 && $density <= 2) {
            $score = 20;
        } elseif ($density > 2) {
            $score = 10;
        }

        return [
            'density' => round($density, 2),
            'score' => $score,
        ];
    }

    private function calculateReadability($content)
    {
        $sentences = preg_split('/[.!؟]/u', $content, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = count($sentences);

        $wordCount = str_word_count(strip_tags($content), 1, "ءآأإؤئابةتثجحخدذرزسشصضطظعغفقكلمنهوي");
        $totalWords = count($wordCount);

        $readability = $sentenceCount > 0 ? $totalWords / $sentenceCount : 0;

        $score = 0;
        if ($readability <= 20) {
            $score = 20;
        } elseif ($readability <= 30) {
            $score = 10;
        }

        return [
            'readability' => round($readability, 2),
            'score' => $score,
        ];
    }

    private function normalizeArabic($text)
    {
        // Remove Arabic diacritics
        $text = preg_replace(
            '/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u',
            '',
            $text
        );
    
        // Normalize specific Arabic letters
        $text = str_replace(['أ', 'إ', 'آ'], 'ا', $text);
        $text = str_replace('ة', 'ه', $text);
    
        return $text;
    }
    
}
