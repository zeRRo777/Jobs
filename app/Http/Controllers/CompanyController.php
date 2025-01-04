<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterCompaniesRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\City;
use App\Models\Company;
use App\Services\Company\CompanyFilterService;
use App\Services\Company\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{

    protected $companyService;

    protected $companyFilterService;

    public function __construct(CompanyService $companyService, CompanyFilterService $companyFilterService)
    {
        $this->companyService = $companyService;
        $this->companyFilterService = $companyFilterService;
    }

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

        $this->companyFilterService->setData($validatedData);

        $query = $this->companyFilterService->applyFilters();

        $validatedData = $this->companyFilterService->getData();

        $companies = $query->with('cities')
            ->withCount('vacancies')
            ->paginate(10)
            ->appends($request->query());


        $cities = $this->companyFilterService->getFilterCityData();

        $companiesFilter = $this->companyFilterService->getFilterCompanyData();

        return view('pages.company.index', compact('companies', 'cities', 'companiesFilter'));
    }

    public function show(Company $company): View
    {

        $vacancies = $company->vacancies()->with('tags', 'company', 'city')->orderBy('created_at', 'desc')->paginate(3);

        $cities = City::all()->map(function ($city) use ($company){
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $company->cities->contains($city)
            ];
        });

        return view('pages.company.show', compact('company', 'vacancies', 'cities'));
    }

    public function update(Company $company, UpdateCompanyRequest $request)
    {
        $validatedData = $request->validated();

        try {

            $this->companyService->updateCompany($company, $validatedData);
        } catch (\Exception $e) {

            return back()->withErrors(['photo' => $e->getMessage()]);
        }

        return redirect()->route('company.show', $company->id)->with('success', 'Данные компании успешно обновлены!');
    }
}
