<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedPhoneBook extends Model
{
    protected $table = 'shared_phone_book';
    protected $fillable = [
        'phone_book_id',
        'shared_user_id',
    ];

    public function sharedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_user_id');
    }

    public function phoneBook(): BelongsTo
    {
        return $this->belongsTo(PhoneBook::class, 'phone_book_id');
    }
}
