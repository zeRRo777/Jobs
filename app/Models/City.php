<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_city');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_city');
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }
    static function firstOrCreateMany(array $data): array
    {
        $cities = array_map('trim', $data);
        $cityIds = [];
        foreach ($cities as $cityName) {
            $city = new City();
            $city->name = $cityName;
            $city->save();
            $cityIds[] = $city->id;
        }
        return $cityIds;
    }
}
