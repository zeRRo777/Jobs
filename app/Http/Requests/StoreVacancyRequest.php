<?php

namespace App\Http\Requests;

use App\Models\Tag;
use App\Rules\UniqueTags;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'company_id_vacancy' => $this->route('company')->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title_vacancy' => [
                'required',
                'max:255',
                'string',
                Rule::unique('vacancies', 'title')->where(function (Builder $query) {
                    return $query->where('company_id', $this->company_id_vacancy);
                })
            ],
            'description_vacancy' => ['nullable', 'string', 'max:1000'],
            'new_city_vacancy' => ['nullable', 'string', 'max:255', 'unique:cities,name'],
            'company_id_vacancy' => ['required', 'exists:companies,id'],
            'city_id_vacancy' => ['nullable', 'exists:cities,id'],
            'new_tags_vacancy' => ['nullable', 'string', new UniqueTags],
            'tags_vacancy' => ['nullable', 'array'],
            'tags_vacancy.*' => ['string', 'exists:tags,id'],
            'salary_start_vacancy' => ['nullable', 'integer', 'min:1'],
            'salary_end_vacancy' => ['nullable', 'integer', 'min:1', 'gte:salary_start'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_vacancy.unique' => 'Вакансия с таким названием уже сущесвует в данной компании!',
            'company_id_vacancy.required' => 'Вакансия должна быть прикреплена к компании!',
            'company_id_vacancy.exists' => 'Компании к которой вы хотите прикрепить вакансию не сущесвует!',
            'tags_vacancy.*.exists' => 'Таких тегов не существует!',
            'salary_end_vacancy.gte' => 'Зарплата от должна быть больше зарплаты до !'
        ];
    }

    public function attributes(): array
    {
        return [
            'title_vacancy' => 'Название вакансии',
            'description_vacancy' => 'Описание вакансии',
            'new_city_vacancy' => 'Новая город',
            'salary_start_vacancy' => 'Начальная зарплата',
            'salary_end_vacancy' => 'Конечная зарплата',
            'city_id_vacancy' => 'Город',
            'new_tags_vacancy' => 'Новые теги',
            'tags_vacancy' => 'Теги',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated($key, $default);

        $newData = [];

        foreach ($validatedData as $key => $value) {

            $newKey = preg_replace('/_vacancy$/', '', $key);
            $newData[$newKey] = $value;
        }

        return $newData;
    }
}
