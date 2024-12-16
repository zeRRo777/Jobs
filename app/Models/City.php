<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_city');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_city');
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }
}
