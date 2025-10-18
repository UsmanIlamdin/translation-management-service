<?php

namespace App\Http\Controllers\Api;

use App\Constants\MessageConstants;
use App\Filters\TranslationBundleFilters;
use App\Http\Controllers\Controller;
use App\Services\TranslationBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationBundleController extends Controller
{
    protected TranslationBundleService $translationBundleService;

    /**
     * @param TranslationBundleService $translationBundleService
     */
    public function __construct(TranslationBundleService $translationBundleService)
    {
        $this->translationBundleService = $translationBundleService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function export(Request $request): JsonResponse
    {
        $filters = new TranslationBundleFilters($request);
        $files = $this->translationBundleService->exportI8nFormat($filters);

        return response()->json([
            'success' => true,
            'message' => MessageConstants::FILEs_GENERATED,
            'files' => $files,
        ], Response::HTTP_OK);
    }
}
