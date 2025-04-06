<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\PhoneBook;

use App\Http\Services\Handlers\Handler;

class AlreadySharedHandler extends Handler
{
    public function handle(array $context): array
    {
        $phoneBook = $context['phoneBook'];
        $validated = $context['validated'];

        $alreadyShared = $phoneBook->sharedPhoneBooks()
            ->where('shared_user_id', $validated['shared_user_id'])
            ->exists();

        if ($alreadyShared) {
            $context['message'] = 'Phone book is already shared with this user';
            $context['statusCode'] = 200;
            return parent::handle($context);
        }

        return parent::handle($context);
    }
}

