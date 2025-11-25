<?php
// app/Http/Middleware/ValidateSystemAPIKey.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\System;

class ValidateSystemAPIKey
{
    /**
     * Validate that the request comes from a valid registered system
     */
    public function handle(Request $request, Closure $next)
    {
        $systemCode = $request->header('X-System-Code');
        $apiKey = $request->header('X-API-Key');

        if (!$systemCode || !$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'System authentication required',
            ], 401);
        }

        // Validate system
        $system = System::where('system_code', $systemCode)
            ->where('api_key', $apiKey)
            ->where('is_active', 1)
            ->first();

        if (!$system) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid system credentials',
            ], 401);
        }

        // Attach system to request
        $request->merge(['requesting_system' => $system]);

        return $next($request);
    }
}