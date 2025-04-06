<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\PhoneBook;

use App\Models\SharedPhoneBook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PhoneBookSharingHandler
{
    public function handle(Collection $phoneBooks): array
    {
        try {
            $phoneBookSharing = [];

            foreach ($phoneBooks as $phoneBook) {
                $phoneBookSharing[$phoneBook->id] = SharedPhoneBook::where('phone_book_id', $phoneBook->id)
                    ->pluck('shared_user_id')
                    ->toArray();
            }

            return $phoneBookSharing;
        } catch (\Exception $e) {
            Log::error('Failed to fetch phone book sharing data: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id() ?? 'unknown',
            ]);

            return [];
        }
    }
}
