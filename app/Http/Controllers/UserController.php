<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\City;
use App\Models\User;
use App\Services\User\UserService;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(User $user)
    {
        $cities = City::all()->map(function ($city) use ($user){
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $user->cities->contains($city)
            ];
        });

        return view('pages.profile', compact('user', 'cities'));
    }

    public function update(User $user, UpdateUserRequest $request)
    {

        $validatedData = $request->validated();

        try {

            $this->userService->updateUser($user, $validatedData);
        } catch (\Exception $e) {

            return back()->withErrors(['photo' => $e->getMessage()]);
        }

        return redirect()->route('profile', $user->id)->with('success', 'Данные пользователя успешно обновлены!');
    }
}
