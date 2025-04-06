<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\FinalResponseHandler;
use App\Http\Services\Handlers\PhoneBook\AlreadySharedHandler;
use App\Http\Services\Handlers\PhoneBook\FindPhoneBookHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SharedPhoneBookShareHandler
{
    public function __construct(
        private readonly FindPhoneBookHandler $findPhoneBookHandler,
        private readonly AlreadySharedHandler $alreadySharedHandler,
        private readonly SharePhoneBookHandler $sharePhoneBookHandler,
        private readonly FinalResponseHandler $finalResponseHandler
    ) {
        $this->findPhoneBookHandler
            ->setNext($this->alreadySharedHandler)
            ->setNext($this->sharePhoneBookHandler)
            ->setNext($this->finalResponseHandler);
    }

    public function share(int $id, array $validated): array
    {
        try {
            $context = [
                'id' => $id,
                'validated' => $validated
            ];

            return $this->findPhoneBookHandler->handle($context);
        } catch (\Exception $e) {
            Log::error('Phone book sharing failed', [
                'id' => $id,
                'shared_user_id' => $validated['shared_user_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to share phone book: ' . $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
    }
}
