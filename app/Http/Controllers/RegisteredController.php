<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\AdminRegisteredRequest;
use App\Http\Requests\RegisteredRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
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

            return redirect()->back()->withErrors(['error' => 'Не удалось зарегистрировать пользователя. Попробуйте снова.']);
        }
    }

    public function admin_store(AdminRegisteredRequest $request): RedirectResponse
    {
        try {
            $user = $this->registeredService->registerAdmin($request->validated());

            return redirect()->route('company.show', $user->company->id);
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => 'Не удалось зарегистрировать администратора. Попробуйте снова.']);
        }
    }
}
