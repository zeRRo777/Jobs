<?php

namespace App\Services\Company;

use App\Models\City;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyService
{
    public function updateCompany(Company $company, array $data)
    {
        try {
            DB::beginTransaction();
    
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
    
            DB::commit(); 
    
            return $company;
        } catch (\Exception $e) {
            DB::rollBack(); 
            throw $e; 
        }
    }

    protected function createCities(string $citiesInput): array
    {
        $cities = explode(',', $citiesInput);
        $cities = array_map('trim', $cities);
        $cityIds = [];
        foreach ($cities as $cityName) {
            $city = new City();
            $city->name = $cityName;
            $city->save();
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
        if(Storage::disk('public')->exists($path))
        {
            Storage::disk('public')->delete($path);
        }
    }

    public function generateSecretCode(int $length = 10): string
    {
        return Str::random($length);
    }
}