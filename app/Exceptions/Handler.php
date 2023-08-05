<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
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
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|RedirectResponse|\Illuminate\Http\Response|Response|void
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        switch (true) {
            case $e instanceof JobException:
            case $e instanceof QueryException:
            case $e instanceof PDOException:
                if ($request->wantsJson()) {
                    return app(JsonHandler::class)->render($request, $e);
                }
                break;
            default:
                break;
        }

        parent::render($request, $e);
    }
}
