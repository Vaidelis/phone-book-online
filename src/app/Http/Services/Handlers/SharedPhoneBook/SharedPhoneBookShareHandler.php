<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\FinalResponseHandler;
use App\Http\Services\Handlers\PhoneBook\AlreadySharedHandler;
use App\Http\Services\Handlers\PhoneBook\FindPhoneBookHandler;
use Illuminate\Support\Facades\Log;

class SharedPhoneBookShareHandler
{
    public function share(int $id, array $validated): array
    {
        try {
            $findPhoneBookHandler = new FindPhoneBookHandler();
            $alreadySharedHandler = new AlreadySharedHandler();
            $sharePhoneBookHandler = new SharePhoneBookHandler();
            $finalResponseHandler = new FinalResponseHandler();

            $findPhoneBookHandler
                ->setNext($alreadySharedHandler)
                ->setNext($sharePhoneBookHandler)
                ->setNext($finalResponseHandler);

            $context = [
                'id' => $id,
                'validated' => $validated
            ];

            return $findPhoneBookHandler->handle($context);
        } catch (\Exception $e) {
            Log::error('Phone book sharing failed', [
                'id' => $id,
                'shared_user_id' => $validated['shared_user_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to share phone book: ' . $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }
}
