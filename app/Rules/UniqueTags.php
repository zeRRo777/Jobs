<?php

namespace App\Rules;

use App\Models\Tag;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueTags implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tags = explode(',', $value);

        $tags = array_map('trim', $tags);

        foreach ($tags as $tag) {
            if (Tag::where('name', $tag)->exists()) {
                $fail('Некоторые теги уже существуют, выберите их');
            }
        }
    }
}
