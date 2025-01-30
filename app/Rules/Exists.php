<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class Exists implements ValidationRule
{
    public function __construct(
        private string $table,
        private ?string $message = null,
        private string $column = 'id'
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ids = array_unique((array) $value);

        $existingValues = DB::table($this->table)
            ->whereIn($this->column, $ids)
            ->distinct()
            ->pluck($this->column)
            ->toArray();


        if (count($existingValues) !== count($ids)) {
            $missingValues = array_diff($ids, $existingValues);

            $fail($this->message ?? __('validation.exists', [
                'attribute' => $attribute,
                'missing' => implode(', ', $missingValues)
            ]));
        }
    }
}
