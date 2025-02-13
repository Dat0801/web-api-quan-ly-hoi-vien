<?php
namespace App\Traits;

trait ApiResponse
{
    protected function success($data, string $message = '', int $status = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function error(string $message, int $status = 400)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
        ], $status);
    }
}

