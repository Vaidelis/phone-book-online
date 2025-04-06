<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers;

use Symfony\Component\HttpFoundation\Response;

class FinalResponseHandler extends Handler
{
    public function handle(array $context): array
    {
        $message = $context['message'] ?? 'Operation completed successfully';
        $statusCode = $context['statusCode'] ?? Response::HTTP_OK;

        return [
            'success' => true,
            'message' => $message,
            'statusCode' => $statusCode
        ];
    }
}

