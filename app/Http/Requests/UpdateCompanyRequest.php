<?php

namespace App\Http\Requests;

use App\Rules\UniqueCities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
            'company_id' => $this->route('company')->id,
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
            'name' => ['required', 'max:255', 'string',
            Rule::unique('companies')->ignore($this->company_id)
            ],
            'company_id' => ['required', 'exists:companies,id'],
            'description' => ['required', 'string', 'max:1000'],
            'new_cities' => ['nullable', 'string', new UniqueCities],
            'cities' => ['nullable', 'array'],
            'cities.*' => ['string', 'exists:cities,id'],
            'photo' => ['nullable', 'image', 'max:5120', 'exclude_with:delete_photo'],
            'delete_photo' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Название компании',
            'description' => 'Описание компании',
            'new_cities' => 'Новые города',
            'cities' => 'Города',
            'photo' => 'Логотип компании',
            'delete_photo' => 'Удалить логотип компании',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Отсутвует идентификатор компании!',
            'company_id.exists' => 'Такой компании не существует!',
            'cities.*.exists' => 'Таких городов не существует!',
        ];
    }
}
