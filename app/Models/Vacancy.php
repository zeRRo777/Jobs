<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vacancy extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'vacancy_tag');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function userLiked(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_vacancy_likes');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
