<?php

namespace App\Http\Middleware;

use App\Services\ApiKeyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function __construct(public ApiKeyService $apiKeyService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'API key required.',
            ], 401);
        }

        $apiKey = $this->apiKeyService->validateApiKey($token);

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key.',
            ], 401);
        }

        if (! $apiKey->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'API key is inactive or expired.',
            ], 401);
        }

        if (! $apiKey->canMakeRequest()) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit exceeded for today.',
            ], 429);
        }

        $userCount = \App\Models\User::count();
        if (! $apiKey->plan->canAccessUsers($userCount)) {
            return response()->json([
                'success' => false,
                'message' => 'User limit exceeded. Please upgrade your plan.',
            ], 403);
        }

        if ($apiKey->ip_whitelist) {
            $allowedIps = explode(',', $apiKey->ip_whitelist);
            $clientIp = $request->ip();

            if (! in_array($clientIp, array_map('trim', $allowedIps))) {
                return response()->json([
                    'success' => false,
                    'message' => 'IP address not allowed.',
                ], 403);
            }
        }

        $request->merge(['api_key' => $apiKey]);
        $request->setUserResolver(fn () => $apiKey);

        $response = $next($request);

        $apiKey->recordUsage(
            $request->getPathInfo(),
            $request->method(),
            $response->getStatusCode(),
            $request->ip()
        );

        return $response;
    }
}
