<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Models\PhoneBook;
use App\Models\SharedPhoneBook;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GetSharedPhoneBookHandler
{
    public function handle(): Collection
    {
        try {
            $sharedPhoneBookIds = SharedPhoneBook::where('shared_user_id', auth()->id())
                ->pluck('phone_book_id');

            return PhoneBook::with('user')
                ->whereIn('id', $sharedPhoneBookIds)
                ->get()
                ->map(function ($phoneBook) {
                    return [
                        'id' => $phoneBook->id,
                        'name' => $phoneBook->name,
                        'phone_number' => $phoneBook->phone_number,
                        'owner_name' => $phoneBook->user?->name ?? 'Unknown'
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Failed to retrieve shared phone books: ' . $e->getMessage());

            return collect();
        }
    }
}
