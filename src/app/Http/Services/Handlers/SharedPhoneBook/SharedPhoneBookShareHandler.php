<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Models\PhoneBook;
use Illuminate\Support\Facades\Log;

class SharedPhoneBookShareHandler
{
    public function share(int $id, array $validated): array
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

            $alreadyShared = $phoneBook->sharedPhoneBooks()
                ->where('shared_user_id', $validated['shared_user_id'])
                ->exists();

            if ($alreadyShared) {
                return [
                    'success' => true,
                    'message' => 'Phone book is already shared with this user',
                    'statusCode' => 200
                ];
            }

            $phoneBook->sharedPhoneBooks()->create([
                'shared_user_id' => $validated['shared_user_id']
            ]);

            return [
                'success' => true,
                'message' => 'Phone book shared successfully',
                'statusCode' => 200
            ];
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
