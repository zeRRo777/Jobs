<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return !empty($user->company) && $user->company->id === $company->id;
    }

    public function generateCode(User $user): bool
    {
        return !empty($user->company);
    }

    public function createVacancy(User $user, Company $company): bool
    {
        return !empty($user->company) && $user->company->id === $company->id;
    }
}
