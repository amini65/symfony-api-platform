<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseFormatter
{
    public function success(array $data = [], string $message = 'Operation completed successfully', int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error(string $message, int $code = 400, array $errors = []): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
