<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiTrait
{
    public $perPage = 10;

    /**
     * responseError
     *
     * @param  mixed $message
     * @return object
     */
    public function responseError(string $message = 'Record Not Found!', int $statusCode = Response::HTTP_NOT_FOUND): object
    {
        return response()->json(
            [
                'statusText' => Response::$statusTexts[$statusCode],
                'message' => $message
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * response
     *
     * @param  mixed $message
     * @return object
     */
    public function response($message, int $statusCode = Response::HTTP_OK): object
    {
        return response()->json(
            [
                'statusText' => Response::$statusTexts[$statusCode],
                'message' => $message
            ],
            Response::HTTP_OK
        );
    }

    public function res(int $statusCode = Response::HTTP_OK, $message = null): object
    {
        $statusText = Response::$statusTexts[$statusCode];
        return response()->json(
            [
                'statusText' => $statusText,
                'message' => $message ?? $statusText
            ],
            $statusCode
        );
    }

    public function responseNoContent($message = null): object
    {
        $statusCode = Response::HTTP_NO_CONTENT;
        $statusText = Response::$statusTexts[$statusCode];
        return response()->json(
            [
                'statusText' => $statusText,
                'message' => $message ?? $statusText
            ],
            $statusCode
        );
    }

    public function responseNotFound($message = 'Record Not Found'): object
    {
        $statusCode = Response::HTTP_NOT_FOUND;
        $statusText = Response::$statusTexts[$statusCode];
        return response()->json(
            [
                'statusText' => $statusText,
                'message' => $message ?? $statusText
            ],
            $statusCode
        );
    }

    public function responseNotAccept($message = null): object
    {
        $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        $statusText = Response::$statusTexts[$statusCode];
        return response()->json(
            [
                'statusText' => $statusText,
                'message' => $message ?? $statusText
            ],
            $statusCode
        );
    }
}
