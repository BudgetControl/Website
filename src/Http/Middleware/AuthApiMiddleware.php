<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Http\Middleware;

use Closure;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthApiMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $handler)
    {
        $token = $request->getHeader('Authorization')[0];

        if (!$token || $token !== 'Bearer ' . env('API_TOKEN')) {
            throw new Exception('Unauthorized', 401);
        }

        return $handler->handle($request);
    }
}