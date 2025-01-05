<?php

namespace App\Http\Requests;

use App\Rules\NotEqual;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults(), new NotEqual('current_password')],
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
        ];
    }
}
