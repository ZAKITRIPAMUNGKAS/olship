<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     * Validates the Bearer token from the incoming API request.
     * The expected token is stored in the WMS_API_TOKEN environment variable.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = config('services.api.wms_token');

        if (empty($expectedToken)) {
            return response()->json([
                'success' => false,
                'message' => 'API token not configured on server.',
            ], 500);
        }

        $bearerToken = $request->bearerToken();

        if (!$bearerToken || !hash_equals($expectedToken, $bearerToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API token.',
            ], 401);
        }

        return $next($request);
    }
}
