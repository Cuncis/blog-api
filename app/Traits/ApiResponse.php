<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function error(string $message = 'Something went wrong', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
