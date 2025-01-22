<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class UserPolicy
{
    public function admin(User $currentUser, User $user): bool
    {
        return !empty($currentUser->company) && ($currentUser->id !== $user->id);
    }

    public function base(User $currenUser, User $user): bool
    {
        return $currenUser->id === $user->id;
    }

    public function viewAllUsers(User $currentUser): bool
    {
        return !empty($currentUser->company);
    }

    public function viewUserDetail(User $currentUser, User $user): bool
    {
        return !empty($currentUser->company)
            && ($currentUser->id !== $user->id)
            && ($user->show || $user->likedVacancies->intersect($currentUser->company->vacancies)->isNotEmpty());
    }

    public function deleteUserCompany(User $user)
    {
        return !empty($user->company);
    }
}
