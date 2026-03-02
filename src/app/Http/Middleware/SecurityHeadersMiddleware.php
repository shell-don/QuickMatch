<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isDevelopment = in_array(app()->environment(), ['local', 'testing']);

        if ($isDevelopment) {
            $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:*; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:*; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' ws://localhost:* http://localhost:*; frame-ancestors 'self';";
        } else {
            $csp = "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; frame-ancestors 'self';";
        }

        $headers = [
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '0',
            'Referrer-Policy' => $isDevelopment ? 'no-referrer-when-downgrade' : 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=()',
            'Content-Security-Policy' => $csp,
        ];

        if (! $isDevelopment) {
            $headers['Cross-Origin-Opener-Policy'] = 'same-origin';
            $headers['Cross-Origin-Resource-Policy'] = 'same-origin';
            $headers['Cross-Origin-Embedder-Policy'] = 'require-corp';
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
