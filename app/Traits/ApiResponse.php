<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Success response
     */
    protected function successResponse($message = 'Success', $data = [], $code = 200)
    {
        return response()->json([
            'status' => $code,
            'error' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error response
     */
    protected function errorResponse($message = 'Error', $code = 400)
    {
        return response()->json([
            'status' => $code,
            'error' => True,
            'message' => $message,
            'data' => null,
        ], $code);
    }
}
