<?php

namespace App\Services;

use App\Filters\TranslationFilters;
use App\Models\Tag;
use App\Models\Translation;
use App\Repositories\TranslationRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TranslationService implements TranslationServiceInterface
{
    protected TranslationRepository $translationRepository;

    /**
     * @param TranslationRepository $translationRepository
     */
    public function __construct(TranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * @param TranslationFilters $filters
     * @return Paginator
     */
    public function getFilteredTranslations(TranslationFilters $filters): Paginator
    {
        $query = $this->translationRepository->baseQuery();
        $filters->apply($query);

        return $this->translationRepository->paginate($query, $filters->getPerPage());
    }

    /**
     * @param array $validated
     * @return mixed
     */
    public function createTranslation(array $validated): mixed
    {
        $translation = $this->translationRepository->store($validated);
        $tagIds = collect($validated['tags'])->map(function ($name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        });
        $translation->tags()->sync($tagIds);

        return $translation->load('tags');
    }

    /**
     * @param int $id
     * @param array $validated
     * @return Translation|null
     */
    public function updateTranslation(int $id, array $validated): Translation|null
    {
        try {
            $translation = $this->translationRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
        $translation->update([
            'locale' => $validated['locale'] ?? $translation->locale,
            'key' => $validated['key'] ?? $translation->key,
            'content' => $validated['content'] ?? $translation->content,
        ]);

        if (isset($validated['tags'])) {
            $tagIds = collect($validated['tags'])->map(fn($name) =>
            Tag::firstOrCreate(['name' => $name])->id);
            $translation->tags()->sync($tagIds);
        }

        return $translation->load('tags');
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteTranslation(int $id): void
    {
        $translation = $this->translationRepository->getById($id);
        $translation->tags()->detach();

        $translation->delete();
    }
}
