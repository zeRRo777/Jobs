<?php

namespace App\Services\User;

use App\Models\User;

class UserVerificationService
{
    public function verifyUser(User $user): void
    {
        $user->show = true;

        $user->save();
    }
}
