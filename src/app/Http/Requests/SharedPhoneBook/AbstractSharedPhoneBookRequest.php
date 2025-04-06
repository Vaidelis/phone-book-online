<?php

namespace App\Http\Requests\SharedPhoneBook;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractSharedPhoneBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        /*$phoneBookId = $this->route('id');
        $phoneBook = PhoneBook::findOrFail($phoneBookId);

        return $phoneBook->user_id === auth()->id();*/
        return true;
    }

    public function rules(): array
    {
        return [
            'shared_user_id' => 'required|exists:users,id',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw new \Illuminate\Validation\ValidationException($validator,
            response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
