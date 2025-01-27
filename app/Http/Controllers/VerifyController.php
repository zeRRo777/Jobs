<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifyController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {

        DB::beginTransaction();

        try {

            $request->fulfill();

            $user = Auth::user();

            $user->show = true;

            $user->save();

            DB::commit();

            Log::info('Email подтверждён', ['user_id' => $user->id]);

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
