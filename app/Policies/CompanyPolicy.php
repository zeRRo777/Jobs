<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return !empty($user->company) && $user->company->id === $company->id && $user->hasVerifiedEmail();
    }

    public function generateCode(User $user, Company $company): bool
    {
        return !empty($user->company) && $user->company->id === $company->id && $user->hasVerifiedEmail();
    }

    public function createVacancy(User $user, Company $company): bool
    {
        return !empty($user->company) && $user->company->id === $company->id && $user->hasVerifiedEmail();
    }
}
