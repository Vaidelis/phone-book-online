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
    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();

            $findPhoneBookHandler = new FindPhoneBookHandler();
            $deleteSharedPhoneBookHandler = new DeleteSharedPhoneBookHandler();
            $deletePhoneBookHandler = new DeletePhoneBookHandler();
            $finalResponseHandler = new FinalResponseHandler();

            $findPhoneBookHandler
                ->setNext($deleteSharedPhoneBookHandler)
                ->setNext($deletePhoneBookHandler)
                ->setNext($finalResponseHandler);

            $result = $findPhoneBookHandler->handle(['id' => $id]);

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

