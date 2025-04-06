<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\FinalResponseHandler;
use App\Http\Services\Handlers\PhoneBook\FindPhoneBookHandler;
use App\Http\Services\Handlers\PhoneBook\NotSharedHandler;
use Illuminate\Support\Facades\Log;

class SharedPhoneBookUnshareHandler
{
    public function unshare(int $id, array $validated): array
    {
        try {
            $findPhoneBookHandler = new FindPhoneBookHandler();
            $notSharedHandler = new NotSharedHandler();
            $unsharePhoneBookHandler = new UnsharePhoneBookHandler();
            $finalResponseHandler = new FinalResponseHandler();

            $findPhoneBookHandler
                ->setNext($notSharedHandler)
                ->setNext($unsharePhoneBookHandler)
                ->setNext($finalResponseHandler);

            $context = [
                'id' => $id,
                'validated' => $validated
            ];

            return $findPhoneBookHandler->handle($context);
        } catch (\Exception $e) {
            Log::error('Phone book unsharing failed', [
                'id' => $id,
                'shared_user_id' => $validated['shared_user_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to unshare phone book: ' . $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }
}
