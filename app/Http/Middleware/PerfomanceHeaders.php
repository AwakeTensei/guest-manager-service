<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerfomanceHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $time = (microtime(true) - $startTime) * 1000; // в миллисекундах
        $memory = (memory_get_usage() - $startMemory) / 1024; // в Кб

        return $response->header('X-Debug-Time', round($time, 2))
                       ->header('X-Debug-Memory', round($memory, 2));
    }
}