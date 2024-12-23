<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterCompaniesRequest;
use App\Models\Company;
use App\Services\Company\CompanyFilterService;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function popular(): View
    {
        $companies = Company::with('cities')->withCount('vacancies')
            ->orderBy('vacancies_count', 'desc')
            ->take(10)
            ->get();
        return view('pages.index', ['companies' => $companies]);
    }

    public function index(SmartFilterCompaniesRequest $request): View
    {

        $validatedData = $request->validated();

        $filterService = new CompanyFilterService($validatedData);

        $query = $filterService->applyFilters();

        $validatedData = $filterService->getData();

        $companies = $query->with('cities')
            ->withCount('vacancies')
            ->paginate(10)
            ->appends($request->query());


        $cities = $filterService->getFilterCityData();

        $companiesFilter = $filterService->getFilterCompanyData();

        return view('pages.company.index', compact('companies', 'cities', 'companiesFilter'));
    }
}
