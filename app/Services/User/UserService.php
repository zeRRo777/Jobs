<?php

namespace App\Services\User;

use App\Models\City;
use App\Models\User;
use App\Services\Common\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class UserService
{

    protected FileService $fileService;
    public function __construct()
    {
        $this->fileService = new FileService();
    }

    public function updateUser(User $user, array $data)
    {
        $this->handleCities($user, $data);
        $this->handlePhoto($user, $data);
        $this->updateUserData($user, $data);

        return $user;
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
        if (isset($data['delete_photo']) && $data['delete_photo'] == 'on') {

            $this->fileService->deletePhoto($user->photo);
            $user->photo = null;
        }

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $path = $this->fileService->uploadPhoto($user, $data['photo']);
            $user->photo = $path;
        }
    }

    protected function updateUserData(User $user, array $data): void
    {
        $emailChanged = isset($data['email']) && $data['email'] !== $user->email;

        $user->fill(Arr::except($data, ['new_cities', 'cities', 'delete_photo', 'user_id', 'photo']));

        if ($emailChanged) {
            $user->email_verified_at = null;
            $user->show = false;
            $user->sendNoficationAfterUpdateEmail();
        }

        $user->save();
    }
}
