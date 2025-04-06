<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\Handler;

class SharePhoneBookHandler extends Handler
{
    public function handle(array $context): array
    {
        $phoneBook = $context['phoneBook'];
        $validated = $context['validated'];

        $phoneBook->sharedPhoneBooks()->create([
            'shared_user_id' => $validated['shared_user_id']
        ]);

        $context['message'] = 'Phone book shared successfully';
        $context['statusCode'] = 200;

        return parent::handle($context);
    }
}

