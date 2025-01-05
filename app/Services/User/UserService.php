<?php

namespace App\Services\User;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function updateUser(User $user, array $data)
    {
        if (!empty($data['new_cities'])) {
            $newCityIds = $this->createCities($data['new_cities']);
            $data['cities'] = array_merge($data['cities'] ?? [], $newCityIds);
        }

        $data['cities'] = $data['cities'] ?? [];

        $user->cities()->sync($data['cities']);


        if(isset($data['delete_photo']) && $data['delete_photo'] == 'on')
        {
            $this->deletePhoto($user->photo);
            $user->photo = null;
        }

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $path = $this->uploadPhoto($user, $data['photo']);
            if ($path) {
                $data['photo'] = $path;
            } else {
                throw new \Exception('Не удалось сохранить фото. Попробуйте снова.');
            }
        } 

        unset($data['new_cities'], $data['cities'], $data['user_id'], $data['delete_photo']);

        $user->update($data);

        return $user;
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

    protected function uploadPhoto(User $user, UploadedFile $newPhoto): string|bool
    {
        if ($user->photo) {
            $this->deletePhoto($user->photo);
        }

        return $newPhoto->store('user_photos', 'public');
    }

    protected function deletePhoto(string $path): void
    {
        if(Storage::disk('public')->exists($path))
        {
            Storage::disk('public')->delete($path);
        }
    }
}