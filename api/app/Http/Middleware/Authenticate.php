<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new HttpException(403, 'Invalid token');
    }

    /**
     * Determine if the request is sending JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function expectsJson(Request $request)
    {
        return $request->expectsJson() || $request->isJson();
    }
}

