<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
//        return parent::render($request, $exception);
        $rendered = parent::render($request, $exception);

        if ($exception instanceof ValidationException) {
            $json = [
                'error' => $exception->validator->errors(),
                'status_code' => $rendered->getStatusCode()
            ];
        } elseif ($exception instanceof AuthorizationException) {
            $json = [
                'error' => 'You are not allowed to do this action.',
                'status_code' => 403
            ];
        } elseif ($exception instanceof UnauthorizedHttpException) {
            // detect previous instance
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                $json = [
                    'error' => 'TOKEN_EXPIRED',
                    'status_code' => $exception->getStatusCode()
                ];
            } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                $json = [
                    'error' => 'TOKEN_INVALID',
                    'status_code' => $exception->getStatusCode()
                ];
            } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                $json = [
                    'error' => 'TOKEN_BLACKLISTED',
                    'status_code' => $exception->getStatusCode()
                ];
            } else {
                $json = [
                    'error' => 'UNAUTHENTICATED',
                    'status_code' => $exception->getStatusCode()
                ];
            }
        } else {
            // Default to vague error to avoid revealing sensitive information
            $json = [
                'error' => (app()->environment() !== 'production')
                    ? $exception->getMessage()
                    : 'An error has occurred.',
                'status_code' => $exception->getCode()
            ];
        }

        return response()->json($json, $rendered->getStatusCode());
    }
}
