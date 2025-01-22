<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserCompanyRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteUserCompanyRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\SmartFilterUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserOffersRequest;
use App\Mail\OfferVacancy;
use App\Models\City;
use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\User\UserFilterService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{

    protected $userService;
    protected $userFilterService;

    public function __construct(UserService $userService, UserFilterService $userFilterService)
    {
        $this->userService = $userService;
        $this->userFilterService = $userFilterService;
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

    public function show(User $user): View
    {
        $vacancies = Vacancy::where('company_id', '=', Auth::user()->company->id)->get()
            ->map(function ($vacancy) use ($user) {
                return [
                    'id' => $vacancy->id,
                    'title' => $vacancy->title,
                    'isLiked' => $vacancy->userLiked->contains($user),
                    'isOffered' => $vacancy->offeredUsers->contains($user)
                ];
            });

        return view('pages.users.show', compact('user', 'vacancies'));
    }

    public function all(SmartFilterUsersRequest $request)
    {
        $this->userFilterService->setData($request->validated());

        $query = $this->userFilterService->applyFilters();

        $validatedData = $this->userFilterService->getData();

        $users = $query->where('id', '!=', auth()->id())
            ->where('show', '=', true)
            ->paginate(10)
            ->appends($request->query());

        $cities = $this->userFilterService->getCitiesFilterData();

        $professions = $this->userFilterService->getProfessionFilterData();

        return view('pages.users.index', compact('users', 'cities', 'professions'));
    }

    public function offers(User $user, UserOffersRequest $request)
    {
        $validatedData = $request->validated();

        foreach ($validatedData['vacancies'] as $vacancyId) {
            $vacancy = Vacancy::findOrFail($vacancyId);

            Gate::authorize('offer', $vacancy);

            try {
                Mail::to($user)->send(new OfferVacancy($vacancy, $user, Auth::user()));

                if (!$vacancy->offeredUsers->contains($user)) {
                    $vacancy->offeredUsers()->attach($user->id);
                }
            } catch (\Exception $e) {
                Log::error('Ошибка при отправке письма: ' . $e->getMessage());
                return redirect()->route('user.show', $user->id)->withErrors(['error' => 'Произошла ошибка при отправке письма. Попробуйте еще раз!']);
            }
        }

        return redirect()->route('user.show', $user->id)->with('success', 'Письма о предложении работы успешно отправлены!');
    }

    public function deleteCompany(DeleteUserCompanyRequest $request)
    {

        $request->validated();

        $user = Auth::user();

        Gate::authorize('deleteUserCompany', $user);

        try {
            $user->company()->dissociate();

            $user->save();
        } catch (\Exception $e) {

            Log::error('Ошибка при удалении компании у пользователя с ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('profile', $user->id)->withErrors(['error' => 'Ошибка при удалении компании! Попробуйте еще раз!']);
        }

        return redirect()->route('profile', $user->id)->with('success', 'Компания успешно удалена!');
    }

    public function addCompany(AddUserCompanyRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();

        Gate::authorize('addCompany', $user);

        try {
            $company = Company::where('secret_code', '=', $validatedData['secret_code'])->firstOrFail();

            $user->company()->associate($company);
            $user->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при добавлении компании у пользователя с ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('profile', $user->id)->withErrors(['error' => 'Ошибка при добавлении компании! Попробуйте еще раз!']);
        }

        return redirect()->route('profile', $user->id)->with('success', 'Компания успешно добавлена!');
    }
}
