<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\ChangeEmailMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'user_city');
    }

    public function likedVacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'user_vacancy_likes');
    }

    public function offeredVacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'user_vacancy_offers');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $company = $user->company;

            if (!empty($company) && $company->users()->count() == 1) {
                $company->delete();
            }

            if (!empty($user->photo)) {
                if (Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
            }
        });

        static::updating(function (User $user) {

            if ($user->isDirty('company_id')) {
                if ($user->company_id === null) {

                    $company = Company::find($user->getOriginal('company_id'));

                    if (!empty($company) && $company->users()->count() < 2) {
                        $company->delete();
                    }
                }
            }
        });
    }

    public function sendNoficationAfterUpdateEmail(): void
    {
        Mail::to($this)->send(new ChangeEmailMail($this));
        session()->flash('warning', 'Email изменен. Письмо для подтверждения отправлено на ваш новый адрес.');
    }
}
