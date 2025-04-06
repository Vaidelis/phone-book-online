<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\FinalResponseHandler;
use App\Http\Services\Handlers\PhoneBook\FindPhoneBookHandler;
use App\Http\Services\Handlers\PhoneBook\NotSharedHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SharedPhoneBookUnshareHandler
{
    public function __construct(
        private readonly FindPhoneBookHandler $findPhoneBookHandler,
        private readonly NotSharedHandler $notSharedHandler,
        private readonly UnsharePhoneBookHandler $unsharePhoneBookHandler,
        private readonly FinalResponseHandler $finalResponseHandler
    )
    {
        $this->findPhoneBookHandler
            ->setNext($this->notSharedHandler)
            ->setNext($this->unsharePhoneBookHandler)
            ->setNext($this->finalResponseHandler);
    }

    public function unshare(int $id, array $validated): array
    {
        try {
            $context = [
                'id' => $id,
                'validated' => $validated
            ];

            return $this->findPhoneBookHandler->handle($context);
        } catch (\Exception $e) {
            Log::error('Phone book unsharing failed', [
                'id' => $id,
                'shared_user_id' => $validated['shared_user_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to unshare phone book: ' . $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
    }
}
