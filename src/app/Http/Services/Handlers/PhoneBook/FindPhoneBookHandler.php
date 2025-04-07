<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\PhoneBook;

use App\Http\Services\Handlers\Handler;
use App\Models\PhoneBook;
use Symfony\Component\HttpFoundation\Response;

class FindPhoneBookHandler extends Handler
{
    public function handle(array $context): array
    {
        $phoneBook = PhoneBook::find($context['id']);

        if (!$phoneBook) {
            return [
                'success' => false,
                'message' => 'Phone book not found',
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }

        $context['phoneBook'] = $phoneBook;
        return parent::handle($context);
    }
}


