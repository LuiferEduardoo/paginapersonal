<?php

namespace Tests\Unit\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        // Instancia del middleware
        $this->middleware = app(\App\Http\Middleware\Authenticate::class);
    }

    public function testUnauthenticatedThrowsHttpException()
    {
        // Simular una solicitud y un array de guardias vacíos
        $request = Request::create('/');
        $guards = [];

        // Asegurarse de que el método 'unauthenticated' lanza una excepción HttpException
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Invalid token');

        // Llamar al método protegido 'unauthenticated' usando reflection
        $reflection = new \ReflectionClass($this->middleware);
        $method = $reflection->getMethod('unauthenticated');
        $method->setAccessible(true);
        $method->invoke($this->middleware, $request, $guards);
    }

    public function testExpectsJsonReturnsTrueForJsonRequest()
    {
        // Simular una solicitud que espera JSON
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);

        // Verificar que 'expectsJson' devuelve true
        $this->assertTrue($this->invokeProtectedMethod($this->middleware, 'expectsJson', $request));
    }

    public function testExpectsJsonReturnsTrueForJsonRequestWithIsJson()
    {
        // Simular una solicitud donde 'isJson' devuelve true
        $request = Request::create('/', 'GET');
        $request->headers->set('Content-Type', 'application/json'); // Configurar el request para simular JSON

        // Verificar que 'expectsJson' devuelve true
        $this->assertTrue($this->invokeProtectedMethod($this->middleware, 'expectsJson', $request));
    }

    public function testExpectsJsonReturnsFalseForNonJsonRequest()
    {
        // Simular una solicitud que no espera JSON
        $request = Request::create('/', 'GET');

        // Verificar que 'expectsJson' devuelve false
        $this->assertFalse($this->invokeProtectedMethod($this->middleware, 'expectsJson', $request));
    }

    protected function invokeProtectedMethod($object, $methodName, ...$parameters)
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invoke($object, ...$parameters);
    }
}