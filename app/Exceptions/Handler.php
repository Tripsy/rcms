<?php

namespace App\Exceptions;

use BadMethodCallException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * Overwrite the base class method to change of
     *      ModelNotFoundException to NotFoundHttpException
     *      RecordsNotFoundException to NotFoundHttpException
     */
    protected function prepareException(Throwable $e): Throwable
    {
        if ($e instanceof ModelNotFoundException) {
            return $e;
        }

        if ($e instanceof RecordsNotFoundException) {
            return $e;
        }

        return parent::prepareException($e);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //        $this->stopIgnoring(ModelNotFoundException::class); //this does update $this->internalDontReport but the exception is still not reported

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('test')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, __('message.exception.method_not_supported', [
                    'method' => $request->method(),
                ]), $e->getStatusCode());
            }
        });

        $this->renderable(function (BadMethodCallException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('test')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });

        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('mysql')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, __('message.exception.model_not_found'), Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->standardJsonResponseError($request, $e, __('message.exception.not_found'), Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (QueryException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('mysql')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, __('message.exception.query'));
            }
        });

        $this->renderable(function (PDOException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('mysql')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, __('message.exception.pdo'));
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->standardJsonResponseError($request, $e, $e->getMessage(), Response::HTTP_FORBIDDEN);
            }
        });

        $this->renderable(function (ActionException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('test')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, $e->getMessage());
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->standardJsonResponseError($request, $e, $e->getMessage());
            }
        });

        $this->renderable(function (ControllerException $e, Request $request) {
            if ($request->expectsJson()) {
                Log::channel('test')->error($e->getMessage());

                return $this->standardJsonResponseError($request, $e, $e->getMessage());
            }
        });
    }

    /**
     * Return common response for requests which expects JSON
     */
    private function standardJsonResponseError(
        Request $request,
        Exception $e,
        string $message,
        int $fallBackCode = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        $responseData = [
            'success' => false,
            'message' => $message,
            'errors' => [],
            'request' => $request->all(),
        ];

        if ($e instanceof ValidationException) {
            $responseData['errors'] = $e->validator->errors();
        }

        if (app()->environment() == 'local') {
            $responseData['debug'] = [
                'message' => $e->getMessage(),
                'exception' => $e::class,
                //                'file' => $e->getFile(),
                //                'line' => $e->getLine(),
            ];
        }

        return response()->json($responseData, returnValidHttpResponseCode((int) $e->getCode(), $fallBackCode));
    }

    /**
     * Convert an authentication exception into a response.
     *
     *
     * @param  Request  $request
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|RedirectResponse
    {
        return $this->shouldReturnJson($request, $exception)
//                    ? response()->json(['message' => $exception->getMessage()], 401)
                    ? $this->standardJsonResponseError($request, $exception, __('message.exception.unauthenticated'))
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
