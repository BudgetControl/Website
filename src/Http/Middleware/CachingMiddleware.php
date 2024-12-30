<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Http\Middleware;

use Exception;
use Illuminate\Support\Facades\Cache;

class CachingMiddleware
{
    const CACHE_TTL = 86400; // 24 hours in seconds
    const CACHE_KEY = 'BLOG::';
    /**
     * Example middleware invokable class
     *
     */
    public function __invoke(\GuzzleHttp\Psr7\ServerRequest $request, $handler)
    {
        $requestUri = $request->getUri()->getPath();
        $cacheKey = self::CACHE_KEY . $requestUri;

        $existCache = Cache::has($cacheKey);
        if(true === $existCache) {
            $requestBody = Cache::get($cacheKey);
            $request = $request->withBody($requestBody);
            return $handler->handle($request);
            
        }

        Cache::put($cacheKey, $request->getBody(), env('APP_CACHE_TTL',self::CACHE_TTL));
        return $handler->handle($request);
    }
}