<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ForceJsonResponseTest extends TestCase
{
    /**
     * Test that the middleware returns a JSON response with the correct message and status code.
     *
     * @dataProvider statusProvider
     */
    public function testMiddlewareReturnsJsonResponseWithMessage($statusCode, $expectedMessage)
    {
        $middleware = new ForceJsonResponse();

        // Mock the request and response
        $request = Request::create('/');
        $response = new Response('', $statusCode);

        // Create a closure to simulate passing through middleware
        $next = function ($request) use ($response) {
            return $response;
        };

        // Handle the request through the middleware
        $jsonResponse = $middleware->handle($request, $next);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $jsonResponse);

        // Assert the JSON response content and status code
        $this->assertEquals($statusCode, $jsonResponse->status());
        $this->assertEquals(['message' => $expectedMessage], $jsonResponse->getData(true));
    }

    /**
     * Data provider for testMiddlewareReturnsJsonResponseWithMessage.
     *
     * @return array
     */
    public static function statusProvider()
    {
        return [
            [200, 'success'],
            [201, 'Resource created successfully'],
            [204, 'No content'],
            [400, 'Bad request'],
            [401, 'Unauthorized'],
            [403, 'Forbidden'],
            [404, 'Resource not found'],
            [500, 'Internal server error'],
        ];
    }
}
