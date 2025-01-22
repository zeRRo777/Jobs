<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmartFilterCompaniesRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\Tag;
use App\Models\User;
use App\Services\Company\CompanyFilterService;
use App\Services\Company\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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

        $sortColumn = 'created_at';
        $sortDirection = 'desc';

        if ($request->has('sort_vacancies_count')) {
            $sortColumn = 'vacancies_count';
            $sortDirection = $request->get('sort_vacancies_count');
        }

        $companies = $query->with('cities')
            ->withCount('vacancies')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(10)
            ->appends($request->query());


        $cities = $this->companyFilterService->getFilterCityData();

        $companiesFilter = $this->companyFilterService->getFilterCompanyData();

        return view('pages.company.index', compact('companies', 'cities', 'companiesFilter'));
    }

    public function show(Company $company): View
    {

        $vacancies = $company->vacancies()->with('tags', 'company', 'city')->orderBy('created_at', 'desc')->paginate(3);

        $cities = City::all()->map(function ($city) use ($company) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $company->cities->contains($city)
            ];
        });

        $tags_vacancy = Tag::all()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'active' => in_array($tag->id, session()->get('tags_vacancy', []))
            ];
        });

        $cities_vacancy = City::all()->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $city->id == session()->get('city_id_vacancy')
            ];
        });

        return view('pages.company.show', compact('company', 'vacancies', 'cities', 'tags_vacancy', 'cities_vacancy'));
    }

    public function update(Company $company, UpdateCompanyRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $this->companyService->updateCompany($company, $validatedData);
        } catch (\Throwable $e) {
            Log::error('Ошибка при обновлении компании: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error_company' => 'Ошибка при обновлении данных компании. Попробуйте снова.']);
        }

        return redirect()->route('company.show', $company->id)->with('success', 'Данные компании успешно обновлены!');
    }

    public function generateSecretCode(User $user, Company $company): RedirectResponse
    {

        Gate::authorize('generateCode', $company);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $secretCode = $this->companyService->generateSecretCode();
            $user->company->update(['secret_code' => $secretCode]);

            DB::commit();

            return redirect()->route('profile', $user->id)->with('success', 'Секретный код успешно сгенерирован!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка генерации секретного кода: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('profile', $user->id)
                ->withErrors(['secret_code' => 'Не удалось сгенерировать секретный код. Попробуйте снова.']);
        }
    }

    public function deleteSecretCode(User $user, Company $company)
    {
        Gate::authorize('generateCode', $company);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $user->company->update(['secret_code' => null]);

            DB::commit();

            return redirect()->route('profile', $user->id)->with('success', 'Секретный код успешно удален!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка удаления секретного кода: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('profile', $user->id)
                ->withErrors(['secret_code' => 'Не удалось удалить секретный код. Попробуйте снова.']);
        }
    }
}
