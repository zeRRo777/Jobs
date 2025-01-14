<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class UserPolicy
{
    public function admin(User $currentUser, User $user)
    {
        return !empty($currentUser->company) && ($currentUser->id !== $user->id);
    }

    public function base(User $currenUser, User $user)
    {
        return $currenUser->id === $user->id;
    }
}
