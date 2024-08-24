<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Continuar con la solicitud
        $response = $next($request);

        // Obtener el código de estado de la respuesta
        $status = $response->getStatusCode();

        // Definir mensajes personalizados para cada código de estado
        $messages = [
            200 => 'success',
            201 => 'Resource created successfully',
            204 => 'No content',
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Resource not found',
            500 => 'Internal server error',
            // Añade más mensajes según sea necesario
        ];

        // Determinar el mensaje a retornar
        $message = $messages[$status] ?? 'An error occurred';

        // Si la respuesta ya es un JsonResponse, solo agregamos el mensaje
        if ($response instanceof JsonResponse) {
            return $response->setData($response->getData());
        }

        // Retornar la respuesta en formato JSON con el código de estado y el mensaje personalizado
        return response()->json(['message' => $message], $status);
    }
}