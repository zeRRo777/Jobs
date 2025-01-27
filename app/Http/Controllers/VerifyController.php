<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerifyController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = Auth::user();

        $user->show = true;

        $user->save();

        return redirect()->route('profile', Auth::id())->with(['success' => 'Вы успешно подтвердили почту!']);
    }
}
