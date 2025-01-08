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
            'company_id_company' => $this->route('company')->id,
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
            'name_company' => ['required', 'max:255', 'string',
            Rule::unique('companies')->ignore($this->company_id_company)
            ],
            'company_id_company' => ['required', 'exists:companies,id'],
            'description_company' => ['required', 'string', 'max:1000'],
            'new_cities_company' => ['nullable', 'string', new UniqueCities],
            'cities_company' => ['nullable', 'array'],
            'cities_company.*' => ['string', 'exists:cities,id'],
            'photo_company' => ['nullable', 'image', 'max:5120', 'exclude_with:delete_photo'],
            'delete_photo_company' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name_company' => 'Название компании',
            'description_company' => 'Описание компании',
            'new_cities_company' => 'Новые города',
            'cities_company' => 'Города',
            'photo_company' => 'Логотип компании',
            'delete_photo_company' => 'Удалить логотип компании',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id_company.required' => 'Отсутвует идентификатор компании!',
            'company_id_company.exists' => 'Такой компании не существует!',
            'cities_company.*.exists' => 'Таких городов не существует!',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated($key, $default);

        $newData = [];

        foreach ($validatedData as $key => $value) {

            $newKey = preg_replace('/_company$/', '', $key);
            $newData[$newKey] = $value;
        }

        return $newData;
    }
}
