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

        $existingCount = DB::table($this->table)
            ->whereIn($this->column, $ids)
            ->count();

        if ($existingCount !== count($ids)) {
            if ($this->message) {
                $fail($this->message);
            } else {
                $fail(__('validation.my_exists', ['attribute' => $attribute]));
            }
        }
    }
}
