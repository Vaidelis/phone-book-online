<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\Handler;
use Symfony\Component\HttpFoundation\Response;

class UnsharePhoneBookHandler extends Handler
{
    public function handle(array $context): array
    {
        $sharedRecord = $context['sharedRecord'];
        $sharedRecord->delete();

        $context['message'] = 'Phone book unshared successfully';
        $context['statusCode'] = Response::HTTP_OK;

        return parent::handle($context);
    }
}
