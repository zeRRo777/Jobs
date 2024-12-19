<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmartFilterVacanciesRequest extends FormRequest
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
            'professions.*' => ['string', 'exists:vacancies,title'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'exists:tags,id'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['integer', 'exists:companies,id'],
            'salary_start' => ['nullable', 'integer', 'min:0'],
            'salary_end' => ['nullable', 'integer', 'min:0', 'gte:salary_start'],
            'search' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cities.*.exists' => 'Таких городов не существует!',
            'professions.*.exists' => 'Таких профессий не существует!',
            'tags.*.exists' => 'Таких тегов не существует!',
            'companies.*.exists' => 'Таких компаний не существует!',
            'salary_end.gte' => 'Зарплата от должна быть больше зарплаты до !'
        ];
    }
}
