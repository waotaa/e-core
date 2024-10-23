<?php

namespace Vng\EvaCore\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LogAsyncPayloadSize
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the route is marked as asynchronous
        if ($request->route()->getAction('async') === true) {
            $size = strlen($request->getContent());

            Log::channel('async_payload_sizes')->info('Async payload size', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'size' => $size,
                'exceeds_limit' => $size > 262144 // 256KB limit for async calls
            ]);
        }

        return $next($request);
    }
}