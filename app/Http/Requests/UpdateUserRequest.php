<?php

namespace App\Http\Requests;

use App\Rules\UniqueCities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 
            Rule::unique('users')->ignore($this->user_id)
            ],
            'profession' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'exists:users,id'],
            'new_cities' => ['nullable', 'string', new UniqueCities],
            'cities' => ['nullable', 'array'],
            'cities.*' => ['string', 'exists:cities,id'],
            'photo' => ['nullable', 'image', 'max:5120', 'exclude_with:delete_photo'],
            'delete_photo' => ['nullable', 'string'],
            'resume' => ['nullable', 'string', 'max:2000']
        ];
    }

    public function attributes() : array
    {
        return [
            'name' => 'ФИО',
            'email' => 'Электронная почта',
            'profession' => 'Профессия',
            'new_cities' => 'Новые города',
            'cities' => 'Города',
            'photo' => 'Фото пользователя',
            'resume' => 'Резюме пользователя',
            'delete_photo' => 'Удалить логотип компании',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Отсутвует идентификатор пользователя!',
            'user_id.exists' => 'Такого пользователя не существует!',
            'cities.*.exists' => 'Таких городов не существует!',
        ];
    }
}
