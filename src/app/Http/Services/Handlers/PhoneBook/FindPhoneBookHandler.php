<?php

namespace App\Http\Services\Handlers\PhoneBook;

use App\Http\Services\Handlers\Handler;
use App\Models\PhoneBook;

class FindPhoneBookHandler extends Handler
{
    public function handle(array $context): array
    {
        $phoneBook = PhoneBook::find($context['id']);

        if (!$phoneBook) {
            return [
                'success' => false,
                'message' => 'Phone book not found',
                'statusCode' => 404
            ];
        }

        $context['phoneBook'] = $phoneBook;
        return parent::handle($context);
    }
}


