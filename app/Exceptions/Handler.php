<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Exception $exception)
    {
        // Handle Session Expired (419)
        if ($exception instanceof TokenMismatchException) {
            return redirect('/login')
                ->with('error', 'Session expired. Please login again.');
        }

        // Handle 403 Forbidden
        if ($exception instanceof HttpException && $exception->getStatusCode() == 403) {
            return redirect('/login')
                ->with('error', 'Unauthorized access.');
        }

        return parent::render($request, $exception);
    }
}