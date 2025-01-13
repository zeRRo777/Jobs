<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisteredRequest;
use App\Http\Requests\RegisteredRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Services\User\RegisteredService;

class RegisteredController extends Controller
{
    private RegisteredService $registeredService;

    public function __construct(RegisteredService $registeredService)
    {
        $this->registeredService = $registeredService;
    }

    public function store(RegisteredRequest $request): RedirectResponse
    {
        try {
            $this->registeredService->registerUser($request->validated());
            return redirect()->route('vacancies');
        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации пользователя: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Не удалось зарегистрировать пользователя. Попробуйте снова.']);
        }
    }

    public function admin_store(AdminRegisteredRequest $request): RedirectResponse
    {
        try {
            $companyId = $this->registeredService->registerAdmin($request->validated());
            return redirect()->route('company.show', $companyId);
        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации администратора: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Не удалось зарегистрировать администратора. Попробуйте снова.']);
        }
    }
}
