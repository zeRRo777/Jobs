<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'vacancy_tag');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function userLiked()
    {
        return $this->belongsToMany(User::class, 'user_vacancy_likes');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
