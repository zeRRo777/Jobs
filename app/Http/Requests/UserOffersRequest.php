<?php

namespace App\Http\Requests;

use App\Rules\Exists;
use Illuminate\Foundation\Http\FormRequest;

class UserOffersRequest extends FormRequest
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
            'user_id' => $this->route('user')->id,
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
            'vacancies' => ['required', 'array', new Exists('vacancies')],
            'vacancies.*' => ['string'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'vacancies' => 'Вакансии',
            'user_id' => 'Пользователь',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Отсутвует идентификатор пользователя!',
            'user_id.exists' => 'Пользователя не существует!',
            'vacancies.*.exists' => 'Вакансий не существует!',
            'vacancies.required' => 'Вы не выбрали ни одной вакансии!'
        ];
    }
}
