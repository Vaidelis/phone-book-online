<?php

declare(strict_types=1);

namespace App\Http\Services\DeleteHandlers\SharedPhoneBook;

use App\Http\Services\Handlers\Handler;
use App\Models\SharedPhoneBook;

class DeleteSharedPhoneBookHandler extends Handler
{
    public function handle(array $context): array
    {
        SharedPhoneBook::where('phone_book_id', $context['id'])->delete();
        return parent::handle($context);
    }
}
