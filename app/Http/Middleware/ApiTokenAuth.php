<?php

namespace App\Http\Middleware;

use App\Constants\MessageConstants;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ApiTokenAuth
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $providedToken = $request->bearerToken() ?? $request->header('X-API-TOKEN');
        if ($providedToken && str_starts_with(strtolower($providedToken), 'bearer ')) {
            $providedToken = trim(substr($providedToken, 7));
        }
        $expectedToken = config('app.api_auth_token');

        if (!$providedToken || $providedToken !== $expectedToken) {
            return response()->json(
                [
                    'message' => MessageConstants::UNAUTHORIZED
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
