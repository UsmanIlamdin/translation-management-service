<?php

namespace App\Services;

use App\Repositories\TranslationRepository;

class TranslationService implements TranslationServiceInterface
{
    protected TranslationRepository $translationRepository;

    /**
     * Inject the translation repository.
     */
    public function __construct(TranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function getAllTranslations()
    {
        return $this->translationRepository->getAll();
    }
}
