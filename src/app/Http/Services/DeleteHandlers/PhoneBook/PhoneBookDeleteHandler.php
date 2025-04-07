<?php

declare(strict_types=1);

namespace App\Http\Services\DeleteHandlers\PhoneBook;

use App\Http\Services\DeleteHandlers\SharedPhoneBook\DeleteSharedPhoneBookHandler;
use App\Http\Services\Handlers\FinalResponseHandler;
use App\Http\Services\Handlers\PhoneBook\FindPhoneBookHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhoneBookDeleteHandler
{
    public function __construct(
        private readonly FindPhoneBookHandler $findPhoneBookHandler,
        private readonly DeleteSharedPhoneBookHandler $deleteSharedPhoneBookHandler,
        private readonly DeletePhoneBookHandler $deletePhoneBookHandler,
        private readonly FinalResponseHandler $finalResponseHandler
    ) {
        $this->findPhoneBookHandler
            ->setNext($this->deleteSharedPhoneBookHandler)
            ->setNext($this->deletePhoneBookHandler)
            ->setNext($this->finalResponseHandler);
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();

            $result = $this->findPhoneBookHandler->handle(['id' => $id]);

            if (isset($result['success']) && $result['success']) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Phone book deletion failed', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to delete phone book: ' . $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }
}

