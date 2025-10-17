<?php

namespace App\Services;

use App\Filters\TranslationFilters;

interface TranslationServiceInterface
{
    public function getFilteredTranslations(TranslationFilters $filters);
    public function createTranslation(array $validated);
    public function updateTranslation(int $id, array $validated);
    public function deleteTranslation(int $id);
}
