<?php

namespace App\Http\Controllers;

use App\Services\User\UserVerificationService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifyController extends Controller
{

    public function __construct(
        private UserVerificationService $userVerificationService
    ) {}

    public function verify(EmailVerificationRequest $request)
    {

        DB::beginTransaction();

        Log::info('Начало подтверждения Email у пользователя с ID: ' . $request->user()->id);

        try {
            $request->fulfill();

            $this->userVerificationService->verifyUser($request->user());

            DB::commit();

            Log::info('Email подтверждён у пользователя с ID: ' . Auth::id());

            return redirect()->route('profile', Auth::id())->with(['success' => 'Вы успешно подтвердили почту!']);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при подтверждении email', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('profile', Auth::id())->withErrors(['error' => 'Не удалось подтвердить почту. Попробуйте позже.']);
        }
    }
}
