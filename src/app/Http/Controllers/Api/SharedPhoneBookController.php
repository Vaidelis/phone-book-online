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

    /**
     * Share a phone book entry
     *
     * @bodyParam user_id int required The ID of the user to share the contact with. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Contact shared successfully"
     * }
     */
    public function share(SharedPhoneBookShareRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->sharedPhoneBookShareHandler->share($id, $validated);

        return new JsonResponse([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['statusCode']);
    }

    /**
     * Unshare a phone book entry
     *
     * @bodyParam user_id int required The ID of the user to unshare the contact with. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Contact unshared successfully"
     * }
     */
    public function unshare(SharedPhoneBookUnshareRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->sharedPhoneBookUnshareHandler->unshare($id, $validated);

        return new JsonResponse([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['statusCode']);
    }
}
