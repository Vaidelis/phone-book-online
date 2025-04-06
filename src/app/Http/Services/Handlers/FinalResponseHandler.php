<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers;

class FinalResponseHandler extends Handler
{
    public function handle(array $context): array
    {
        $message = $context['message'] ?? 'Operation completed successfully';
        $statusCode = $context['statusCode'] ?? 200;

        return [
            'success' => true,
            'message' => $message,
            'statusCode' => $statusCode
        ];
    }
}

