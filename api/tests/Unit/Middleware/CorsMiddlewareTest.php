<?php

namespace Tests\Unit\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Closure;
use Tests\TestCase;

class CCorsMiddlewareTest extends TestCase
{
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        // Instancia del middleware
        $this->middleware = app(\App\Http\Middleware\CorsMiddleware::class);
    }

    public function testHandleAddsCorsHeadersToResponse()
    {
        // Simular una solicitud normal
        $request = Request::create('/');

        // Crear un Closure que representa el siguiente middleware en la cadena
        $next = function ($request) {
            return response('OK');
        };

        // Aplicar el middleware
        $response = $this->middleware->handle($request, $next);

        // Verificar que los encabezados CORS estén presentes en la respuesta
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, POST, PATCH, PUT, DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization, x-api-key', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testHandleForOptionsRequestReturns200WithCorsHeaders()
    {
        // Simular una solicitud de preflight (OPTIONS)
        $request = Request::create('/', 'OPTIONS');

        // Crear un Closure que representa el siguiente middleware en la cadena
        $next = function ($request) {
            return response('OK');
        };

        // Aplicar el middleware
        $response = $this->middleware->handle($request, $next);

        // Verificar que la respuesta tiene un código 200 y los encabezados CORS
        $this->assertEquals(200, $response->status());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, POST, PATCH, PUT, DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization, x-api-key', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testHandleForNonOptionsRequestPassesThrough()
    {
        // Simular una solicitud normal
        $request = Request::create('/');

        // Crear un Closure que representa el siguiente middleware en la cadena
        $next = function ($request) {
            return response('OK');
        };

        // Aplicar el middleware
        $response = $this->middleware->handle($request, $next);

        // Verificar que el middleware ha añadido los encabezados CORS y ha pasado la respuesta
        $this->assertEquals('OK', $response->getContent());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, POST, PATCH, PUT, DELETE', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization, x-api-key', $response->headers->get('Access-Control-Allow-Headers'));
    }
}