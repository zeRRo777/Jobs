<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterVacanciesRequest;
use App\Models\Vacancy;
use App\Models\City;
use App\Models\Tag;
use App\Models\Company;

class VacancyController extends Controller
{
    public function index(SmartFilterVacanciesRequest $request)
    {
        $validatedData = $request->validated();

        $query = Vacancy::query();


        if (isset($validatedData['cities'])) {
            $query->whereIn('city_id', $validatedData['cities']);
        }
        if (isset($validatedData['tags'])) {
            $tags = $validatedData['tags'];
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('id', $tags);
            });
        }
        if (isset($validatedData['professions'])) {
            $query->whereIn('title', $validatedData['professions']);
        }
        if (isset($validatedData['companies'])) {
            $query->whereIn('company_id', $validatedData['companies']);
        }
        if (isset($validatedData['salary_start'])) {
            $query->where(function ($subQuery) use ($validatedData) {
                $subQuery->where('salary_start', '>=', $validatedData['salary_start'])->orWhereNull('salary_start');
            });
        }
        if (isset($validatedData['salary_end'])) {
            $query->where(function ($subQuery) use ($validatedData) {
                $subQuery->where('salary_end', '<=', $validatedData['salary_end'])
                    ->orWhereNull('salary_end');
            });
        }

        $vacancies = $query->with(['tags', 'city', 'company'])->paginate(10)->appends($request->query());

        $maxSalary = $validatedData['salary_end'] ?? Vacancy::max('salary_end');

        $minSalary = $validatedData['salary_start'] ?? Vacancy::min('salary_start');

        $cities = City::whereHas('vacancies')
            ->pluck('name', 'id')->unique()
            ->map(function ($name, $id) use ($validatedData) {
                return [
                    'name' => $name,
                    'active' => in_array($id, $validatedData['cities'] ?? []),
                ];
            })
            ->toArray();

        $tags = Tag::whereHas('vacancies')
            ->pluck('name', 'id')
            ->unique()
            ->map(function ($name, $id) use ($validatedData) {
                return [
                    'name' => $name,
                    'active' => in_array($id, $validatedData['tags'] ?? []),
                ];
            })
            ->toArray();

        $companies = Company::whereHas('vacancies')
            ->pluck('name', 'id')
            ->unique()
            ->map(function ($name, $id) use ($validatedData) {
                return [
                    'name' => $name,
                    'active' => in_array($id, $validatedData['companies'] ?? []),
                ];
            })
            ->toArray();

        $professions = Vacancy::select('title')
            ->distinct()
            ->pluck('title', 'title')
            ->map(function ($name, $id) use ($validatedData) {
                return [
                    'name' => $name,
                    'active' => in_array($id, $validatedData['professions'] ?? []),
                ];
            })
            ->toArray();

        return view(
            'pages.vacancy.index',
            [
                'vacancies' => $vacancies,
                'cities' => $cities,
                'tags' => $tags,
                'companies' => $companies,
                'professions' => $professions,
                'minSalary' => $minSalary,
                'maxSalary' => $maxSalary,
            ]
        );
    }
}
