<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmartFilterUsersRequest extends FormRequest
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
            'cities' => ['nullable', 'array'],
            'cities.*' => ['integer', 'exists:cities,id'],
            'professions' => ['nullable', 'array'],
            'professions.*' => ['string', 'exists:users,profession'],
        ];
    }

    public function messages(): array
    {
        return [
            'cities.*.exists' => 'Таких городов не существует!',
            'professions.*.exists' => 'Таких профессий не существует!',
        ];
    }

    public function attributes(): array
    {
        return [
            'cities' => 'Города',
            'professions' => 'Профессии',
        ];
    }
}