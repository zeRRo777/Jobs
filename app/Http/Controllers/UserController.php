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

        DB::beginTransaction();

        Log::info('Начало транзакции для обновления пользователя с ID: ' . $user->id);

        try {

            $this->userService->updateUser($user, $validatedData);

            DB::commit();

            Log::info('Транзакция успешно завершена для пользователя с ID: ' . $user->id);

            return redirect()->route('profile', $user->id)->with('success', 'Данные пользователя успешно обновлены!');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Ошибка при обновлении пользователя с ID: ' . $user->id . '. Ошибка: ' . $e->getMessage());

            return back()->withErrors(['photo' => $e->getMessage()]);
        }
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = Auth::user();

        Log::info('Начало смены пароля для пользователя с ID: ' . $user->id);

        try {
            DB::beginTransaction();

            Log::info('Начало транзакции для смены пароля пользователя с ID: ' . $user->id);

            $user->password = Hash::make($validatedData['new_password']);
            $user->save();

            DB::commit();
            Log::info('Транзакция успешно завершена. Пароль пользователя с ID: ' . $user->id . ' изменен.');

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            Log::info('Пользователь с ID: ' . $user->id . ' вышел из системы после смены пароля.');

            return redirect()->route('login')->with('success', 'Пароль успешно изменен!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при смене пароля для пользователя с ID: ' . $user->id . '. Ошибка: ' . $e->getMessage());
            return back()->withErrors(['password' => 'Не удалось изменить пароль. Пожалуйста, попробуйте снова.']);
        }
    }

    public function delete(DeleteUserRequest $request): RedirectResponse
    {
        $request->validated();

        $user = Auth::user();

        Log::info('Начало удаления аккаунта пользователя с ID: ' . $user->id);

        try {

            Auth::logout();
            Log::info('Пользователь с ID: ' . $user->id . ' вышел из системы перед удалением аккаунта.');

            $user->delete();

            Log::info('Аккаунт пользователя с ID: ' . $user->id . ' успешно удален.');

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            Log::info('Процесс удаления аккаунта пользователя с ID: ' . $user->id . ' завершен успешно.');

            return redirect()->route('register')->with('success', 'Аккаунт был успешно удален!');
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении аккаунта пользователя с ID: ' . $user->id . '. Ошибка: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Не удалось удалить аккаунт. Пожалуйста, попробуйте снова.']);
        }
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

        DB::beginTransaction();

        try {

            Log::info('Начало процесса предложения работы пользователю с ID : ' . $user->id);

            foreach ($validatedData['vacancies'] as $vacancyId) {

                $vacancy = Vacancy::findOrFail($vacancyId);

                Log::info('Начался процесс предложения работы по вакансии с ID: ' . $vacancyId . ' для пользователя с ID: ' . $user->id);

                Gate::authorize('offer', $vacancy);

                Mail::to($user)->send(new OfferVacancy($vacancy, $user, Auth::user()));

                Log::info('Отправлено письмо с предложением работы по вакансии с ID: ' . $vacancyId . ' для пользователя с ID: ' . $user->id);

                if (!$vacancy->offeredUsers->contains($user)) {

                    $vacancy->offeredUsers()->attach($user->id);

                    Log::info('Пользователю с ID: ' . $user->id . ' добавлена связь в бд с вакансией ID: ' . $vacancyId);
                }
            }

            DB::commit();

            Log::info('Предложения работы успешно завершено для пользователя с ID: ' . $user->id);

            return redirect()->route('user.show', $user->id)->with('success', 'Письма о предложении работы успешно отправлены!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка во время предложения работы для Пользователя с ID: ' . $user->id, [
                'error' => $e->getMessage(),
                'vacancy_id' => $vacancyId ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('user.show', $user->id)->withErrors(['error' => 'Произошла ошибка при отправке письма. Попробуйте еще раз!']);
        }
    }

    public function deleteCompany(DeleteUserCompanyRequest $request)
    {

        $request->validated();

        $user = Auth::user();

        Log::info('Начало удаление компании у пользователя с ID: ' . $user->id);

        DB::beginTransaction();

        try {
            $user->company()->dissociate();

            $user->save();

            DB::commit();

            Log::info('Компания успешно удалена у пользователя с ID: ' . $user->id);

            return redirect()->route('profile', $user->id)->with('success', 'Компания успешно удалена!');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Ошибка при удалении компании у пользователя с ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('profile', $user->id)->withErrors(['error' => 'Ошибка при удалении компании! Попробуйте еще раз!']);
        }
    }

    public function addCompany(AddUserCompanyRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();

        DB::beginTransaction();

        try {

            Log::info('Попытка добавления компании для пользователя с ID ' . $user->id);

            $company = Company::where('secret_code', '=', $validatedData['secret_code'])->firstOrFail();

            $vacancyIds = $company->vacancies()->pluck('id');

            $user->offeredVacancies()->detach($vacancyIds);

            $user->likedVacancies()->detach($vacancyIds);

            $user->company()->associate($company);
            $user->save();

            Log::info('Компания с ID ' . $company->id . ' добавлена пользователем с ID ' . $user->id);

            $company->secret_code = null;
            $company->save();

            DB::commit();

            Log::info('Транзакция успешно завершена для пользователя с ID ' . $user->id);

            return redirect()->route('profile', $user->id)->with('success', 'Компания успешно добавлена!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при добавлении компании у пользователя с ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('profile', $user->id)->withErrors(['error' => 'Ошибка при добавлении компании! Попробуйте еще раз!']);
        }
    }
}
