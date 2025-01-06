<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'company_city');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (Company $company) {
            
            if(!empty($company->photo))
            {
                if(Storage::disk('public')->exists($company->photo))
                {
                    Storage::disk('public')->delete($company->photo);
                }
            }
        });
    }
}
