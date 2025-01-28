<?php

namespace App\Services\User;

use App\Events\UserRegistered;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('Начало регистрации обычного пользователя');

        DB::beginTransaction();

        try {
            $user = User::create($dataValidated);

            Log::info('Был создан пользователь с ID: ' . $user->id);

            event(new UserRegistered($user));

            Log::info('Сработало событие при регистрации пользователя с ID: ' . $user->id);

            Auth::login($user, true);

            Log::info('Пользователь авторизован с ID: ' . $user->id);

            DB::commit();

            Log::info('Пользователь успешно зарегестрирован с ID: ' . $user->id);

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при регистрации пользователя: ' . $e->getMessage(), ['exception' => $e]);

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

        Log::info('Начало регистрации админа');

        DB::beginTransaction();

        try {
            if (isset($dataValidated['company'])) {
                $company = new Company();
                $company->name = $dataValidated['company'];
                $company->save();

                Log::info('Новая компания успешно создана с ID: ' . $company->id);

                $dataValidated['company_id'] = $company->id;
                unset($dataValidated['company']);
            } elseif (isset($dataValidated['secret_code'])) {
                $company = Company::where('secret_code', $dataValidated['secret_code'])->firstOrFail();
                $company->secret_code = null;
                $company->save();

                Log::info('Компания с секретным кодом успешно найдена с ID: ' . $company->id);

                $dataValidated['company_id'] = $company->id;
                unset($dataValidated['secret_code']);
            }

            $user = User::create($dataValidated);

            Log::info('Был создан админ с ID: ' . $user->id);

            event(new UserRegistered($user));

            Log::info('Сработало событие при регистрации админа с ID: ' . $user->id);

            Auth::login($user, true);

            Log::info('Пользователь авторизован с ID: ' . $user->id);

            DB::commit();

            Log::info('Пользователь успешно зарегестрирован с ID: ' . $user->id);
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при регистрации администратора: ' . $e->getMessage(), ['exception' => $e]);

            throw $e;
        }
    }
}
