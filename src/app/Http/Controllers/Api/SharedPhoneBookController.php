<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\SharedPhoneBook\SharedPhoneBookShareRequest;
use App\Http\Requests\SharedPhoneBook\SharedPhoneBookUnshareRequest;
use App\Http\Services\Handlers\SharedPhoneBook\SharedPhoneBookShareHandler;
use App\Http\Services\Handlers\SharedPhoneBook\SharedPhoneBookUnshareHandler;
use Illuminate\Http\JsonResponse;

class SharedPhoneBookController
{
    public function __construct(
        private readonly SharedPhoneBookShareHandler $sharedPhoneBookShareHandler,
        private readonly SharedPhoneBookUnshareHandler $sharedPhoneBookUnshareHandler
    ) {
    }

    public function share(SharedPhoneBookShareRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->sharedPhoneBookShareHandler->share($id, $validated);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['statusCode']);
    }

    public function unshare(SharedPhoneBookUnshareRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->sharedPhoneBookUnshareHandler->unshare($id, $validated);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['statusCode']);
    }
}
