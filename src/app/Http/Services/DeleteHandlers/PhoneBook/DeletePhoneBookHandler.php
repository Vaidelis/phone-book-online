<?php

declare(strict_types=1);

namespace App\Http\Services\DeleteHandlers\PhoneBook;

use App\Http\Services\Handlers\Handler;

class DeletePhoneBookHandler extends Handler
{
    public function handle(array $context): array
    {
        $context['phoneBook']->delete();
        return parent::handle($context);
    }
}

