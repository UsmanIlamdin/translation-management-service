<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status'  =>  Response::HTTP_OK,
            'service' => config('app.name'),
            'message' => 'Service is online',
            'time'    => now('UTC')->toDateTimeString(),
        ], Response::HTTP_OK);
    }
}
