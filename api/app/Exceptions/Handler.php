<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method not allowed.',
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return parent::render($request, $exception);
    }
}
