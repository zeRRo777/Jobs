<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterVacanciesRequest;
use App\Models\Vacancy;
use App\Models\City;
use App\Models\Tag;
use App\Models\Company;
use App\Services\Vacancy\VacancyFilterService;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(SmartFilterVacanciesRequest $request): View
    {
        $validatedData = $request->validated();

        $filterService = new VacancyFilterService($validatedData);

        $query = $filterService->applyFilters();

        $validatedData = $filterService->getData();

        $vacancies = $query->with(['tags', 'city', 'company'])
            ->paginate(10)
            ->appends($request->query());

        $salaryStats = Vacancy::selectRaw('MIN(salary_start) as min_salary, MAX(salary_end) as max_salary')->first();

        $minSalary = $validatedData['salary_start'] ?? $salaryStats->min_salary;
        $maxSalary = $validatedData['salary_end'] ?? $salaryStats->max_salary;

        $cities = $filterService->getFilterData(City::class, 'cities');
        $tags = $filterService->getFilterData(Tag::class, 'tags');
        $companies = $filterService->getFilterData(Company::class, 'companies');
        $professions = $filterService->getProfessionFilterData();

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
}
