<?php

namespace App\Services\User;

use App\Mail\ChangeEmailMail;
use App\Mail\RegistrationMail;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function updateUser(User $user, array $data)
    {
        $emailChanged = isset($data['email']) && $data['email'] !== $user->email;

        Log::info('Начало обновления данных пользователя с ID: ' . $user->id);

        if (!empty($data['new_cities'])) {
            $newCityIds = $this->createCities($data['new_cities']);
            $data['cities'] = array_merge($data['cities'] ?? [], $newCityIds);
        }

        $data['cities'] = $data['cities'] ?? [];

        $user->cities()->sync($data['cities']);

        Log::info('Города пользователя с ID: ' . $user->id . ' успешно обновлены.');


        if (isset($data['delete_photo']) && $data['delete_photo'] == 'on') {
            $this->deletePhoto($user->photo);
            $user->photo = null;
            Log::info('Фото пользователя с ID: ' . $user->id . ' удалено.');
        }

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $path = $this->uploadPhoto($user, $data['photo']);
            if ($path) {
                $data['photo'] = $path;
                Log::info('Новое фото пользователя с ID: ' . $user->id . ' успешно загружено.');
            } else {
                Log::error('Не удалось загрузить фото для пользователя с ID: ' . $user->id);
                throw new \Exception('Не удалось сохранить фото. Попробуйте снова.');
            }
        }

        unset($data['new_cities'], $data['cities'], $data['user_id'], $data['delete_photo']);

        if ($emailChanged) {
            $data['show'] = false;
            $data['email_verified_at'] = null;
            Log::info('Email пользователя с ID: ' . $user->id . ' изменен. Требуется повторная верификация.');
        }

        $user->update($data);
        Log::info('Данные пользователя с ID: ' . $user->id . ' успешно обновлены.');

        if ($emailChanged) {
            Mail::to($user)->send(new ChangeEmailMail($user));
            Log::info('Письмо для подтверждения email отправлено пользователю с ID: ' . $user->id);
            session()->flash('warning', 'Email изменен. Письмо для подтверждения отправлено на ваш новый адрес.');
        }
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
}
