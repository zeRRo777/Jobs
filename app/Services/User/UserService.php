<?php

namespace App\Services\User;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function updateUser(User $user, array $data)
    {
        $this->handleCities($user, $data);
        $this->handlePhoto($user, $data);
        $this->updateUserData($user, $data);

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
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function handleCities(User $user, array &$data): void
    {
        if (!empty($data['new_cities'])) {
            $newCityIds = City::firstOrCreateMany(explode(',', $data['new_cities']));
            $data['cities'] = array_merge($data['cities'] ?? [], $newCityIds);
        }

        $user->cities()->sync($data['cities'] ?? []);
    }

    protected function handlePhoto(User $user, array &$data): void
    {
        if ($data['delete_photo'] ?? false) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
        }

        if ($data['photo'] ?? false) {
            $path = $data['photo']->store('user_photos', 'public');
            $user->photo = $path;
        }
    }

    protected function updateUserData(User $user, array $data): void
    {
        $emailChanged = isset($data['email']) && $data['email'] !== $user->email;

        $user->fill(Arr::except($data, ['new_cities', 'cities', 'delete_photo']));


        if ($emailChanged) {
            $user->email_verified_at = null;
            $user->show = false;
            $user->sendNoficationAfterUpdateEmail();
        }

        $user->save();
    }
}
