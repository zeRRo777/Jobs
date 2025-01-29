<?php

namespace App\Services\User;

use App\Mail\OfferVacancy;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class UserVacancyService
{
    public function sendOffers(User $user, array $vacancyIds): void
    {
        foreach ($vacancyIds as $vacancyId) {

            $vacancy = Vacancy::findOrFail($vacancyId);

            Gate::authorize('offer', $vacancy);

            Mail::to($user)->send(new OfferVacancy($vacancy, $user, Auth::user()));

            if (!$vacancy->offeredUsers()->where('user_id', $user->id)->exists()) {

                $vacancy->offeredUsers()->attach($user->id);
            }
        }
    }
}
