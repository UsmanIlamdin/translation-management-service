<?php

namespace App\Services;

use App\Filters\TranslationBundleFilters;
use App\Filters\TranslationFilters;

interface TranslationBundleServiceInterface
{
    public function exportI8nFormat(TranslationBundleFilters $filters, string $basePath);
}
