<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\City;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(User $user): View
    {
        $cities = City::all()->map(function ($city) use ($user) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $user->cities->contains($city)
            ];
        });

        return view('pages.profile', compact('user', 'cities'));
    }

    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {

        $validatedData = $request->validated();

        try {

            $this->userService->updateUser($user, $validatedData);
        } catch (\Exception $e) {

            return back()->withErrors(['photo' => $e->getMessage()]);
        }

        return redirect()->route('profile', $user->id)->with('success', 'Данные пользователя успешно обновлены!');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $user->password = Hash::make($validatedData['new_password']);
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['password' => 'Не удалось изменить пароль. Пожалуйста, попробуйте снова.']);
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Пароль успешно изменен!');
    }

    public function delete(DeleteUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('register')->with('success', 'Аккаунт был успешно удален!');
    }
}
