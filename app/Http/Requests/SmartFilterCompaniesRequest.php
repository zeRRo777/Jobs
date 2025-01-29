<?php

namespace App\Http\Requests;

use App\Rules\Exists;
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
            'cities' => ['nullable', 'array', new Exists('cities')],
            'cities.*' => ['integer'],
            'companies' => ['nullable', 'array', new Exists('companies')],
            'companies.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'cities.*.integer' => 'ID города должен быть целым числом.',
            'companies.*.integer' => 'ID компании должен быть целым числом.',
        ];
    }

    public function attributes(): array
    {
        return ['cities' => 'Города', 'companies' => 'Компании'];
    }
}
