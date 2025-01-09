<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdminRegisteredRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company' => ['required_without:secret_code', 'unique:companies,name'],
            'secret_code' => ['required_without:company', 'exists:companies, secret_code'],
            'password' => ['required', Password::defaults()],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'имя',
            'email' => 'почта',
            'password' => 'пароль',
            'secret_code' => 'секретный код'
        ];
    }
}
