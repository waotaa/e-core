<?php

namespace Vng\EvaCore\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LogAsyncPayloadSize
{
    public function handle(Request $request, Closure $next)
    {
        // Controleer of het request een route heeft
        $route = $request->route();

        // Check if the route is marked as asynchronous
        if ($route && $route->getAction('async') === true) {
            $size = strlen($request->getContent());

            Log::channel('async_payload_sizes')->info('Async payload size', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'size' => $size,
                'exceeds_limit' => $size > 262144 // 256KB limiet voor async requests
            ]);
        }

        return $next($request);
    }
}