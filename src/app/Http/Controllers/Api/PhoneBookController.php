<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneBook\PhoneBookStoreRequest;
use App\Http\Requests\PhoneBook\PhoneBookUpdateRequest;
use App\Http\Services\DeleteHandlers\PhoneBook\PhoneBookDeleteHandler;
use App\Models\PhoneBook;
use Illuminate\Http\JsonResponse;

class PhoneBookController extends Controller
{
    public function __construct(private readonly PhoneBookDeleteHandler $phoneBookDeleteHandler)
    {
    }

    /**
     * Store a new phone book entry
     *
     * @bodyParam name string required The name of the contact. Example: John Doe
     * @bodyParam phone_number string required The phone number of the contact. Example: +1234567890
     * @bodyParam user_id int required The ID of the user creating the contact. Example: 1
     *
     * @response {
     *  "id": 1,
     *  "name": "John Doe",
     *  "phone_number": "+1234567890",
     *  "created_at": "2023-01-01T00:00:00.000000Z",
     *  "updated_at": "2023-01-01T00:00:00.000000Z"
     * }
     */
    public function store(PhoneBookStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $phoneBook = PhoneBook::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'data' => $phoneBook
        ]);
    }

    /**
     * Update an existing phone book entry
     *
     * @bodyParam name string required The name of the contact. Example: John Doe
     * @bodyParam phone_number string required The phone number of the contact. Example: +1234567890
     *
     * @response {
     *  "success": true,
     *  "message": "Contact updated successfully",
     *  "data": {
     *      "id": 1,
     *      "name": "John Doe",
     *      "phone_number": "+1234567890",
     *      "created_at": "2023-01-01T00:00:00.000000Z",
     *      "updated_at": "2023-01-01T00:00:00.000000Z"
     *  }
     * }
     */
    public function update(PhoneBookUpdateRequest $request, int $id): JsonResponse
    {
        $phoneBook = PhoneBook::findOrFail($id);
        $validated = $request->validated();
        $phoneBook->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'data' => $phoneBook
        ]);
    }

    /**
     * Delete a phone book entry
     *
     * @response {
     *  "success": true,
     *  "message": "Contact deleted successfully"
     * }
     */
    public function delete(int $id): JsonResponse
    {
        $result = $this->phoneBookDeleteHandler->delete($id);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['statusCode']);
    }
}
