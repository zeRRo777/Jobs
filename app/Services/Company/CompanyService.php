<?php

namespace App\Services\Company;

use App\Models\City;
use App\Models\Company;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyService
{
    public function getPopularCompanies(): Collection
    {
        return Company::with('cities')->withCount('vacancies')
            ->orderBy('vacancies_count', 'desc')
            ->take(10)
            ->get();
    }

    public function getCompanyDetails(Company $company): array
    {

        $vacancies = $company->vacancies()
            ->with(['tags', 'company', 'city'])
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        $allCities = Cache::remember('all_cities', 3600, function () {
            return City::all();
        });

        $allTags = Cache::remember('all_tags', 3600, function () {
            return Tag::all();
        });

        $cities = $allCities->map(function ($city) use ($company) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $company->cities->contains('id', $city->id)
            ];
        });

        $tags_vacancy = $allTags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'active' => in_array($tag->id, session()->get('tags_vacancy', []))
            ];
        });

        $cities_vacancy = $allCities->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'active' => $city->id == session()->get('city_id_vacancy')
            ];
        });

        return compact('company', 'vacancies', 'cities', 'tags_vacancy', 'cities_vacancy');
    }

    public function updateCompany(Company $company, array $data)
    {

        if (!empty($data['new_cities'])) {
            $newCityIds = $this->createCities($data['new_cities']);
            $data['cities'] = array_merge($data['cities'] ?? [], $newCityIds);
        }

        $data['cities'] = $data['cities'] ?? [];
        $company->cities()->sync($data['cities']);

        if (isset($data['delete_photo']) && $data['delete_photo'] == 'on') {
            $this->deletePhoto($company->photo);
            $company->photo = null;
        }

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $path = $this->uploadPhoto($company, $data['photo']);
            if ($path) {
                $data['photo'] = $path;
            } else {
                throw new \Exception('Не удалось сохранить фото. Попробуйте снова.');
            }
        }

        unset($data['new_cities'], $data['cities'], $data['company_id'], $data['delete_photo']);
        $company->update($data);
    }

    protected function createCities(string $citiesInput): array
    {
        $cities = explode(',', $citiesInput);
        $cities = array_map('trim', $cities);
        $cityIds = [];
        foreach ($cities as $cityName) {
            $city = City::create(['name' => $cityName]);
            $cityIds[] = $city->id;
        }
        return $cityIds;
    }

    protected function uploadPhoto(Company $company, UploadedFile $newPhoto): string|bool
    {
        if ($company->photo) {
            $this->deletePhoto($company->photo);
        }

        return $newPhoto->store('company_photos', 'public');
    }

    protected function deletePhoto(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function generateSecretCode(Company $company, int $length = 10): void
    {
        $secretCode = Str::random($length);
        $company->update(['secret_code' => $secretCode]);
    }

    public function deleteSecretCode(Company $company): void
    {
        $company->update(['secret_code' => null]);
    }
}
