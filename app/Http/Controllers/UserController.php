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
use App\Services\User\UserCompanyService;
use App\Services\User\UserFilterService;
use App\Services\User\UserSecurityService;
use App\Services\User\UserService;
use App\Services\User\UserVacancyService;
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
    protected $securityService;
    protected $companyService;
    protected $vacancyService;

    public function __construct(
        UserService $userService,
        UserFilterService $userFilterService,
        UserSecurityService $securityService,
        UserCompanyService $companyService,
        UserVacancyService $vacancyService
    ) {
        $this->userService = $userService;
        $this->userFilterService = $userFilterService;
        $this->securityService = $securityService;
        $this->companyService = $companyService;
        $this->vacancyService = $vacancyService;
    }

    public function index(User $user): View
    {
        $cities = City::all()->map(fn($city) => [
            'id' => $city->id,
            'name' => $city->name,
            'active' => $user->cities->contains($city)
        ]);

        return view('pages.profile', compact('user', 'cities'));
    }

    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {

        $validatedData = $request->validated();

        Log::info('Начало  обновления пользователя с ID: ' . $user->id);

        try {
            DB::transaction(function () use ($user, $validatedData) {

                $this->userService->updateUser($user, $validatedData);

                Log::info('Успешное обновления пользователя с ID: ' . $user->id);
            });

            return redirect()->route('profile', $user->id)->with('success', 'Данные успешно обновлены!');
        } catch (\Throwable $e) {
            Log::error('Ошибка обновления пользователя с ID: ' . $user->id, [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['error' => 'Произошла ошибка при обновлении данных'])
                ->withInput();
        }
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {

        $validatedData = $request->validated();

        $user = Auth::user();

        Log::info('Начало смены пароля для пользователя с ID: ' . $user->id);

        try {
            DB::transaction(function () use ($validatedData) {
                $this->securityService->changePassword($user, $validatedData['new_password']);
            });

            Log::info('Транзакция успешно завершена. Пароль пользователя с ID: ' . $user->id . ' изменен.');

            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('login')->with('success', 'Пароль успешно изменен!');
        } catch (\Exception $e) {

            Log::error('Ошибка при смене пароля для пользователя с ID: ' . $user->id . '. Ошибка: ' . $e->getMessage());
            return back()
                ->withErrors(['password' => 'Не удалось изменить пароль. Пожалуйста, попробуйте снова.']);
        }
    }

    public function delete(DeleteUserRequest $request): RedirectResponse
    {
        try {
            Log::info('Начало удаления аккаунта пользователя с ID: ' . Auth::id());

            DB::transaction(function () use ($request) {

                $this->securityService->deleteUser(Auth::user());
            });

            Log::info('Пользователь  с ID: ' . Auth::id() . ' успешно удален.');

            $request->session()->invalidate();

            return redirect()->route('register')->with('success', 'Аккаунт успешно удален!');
        } catch (\Exception $e) {

            Log::error('Ошибка при удалении аккаунта пользователя с ID: ' . Auth::id() . '. Ошибка: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Не удалось удалить аккаунт. Пожалуйста, попробуйте снова.']);
        }
    }

    public function show(User $user): View
    {
        $admin = Auth::user();

        $admin->load(['company.vacancies' => function ($query) {
            $query->with(['userLiked:id', 'offeredUsers:id']);
        }]);

        $vacancies = $admin->company->vacancies->map(function ($vacancy) use ($user) {
            return [
                'id' => $vacancy->id,
                'title' => $vacancy->title,
                'isLiked' => $vacancy->userLiked->contains('id', $user->id),
                'isOffered' => $vacancy->offeredUsers->contains('id', $user->id)
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

        Log::info('Начало процесса предложения работы пользователю с ID : ' . $user->id);

        try {
            DB::transaction(function () use ($user, $validatedData) {
                $this->vacancyService->sendOffers($user, $validatedData['vacancies']);
            });

            Log::info('Предложения работы успешно завершено для пользователя с ID: ' . $user->id);

            return redirect()->route('user.show', $user->id)->with('success', 'Письма о предложении работы успешно отправлены!');
        } catch (\Exception $e) {
            Log::error('Ошибка во время предложения работы для Пользователя с ID: ' . $user->id, [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('user.show', $user->id)->withErrors(['error' => 'Произошла ошибка при отправке письма. Попробуйте еще раз!']);
        }
    }

    public function deleteCompany(DeleteUserCompanyRequest $request)
    {

        $request->validated();

        $user = Auth::user();

        Log::info('Начало удаление компании у пользователя с ID: ' . $user->id);

        try {
            DB::transaction(function () {
                $this->companyService->removeCompany($user);
            });

            Log::info('Компания успешно удалена у пользователя с ID: ' . $user->id);

            return redirect()->route('profile', $user->id)->with('success', 'Компания успешно удалена!');
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении компании у пользователя с ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('profile', $user->id)->withErrors(['error' => 'Ошибка при удалении компании! Попробуйте еще раз!']);
        }
    }

    public function addCompany(AddUserCompanyRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();

        Log::info('Начало добавления компании для пользователя с ID ' . $user->id);

        try {
            DB::transaction(function () use ($validatedData, $user) {
                $company = Company::where('secret_code', $validatedData['secret_code'])->firstOrFail();
                $this->companyService->attachCompany($user, $company);
            });

            Log::info('Компания с ID ' . $user->company->id . 'успешно добавлена пользователем с ID ' . $user->id);

            return redirect()->route('profile', $user->id)->with('success', 'Компания успешно добавлена!');
        } catch (\Exception $e) {
            Log::error('Ошибка при добавлении компании у пользователя с ID ' . $user->id . ': ' . $e->getMessage());

            return redirect()->route('profile', $user->id)->withErrors(['error' => 'Ошибка при добавлении компании! Попробуйте еще раз!']);
        }
    }
}
