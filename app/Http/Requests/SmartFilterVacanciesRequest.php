<?php

namespace App\Http\Requests;

use App\Rules\Exists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'cities' => ['nullable', 'array', new Exists('cities')],
            'cities.*' => ['integer'],
            'professions' => ['nullable', 'array', new Exists('vacancies', column: 'title')],
            'professions.*' => ['string'],
            'tags' => ['nullable', 'array', new Exists('tags')],
            'tags.*' => ['string'],
            'companies' => ['nullable', 'array', new Exists('companies')],
            'companies.*' => ['integer'],
            'salary_start' => ['nullable', 'integer', 'min:0'],
            'salary_end' => ['nullable', 'integer', 'min:0', 'gte:salary_start'],
            'sort_salary_start' => ['nullable', Rule::in(['asc', 'desc'])],
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

    public function attributes(): array
    {
        return [
            'cities' => 'Города',
            'professions' => 'Профессии',
            'tags' => 'Теги',
            'companies' => 'Компании',
            'salary_start' => 'Зарплата от',
            'salary_end' => 'Зарплата до',
            'sort_salary_start' => 'Сортировать по зарплате'
        ];
    }
}
