<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data, string $message = null, int $code)
    {
        return response()->json(
            [
            'status' => 'Success',
            'message' => $message,
            'data' => $data
            ],
            $code
        );
    }

    protected function error($data = null, string $message = null, int $code)
    {
        return response()->json(
            [
            'status' => 'Error',
            'message' => $message,
            'data' => $data
            ],
            $code
        );
    }
}
