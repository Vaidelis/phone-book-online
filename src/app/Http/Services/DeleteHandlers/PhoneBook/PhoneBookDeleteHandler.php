<?php

declare(strict_types=1);

namespace App\Http\Services\DeleteHandlers\PhoneBook;

use App\Models\PhoneBook;
use App\Models\SharedPhoneBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhoneBookDeleteHandler
{
    public function delete(int $id): array
    {
        try {
            $phoneBook = PhoneBook::find($id);

            if (!$phoneBook) {
                return [
                    'success' => false,
                    'message' => 'Phone book not found',
                    'statusCode' => 404
                ];
            }

            DB::beginTransaction();
            try {
                SharedPhoneBook::where('phone_book_id', $id)->delete();
                $phoneBook->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            return [
                'success' => true,
                'message' => 'Phone book deleted successfully',
                'statusCode' => 200
            ];
        } catch (\Exception $e) {
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
