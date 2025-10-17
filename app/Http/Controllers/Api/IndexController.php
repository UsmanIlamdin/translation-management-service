<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function health()
    {
        return response()->json([
            'status'  =>  \Illuminate\Http\Response::HTTP_OK,
            'service' => config('app.name'),
            'message' => 'Service is online',
            // UTC time
            'time'    => now('UTC')->toDateTimeString(),
        ],  \Illuminate\Http\Response::HTTP_OK);
    }
}
