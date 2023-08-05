<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDOException;
use Symfony\Component\HttpFoundation\Response;

class JsonHandler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (QueryException $e, Request $request) {
            Log::channel('mysql')->error($e->getMessage());

            return $this->standardJsonResponseError($request, $e, __('message.exception.query'));
        });

        $this->renderable(function (PDOException $e, Request $request) {
            Log::channel('mysql')->error($e->getMessage());

            return $this->standardJsonResponseError($request, $e, __('message.exception.pdo'));
        });

        $this->renderable(function (JobException $e, Request $request) {
            Log::channel('test')->error($e->getMessage());

            return $this->standardJsonResponseError($request, $e, $e->getMessage());
        });
    }

    /**
     * Return common response for requests which expects JSON
     *
     * @param Request $request
     * @param Exception $e
     * @param string $message
     * @return JsonResponse
     */
    private function standardJsonResponseError(Request $request, Exception $e, string $message): JsonResponse
    {
        $responseData = [
            'success' => false,
            'message' => $message,
            'request' => $request->all()
        ];

        if (app()->environment() == 'local') {
            $responseData['debug'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        return response()->json($responseData, returnValidHttpResponseCode($e->getCode(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
