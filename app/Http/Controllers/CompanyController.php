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
        $companies = $this->companyService->getPopularCompanies();

        return view('pages.index', ['companies' => $companies]);
    }

    public function index(SmartFilterCompaniesRequest $request): View
    {
        $validatedData = $request->validated();

        $companies = $this->companyFilterService->getFilteredCompanies($validatedData, $request->query());
        $cities = $this->companyFilterService->getFilterCityData();
        $companiesFilter = $this->companyFilterService->getFilterCompanyData();

        return view('pages.company.index', compact('companies', 'cities', 'companiesFilter'));
    }

    public function show(Company $company): View
    {
        $data = $this->companyService->getCompanyDetails($company);

        return view('pages.company.show', $data);
    }

    public function update(Company $company, UpdateCompanyRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        Log::info('Начало обновление компании с ID: ' . $company->id . ' и пользователя с ID: ' . Auth::id());

        try {
            $this->companyService->updateCompany($company, $validatedData);

            DB::commit();

            Log::info('Обновлениеко компании прошло успешно с ID: ' . $company->id);

            return redirect()->route('company.show', $company->id)->with('success', 'Данные компании успешно обновлены!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при обновлении компании: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('company.show', $company->id)->with('error', 'Ошибка при обновлении данных компании!');
        }
    }

    public function generateSecretCode(User $user, Company $company): RedirectResponse
    {
        Gate::authorize('generateCode', $company);

        DB::beginTransaction();

        Log::info('Начало генерации кода для кмопании с ID: ' . $company->id . ' и пользователя с ID: ' . $user->id);

        try {
            $this->companyService->generateSecretCode($company);

            Log::info('Сгенерированный код добавлен компании с ID: ' . $company->id);

            DB::commit();

            return redirect()->route('profile', $user->id)->with('success', 'Секретный код успешно сгенерирован!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка генерации секретного кода: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('profile', $user->id)->with('error', 'Ошибка при генерации секретного кода!');
        }
    }

    public function deleteSecretCode(User $user, Company $company)
    {
        Gate::authorize('generateCode', $company);

        DB::beginTransaction();

        Log::info('Начало удаления секретного кода для кмопании с ID: ' . $company->id . ' и пользователя с ID: ' . $user->id);

        try {
            $this->companyService->deleteSecretCode($company);

            Log::info('Сгенерированный код удален у компании с ID: ' . $company->id);

            DB::commit();

            return redirect()->route('profile', $user->id)->with('success', 'Секретный код успешно удален!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка удаления секретного кода: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('profile', $user->id)->with('error', 'Ошибка при удалении секретного кода!');
        }
    }
}
