<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\Response;

class VacancyPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vacancy $vacancy): bool
    {
        return !empty($user->company) && $user->company->id === $vacancy->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vacancy $vacancy): bool
    {
        return !empty($user->company) && $user->company->id === $vacancy->company_id;
    }

    public function like(User $user, Vacancy $vacancy)
    {
        return !(!empty($user->company) && $user->company->id === $vacancy->company_id);
    }
}
