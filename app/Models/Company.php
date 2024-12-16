<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'company_city');
    }
}
