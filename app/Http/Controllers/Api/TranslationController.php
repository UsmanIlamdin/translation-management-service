<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    /**
     * Inject the TranslationService.
     */
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Display a listing of translations.
     */
    public function index()
    {
        // Return all translations using the service
        $translations = $this->translationService->getAllTranslations();

        return response()->json([
            'status' => 200,
            'data' => $translations,
        ]);
    }

    /**
     * Store a newly created translation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|max:10',
            'key' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ]);

        $translation = $this->translationService->createTranslation($validated);

        return response()->json([
            'status' => 201,
            'data' => $translation,
        ]);
    }

    /**
     * Display the specified translation.
     */
    public function show(string $id)
    {
        $translation = $this->translationService->getTranslationById($id);

        if (!$translation) {
            return response()->json([
                'status' => 404,
                'message' => 'Translation not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $translation,
        ]);
    }

    /**
     * Update the specified translation.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'locale' => 'sometimes|string|max:10',
            'key' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ]);

        $updated = $this->translationService->updateTranslation($id, $validated);

        if (!$updated) {
            return response()->json([
                'status' => 404,
                'message' => 'Translation not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $updated,
        ]);
    }

    /**
     * Remove the specified translation.
     */
    public function destroy(string $id)
    {
        $deleted = $this->translationService->deleteTranslation($id);

        if (!$deleted) {
            return response()->json([
                'status' => 404,
                'message' => 'Translation not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Translation deleted successfully',
        ]);
    }
}
