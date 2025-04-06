<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\Handlers\PhoneBook\PhoneBookSharingHandler;
use App\Models\PhoneBook;
use App\Models\User;
use Inertia\Response;

class PhoneBookController extends Controller
{
    public function __construct(private readonly PhoneBookSharingHandler $phoneBookSharingHandler)
    {
    }

    public function index(): Response
    {
        $phoneBooks = PhoneBook::where('user_id', auth()->id())->with('sharedPhoneBooks')->get();
        $users = User::where('id', '!=', auth()->id())->get();
        $phoneBookSharing = $this->phoneBookSharingHandler->handle($phoneBooks);

        return inertia('phoneBook/Index', [
            'phoneBooks' => $phoneBooks,
            'users' => $users,
            'phoneBookSharing' => $phoneBookSharing
        ]);
    }

    public function create(): Response
    {
        return inertia('phoneBook/Create');
    }

    public function edit(int $id): Response
    {
        $phoneBook = PhoneBook::findOrFail($id);

        return inertia('phoneBook/Edit', ['phoneBook' => $phoneBook]);
    }
}
