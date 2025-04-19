<?php
namespace App\Traits;

trait ApiResponse
{
    protected function success($data = null, string $message = 'Success', int $status = 200)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            if (method_exists($data, 'items')) {
                $response['data'] = $data->items();
                $response['meta'] = [
                    'pagination' => [
                        'total' => $data->total(),
                        'count' => $data->count(),
                        'per_page' => $data->perPage(),
                        'current_page' => $data->currentPage(),
                        'total_pages' => $data->lastPage(),
                        'links' => [
                            'first' => $data->url(1),
                            'last' => $data->url($data->lastPage()),
                            'prev' => $data->previousPageUrl(),
                            'next' => $data->nextPageUrl(),
                        ],
                    ],
                ];
            } else {
                $response['data'] = $data;
            }
        }

        return response()->json($response, $status);
    }

    protected function error(string $message, int $status = 400, $errors = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}

