<?php

namespace App\Services\User;

use App\Events\UserRegistered;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisteredService
{
    /**
     * Регистрация обычного пользователя
     *
     * @param array $dataValidated
     * @throws \Exception
     */
    public function registerUser(array $dataValidated): User
    {
        $user = User::create($dataValidated);

        event(new UserRegistered($user));

        Auth::login($user, true);

        return $user;
    }

    /**
     * Регистрация администратора
     *
     * @param array $dataValidated
     * @return User $user
     * @throws \Exception
     */
    public function registerAdmin(array $dataValidated): User
    {
        if (isset($dataValidated['company'])) {
            $company = new Company();
            $company->name = $dataValidated['company'];
            $company->save();

            $dataValidated['company_id'] = $company->id;
            unset($dataValidated['company']);
        } elseif (isset($dataValidated['secret_code'])) {
            $company = Company::where('secret_code', $dataValidated['secret_code'])->firstOrFail();
            $company->secret_code = null;
            $company->save();

            $dataValidated['company_id'] = $company->id;
            unset($dataValidated['secret_code']);
        }

        $user = User::create($dataValidated);

        event(new UserRegistered($user));

        Auth::login($user, true);

        return $user;
    }
}
