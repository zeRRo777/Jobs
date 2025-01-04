<?php

namespace App\Rules;

use App\Models\City;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCities implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cities = explode(',', $value);

        $cities = array_map('trim', $cities);

        foreach ($cities as $city) {
            if (City::where('name', $city)->exists()) {
                $fail('Некоторые города уже существуют, выберите их');
            }
        }
    }
}
