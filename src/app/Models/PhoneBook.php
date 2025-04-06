<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhoneBook extends Model
{
    protected $table = 'phone_book';
    protected $fillable = [
        'name',
        'phone_number',
        'user_id',
    ];

    public function sharedPhoneBooks(): HasMany
    {
        return $this->hasMany(SharedPhoneBook::class, 'phone_book_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
