<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function admin(User $user): bool
    {
        return !empty($user->company);
    }

    public function base(User $currenUser, User $user): bool
    {
        return $currenUser->id === $user->id;
    }

    public function viewUserDetail(User $currentUser, User $user): bool
    {
        return !empty($currentUser->company)
            && ($currentUser->id !== $user->id)
            && ($user->show || $user->likedVacancies->intersect($currentUser->company->vacancies)->isNotEmpty());
    }

    public function onlyBase(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id && empty($user->company);
    }

    public function onlyAdmin(User $currentUser, User $user)
    {
        return !empty($currentUser->company) && $currentUser->id !== $user->id;
    }
}
