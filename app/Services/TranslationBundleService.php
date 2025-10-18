<?php

namespace App\Services;

use App\Repositories\TranslationRepository;
use App\Filters\TranslationBundleFilters;
use Illuminate\Support\Facades\Storage;

class TranslationBundleService implements TranslationBundleServiceInterface
{
    protected TranslationRepository $translationRepository;

    public function __construct(TranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * Export translations to JSON files using Laravel Storage.
     *
     * @param TranslationBundleFilters $filters
     * @param string $basePath
     * @return array List of generated file paths
     */
    public function exportI8nFormat(TranslationBundleFilters $filters, string $basePath = "i18n"): array
    {
        $query = $this->translationRepository->baseQuery();
        $filters->apply($query);

        $files = [];
        $grouped = [];

        $query->chunk(5000, function ($translations) use (&$grouped) {
            foreach ($translations as $translation) {
                foreach ($translation->tags as $tag) {
                    $locale = $translation->locale;
                    $tagName = $tag->name;
                    $key = $translation->key;

                    $grouped[$locale][$tagName][$key] = $translation->content;
                }
            }
        });

        foreach ($grouped as $locale => $tags) {
            foreach ($tags as $tagName => $data) {
                $path = "i18n/{$locale}/{$tagName}.json";
                Storage::makeDirectory("i18n/{$locale}");
                Storage::disk('s3')->put(
                    $path,
                    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                    'public'
                );
                $files[] = Storage::disk('s3')->url($path);
            }
        }

        return $files;
    }
}
