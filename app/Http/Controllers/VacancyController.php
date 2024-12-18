<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use App\Models\City;
use App\Models\Tag;
use App\Models\Company;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index()
    {

        $cities = City::whereHas('vacancies')->pluck('name', 'id')->unique()->toArray();

        $tags = Tag::whereHas('vacancies')->pluck('name', 'id')->unique()->toArray();

        $companies = Company::whereHas('vacancies')->pluck('name', 'id')->unique()->toArray();

        $professions = Vacancy::select('title')->distinct()->pluck('title', 'title')->toArray();

        $vacancies = Vacancy::with(['tags', 'city', 'company'])->paginate(10);

        return view(
            'pages.vacancy.index',
            [
                'vacancies' => $vacancies,
                'cities' => $cities,
                'tags' => $tags,
                'companies' => $companies,
                'professions' => $professions,
            ]
        );
    }
}
