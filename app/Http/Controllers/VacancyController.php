<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterVacanciesRequest;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Models\Company;
use App\Models\Vacancy;
use App\Models\User;
use App\Services\Vacancy\VacancyDataService;
use App\Services\Vacancy\VacancyFilterService;
use App\Services\Vacancy\VacancyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VacancyController extends Controller
{
    protected VacancyService $vacancyService;
    protected VacancyFilterService $vacancyFilterService;
    protected VacancyDataService $vacancyDataService;

    public function __construct(
        VacancyService $vacancyService,
        VacancyFilterService $vacancyFilterService,
        VacancyDataService $vacancyDataService
    ) {
        $this->vacancyService = $vacancyService;
        $this->vacancyFilterService = $vacancyFilterService;
        $this->vacancyDataService = $vacancyDataService;
    }

    public function index(SmartFilterVacanciesRequest $request): View
    {
        $validatedData = $request->validated();

        $vacancies = $this->vacancyFilterService
            ->setData($validatedData)
            ->getFilteredVacancies()
            ->paginate(10)
            ->appends($request->query());

        $filtersData = $this->vacancyDataService->getAllFilterData($validatedData);
        $salaryRange = $this->vacancyDataService->getSalaryRange($validatedData);

        return view('pages.vacancy.index', array_merge(
            compact('vacancies'),
            $filtersData,
            $salaryRange
        ));
    }

    public function show(Vacancy $vacancy): View
    {
        $vacancy->load('company', 'city');

        $relationsData = $this->vacancyDataService->getVacancyRelationsData($vacancy);
        return view('pages.vacancy.show', array_merge(
            compact('vacancy'),
            $relationsData
        ));
    }

    public function update(Vacancy $vacancy, UpdateVacancyRequest $request): RedirectResponse
    {
        $dataValidated = $request->validated();

        Log::info('Начало обновление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id());

        try {
            DB::transaction(function () use ($dataValidated, $vacancy) {
                $this->vacancyService->updateVacancy($vacancy, $dataValidated);

                Log::info('Обновление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id() . ' завершено успешно.');
            });


            return redirect()->route('vacancy.show', $vacancy->id)->with('success', 'Данные вакансии успешно обновлены!');
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении вакансии: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Ошибка при обновлении данных вакансии. Попробуйте снова.');
        }
    }

    public function delete(Vacancy $vacancy): RedirectResponse
    {
        Log::info('Начало удаление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id());

        try {
            DB::transaction(function () use ($vacancy) {
                $this->vacancyService->delete($vacancy);

                Log::info('Удаление вакансии с ID ' . $vacancy->id . ' у пользователя с ID: ' . Auth::id() . ' завершено успешно.');
            });

            return redirect()->route('company.show', Auth::user()->company->id)->with('success', 'Вакансия успешно удалена!');
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении вакансии: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['delete_vacancy' => 'Ошибка при удалении вакансии. Попробуйте снова.']);
        }
    }

    public function store(StoreVacancyRequest $request, Company $company): RedirectResponse
    {
        Gate::authorize('createVacancy', $company);

        $dataValidated = $request->validated();

        try {

            Log::info('Начало создания вакансии у пользователя с ID: ' . Auth::id());

            $vacancy = DB::transaction(function () use ($dataValidated) {
                $vacancy = $this->vacancyService->createVacancy($dataValidated);

                Log::info('Создание вакансии у пользователя с ID: ' . Auth::id() . ' завершено успешно.');

                return $vacancy;
            });

            return redirect()->route('vacancy.show', $vacancy->id)->with('success', 'Вакансия успешно создана!');
        } catch (\Exception $e) {
            Log::error('Ошибка при создании вакансии: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Ошибка при создании новой вакансии. Попробуйте снова.');
        }
    }

    public function likes(User $user): View
    {
        $vacancies = $user->likedVacancies()->paginate(10);

        return view('pages.vacancy.likes', compact('vacancies'));
    }
}
