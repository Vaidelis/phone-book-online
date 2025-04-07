<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\PhoneBook;

use App\Http\Services\Handlers\Handler;
use Symfony\Component\HttpFoundation\Response;

class NotSharedHandler extends Handler
{
    public function handle(array $context): array
    {
        $phoneBook = $context['phoneBook'];
        $validated = $context['validated'];

        $sharedRecord = $phoneBook->sharedPhoneBooks()
            ->where('shared_user_id', $validated['shared_user_id'])
            ->first();

        $context['sharedRecord'] = $sharedRecord;

        if (!$sharedRecord) {
            $context['message'] = 'This phone book is not shared with the specified use';
            $context['statusCode'] = Response::HTTP_NOT_FOUND;
            return parent::handle($context);
        }

        return parent::handle($context);
    }
}
