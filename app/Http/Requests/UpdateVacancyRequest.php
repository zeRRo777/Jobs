<?php

namespace App\Http\Requests;

use App\Rules\UniqueTags;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVacancyRequest extends FormRequest
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
        'title' => ['required', 'max:255', 'string', ],
        'description' => ['nullable', 'string', 'max:1000'],
        'new_city' => ['nullable', 'string', 'max:255', 'unique:cities,name'],
        'company_id' => ['required', 'exists:companies,id'],
        'city_id' => ['required', 'exists:cities,id'],
        'new_tags' => ['nullable', 'string', new UniqueTags],
        'tags' => ['nullable', 'array'],
        'tags.*' => ['string', 'exists:tags,id'],
        'salary_start' => ['nullable', 'integer', 'min:1'],
        'salary_end' => ['nullable', 'integer', 'min:1', 'gte:salary_start'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'Вакансия с таким названием уже сущесвует в данной компании',
            'company_id.required' => 'Вакансия должна быть прикреплена к компании',
            'company_id.exists' => 'Компании к которой вы хотите прикрепить вакансию не сущесвует',
            'tags.*.exists' => 'Таких тегов не существует!',
            'salary_end.gte' => 'Зарплата от должна быть больше зарплаты до !'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Название вакансии',
            'description' => 'Описание вакансии',
            'new_city' => 'Новая город',
            'salary_start' => 'Начальная зарплата',
            'salary_end' => 'Конечная зарплата',
            'city_id' => 'Город',
            'new_tags' => 'Новые теги',
            'tags' => 'Теги',
        ];
    }
}