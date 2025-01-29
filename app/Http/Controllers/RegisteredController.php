<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisteredRequest;
use App\Http\Requests\RegisteredRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Services\User\RegisteredService;
use Illuminate\Support\Facades\DB;

class RegisteredController extends Controller
{
    private RegisteredService $registeredService;

    public function __construct(RegisteredService $registeredService)
    {
        $this->registeredService = $registeredService;
    }

    public function store(RegisteredRequest $request): RedirectResponse
    {

        DB::beginTransaction();

        Log::info('Начало регистрации обычного пользователя');

        try {
            $user = $this->registeredService->registerUser($request->validated());

            Log::info('Пользователь успешно зарегестрирован с ID: ' . $user->id);

            DB::commit();

            return redirect()->route('vacancies');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Ошибка при регистрации пользователя: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->withErrors(['error' => 'Не удалось зарегистрировать пользователя. Попробуйте снова.']);
        }
    }

    public function admin_store(AdminRegisteredRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        Log::info('Начало регистрации админа');

        try {
            $user = $this->registeredService->registerAdmin($request->validated());

            DB::commit();

            Log::info('Пользователь успешно зарегестрирован с ID: ' . $user->id);

            return redirect()->route('company.show', $user->company->id);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Ошибка при регистрации администратора: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->withErrors(['error' => 'Не удалось зарегистрировать администратора. Попробуйте снова.']);
        }
    }
}
