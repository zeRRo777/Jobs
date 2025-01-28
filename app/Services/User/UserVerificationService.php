<?php

namespace App\Services\User;

use App\Models\User;

class UserVerificationService
{
    public function verifyUser(User $user)
    {
        $user->show = true;

        $user->save();
    }
}
