<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSuspiciousRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['TRACE', 'TRACK'], true)) {
            abort(405);
        }

        $target = strtolower(rawurldecode($request->getRequestUri()));

        foreach ($this->blockedPatterns() as $pattern) {
            if (str_contains($target, $pattern)) {
                abort(403);
            }
        }

        return $next($request);
    }

    /**
     * @return array<int, string>
     */
    private function blockedPatterns(): array
    {
        return [
            "\0",
            '../',
            '..\\',
            '/.env',
            '/vendor/',
            '/storage/logs/',
            '/wp-admin',
            '/wp-login',
            '<script',
            'base64_decode(',
            'GLOBALS[',
            '_SERVER[',
        ];
    }
}
