<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmartFilterCompaniesRequest extends FormRequest
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
            'companies' => ['nullable', 'array'],
            'companies.*' => ['integer', 'exists:companies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cities.*.exists' => 'Таких городов не существует!',
            'companies.*.exists' => 'Таких компаний не существует!',
        ];
    }

    public function attributes(): array
    {
        return ['cities' => 'Города', 'companies' => 'Компании'];
    }
}
