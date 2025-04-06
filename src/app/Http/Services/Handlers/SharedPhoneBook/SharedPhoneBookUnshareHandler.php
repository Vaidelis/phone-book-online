<?php

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Models\PhoneBook;
use App\Models\SharedPhoneBook;
use Illuminate\Support\Facades\Log;

class SharedPhoneBookUnshareHandler
{
    public function unshare(int $id, array $validated): array
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

            $sharedRecord = SharedPhoneBook::where('phone_book_id', $id)
                ->where('shared_user_id', $validated['shared_user_id'])
                ->first();

            if (!$sharedRecord) {
                return [
                    'success' => false,
                    'message' => 'This phone book is not shared with the specified user',
                    'statusCode' => 404
                ];
            }

            $sharedRecord->delete();

            return [
                'success' => true,
                'message' => 'Phone book unshared successfully',
                'statusCode' => 200
            ];
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
