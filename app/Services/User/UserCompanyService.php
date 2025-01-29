<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Company;

class UserCompanyService
{

    public function removeCompany(User $user): void
    {
        $user->company()->dissociate();
        $user->save();
    }

    public function attachCompany(User $user, Company $company): void
    {
        $vacancyIds = $company->vacancies()->pluck('id');
        $user->offeredVacancies()->detach($vacancyIds);
        $user->likedVacancies()->detach($vacancyIds);
        $user->company()->associate($company);
        $user->save();

        $company->secret_code = null;
        $company->save();
    }
}
