<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\Dashboard;

use App\Models\PhoneBook;
use App\Models\SharedPhoneBook;
use Illuminate\Support\Facades\Log;

class GetDashboardStatsHandler
{
    public function handle(): array
    {
        try {
            $userId = auth()->id();

            if (!$userId) {
                return $this->getDefaultStats();
            }

            $totalPhoneBooks = PhoneBook::where('user_id', $userId)->count();
            $sharedByUser = PhoneBook::where('user_id', $userId)
                ->whereHas('sharedPhoneBooks')
                ->count();
            $sharedWithUser = SharedPhoneBook::where('shared_user_id', $userId)->count();

            return [
                'total' => $totalPhoneBooks,
                'sharedByUser' => $sharedByUser,
                'sharedWithUser' => $sharedWithUser,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch dashboard stats: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id() ?? 'unknown',
            ]);

            return $this->getDefaultStats();
        }
    }

    private function getDefaultStats(): array
    {
        return [
            'total' => 0,
            'sharedByUser' => 0,
            'sharedWithUser' => 0,
        ];
    }
}
