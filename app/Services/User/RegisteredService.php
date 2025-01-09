<?php

namespace App\Services\User;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredService
{
    /**
     * Регистрация обычного пользователя
     *
     * @param array $dataValidated
     * @throws \Exception
     */
    public function registerUser(array $dataValidated): void
    {
        $dataValidated['password'] = Hash::make($dataValidated['password']);

        DB::beginTransaction();

        try {
            $user = User::create($dataValidated);
            // event(new Registered($user)); 
            Auth::login($user, true);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Регистрация администратора
     *
     * @param array $dataValidated
     * @return int $companyId
     * @throws \Exception
     */
    public function registerAdmin(array $dataValidated): int
    {
        $dataValidated['password'] = Hash::make($dataValidated['password']);

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
            // event(new Registered($user)); // Если нужно, включите
            Auth::login($user, true);

            DB::commit();
            return $company->id; // Возвращаем ID созданной компании
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
