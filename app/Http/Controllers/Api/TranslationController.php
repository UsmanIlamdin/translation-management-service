<?php

namespace App\Http\Controllers\Api;

use App\Constants\MessageConstants;
use App\Filters\TranslationFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\TranslationResource;
use App\Services\TranslationService;
use App\Validator\TranslationRequestValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    /**
     * @param TranslationService $translationService
     */
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }


    /**
     * @param Request $request
     * @param string|null $locale
     * @param string|null $tag
     * @return JsonResponse
     */
    public function index(Request $request, ?string $locale = null, ?string $tag = null): JsonResponse
    {
        $filters = new TranslationFilters($request, $locale, $tag);
        $translations = $this->translationService->getFilteredTranslations($filters);
        return response()->json([
            'success' => true,
            'meta' => [
                'current_page'   => $translations->currentPage(),
                'per_page'       => $translations->perPage(),
                'next_page_url'  => $translations->nextPageUrl(),
                'prev_page_url'  => $translations->previousPageUrl(),
                'has_more_pages' => $translations->hasMorePages(),
            ],
            'data' => TranslationResource::collection($translations),
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = (new TranslationRequestValidator($request->all()))->validate();
        $translation = $this->translationService->createTranslation($validated);

        return response()->json([
            'success' => true,
            'data' => new TranslationResource($translation)
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = (new TranslationRequestValidator($request->all(), $id))->validate();
        $translation = $this->translationService->updateTranslation($id, $validated);
        if (!$translation) {
            return response()->json([
                'success' => false,
                'message' => MessageConstants::NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new TranslationResource($translation)
        ], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->translationService->deleteTranslation($id);

        return response()->json([
            'status' => true,
            'data' => null
        ], Response::HTTP_NO_CONTENT);
    }
}
