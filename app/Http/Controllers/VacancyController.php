<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterVacanciesRequest;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Models\Vacancy;
use App\Models\City;
use App\Models\Tag;
use App\Models\Company;
use App\Models\User;
use App\Services\Vacancy\VacancyFilterService;
use App\Services\Vacancy\VacancyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VacancyController extends Controller
{

    protected $vacancyService;

    protected $vacancyFilterService;

    public function __construct(VacancyService $vacancyService, VacancyFilterService $vacancyFilterService)
    {
        $this->vacancyService = $vacancyService;
        $this->vacancyFilterService = $vacancyFilterService;
    }

    public function index(SmartFilterVacanciesRequest $request): View
    {
        $validatedData = $request->validated();

        $this->vacancyFilterService->setData($validatedData);

        $query = $this->vacancyFilterService->applyFilters();

        $validatedData = $this->vacancyFilterService->getData();

        $sortColumn = 'created_at';
        $sortDirection = 'desc';

        if ($request->has('sort_salary_start')) {
            $sortColumn = 'salary_start';
            $sortDirection = $request->get('sort_salary_start');
        }

        $vacancies = $query->with(['tags', 'city', 'company'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(10)
            ->appends($request->query());

        $salaryStats = Vacancy::selectRaw('MIN(salary_start) as min_salary, MAX(salary_start) as max_salary_start, MAX(salary_end) as max_salary_end')->first();

        $minSalary = $validatedData['salary_start'] ?? $salaryStats->min_salary;
        $maxSalary = $validatedData['salary_end'] ?? ($salaryStats->max_salary_start > $salaryStats->max_salary_end) ? $salaryStats->max_salary_start : $salaryStats->max_salary_end;

        $cities = $this->vacancyFilterService->getFilterData(City::class, 'cities');
        $tags = $this->vacancyFilterService->getFilterData(Tag::class, 'tags');
        $companies = $this->vacancyFilterService->getFilterData(Company::class, 'companies');
        $professions = $this->vacancyFilterService->getProfessionFilterData();

        return view('pages.vacancy.index', compact(
            'vacancies',
            'cities',
            'tags',
            'companies',
            'professions',
            'minSalary',
            'maxSalary'
        ));
    }

    public function show(Vacancy $vacancy): View
    {
        $tags = Tag::all()->map(function ($tag) use ($vacancy) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'active' => $vacancy->tags->contains($tag)
            ];
        });

        $cities = City::all()->map(function ($city) use ($vacancy) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $city->id == $vacancy->city?->id
            ];
        });

        $users = $vacancy->userLiked()->get();

        return view('pages.vacancy.show', compact('vacancy', 'tags', 'cities', 'users'));
    }

    public function update(Vacancy $vacancy, UpdateVacancyRequest $request): RedirectResponse
    {
        $dataValidated = $request->validated();

        Log::info('Начало обновление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id());
        try {
            $this->vacancyService->updateVacancy($vacancy, $dataValidated);
            Log::info('Обновление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id() . ' завершено успешно.');
            return redirect()->route('vacancy.show', $vacancy->id)->with('success', 'Данные вакансии успешно обновлены!');
        } catch (\Throwable $e) {
            Log::error('Ошибка при обновлении вакансии: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error' => 'Ошибка при обновлении данных вакансии. Попробуйте снова.']);
        }
    }

    public function delete(Vacancy $vacancy): RedirectResponse
    {
        Log::info('Начало удаление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id());
        try {
            $this->vacancyService->delete($vacancy);
            Log::info('Удаление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id() . ' завершено успешно.');
            return redirect()->route('company.show', Auth::user()->company->id)->with('success', 'Вакансия успешно удалена!');
        } catch (\Throwable $e) {
            Log::error('Ошибка при удалении вакансии: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['delete_vacancy' => 'Ошибка при удалении вакансии. Попробуйте снова.']);
        }
    }

    public function store(StoreVacancyRequest $request): RedirectResponse
    {
        $dataValidated = $request->validated();

        Log::info('Начало создания вакансии у пользователя с ID: ' . Auth::id());

        try {
            $vacancy = $this->vacancyService->createVacancy($dataValidated);

            Log::info('Создание вакансии у пользователя с ID: ' . Auth::id() . ' завершено успешно.');

            return redirect()->route('vacancy.show', $vacancy->id)->with('success', 'Вакансия успешно создана!');
        } catch (\Throwable $e) {
            Log::error('Ошибка при создании вакансии: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error_vacancy' => 'Ошибка при создании новой вакансии. Попробуйте снова.']);
        }
    }

    public function likes(User $user)
    {
        $vacancies = $user->likedVacancies()->paginate(10);

        return view('pages.vacancy.likes', compact('vacancies'));
    }
}
