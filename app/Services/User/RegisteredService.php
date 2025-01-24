<?php

namespace App\Services\User;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $user = User::create($dataValidated);
            // event(new Registered($user)); 
            Auth::login($user, true);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
        DB::beginTransaction();

        try {
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

            Auth::login($user, true);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
