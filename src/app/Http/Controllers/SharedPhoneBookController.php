<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\Handlers\SharedPhoneBook\GetSharedPhoneBookHandler;
use Inertia\Response;

class SharedPhoneBookController extends Controller
{
    public function __construct(private readonly GetSharedPhoneBookHandler $getSharedPhoneBookHandler)
    {
    }

    public function index(): Response
    {
        $phoneBooks = $this->getSharedPhoneBookHandler->handle();

        return inertia('sharedPhoneBooks/Index', ['phoneBooks' => $phoneBooks]);
    }
}
