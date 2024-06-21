<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // Add exceptions that you don't want to report
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     * @throws Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $exception
     * @return JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found'
            ], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated'
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => $exception->validator->errors()
            ], 422);
        }

        return response()->json([
            'error' => 'Server Error'
        ], 500);
    }
}
