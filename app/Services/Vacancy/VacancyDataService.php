<?php

namespace App\Services\Vacancy;

use App\Models\City;
use App\Models\Tag;
use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Support\Collection;

class VacancyDataService
{
    protected array $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function getAllFilterData(array $requestData): array
    {

        $this->data = $requestData;

        return [
            'cities' => $this->getFilterOptions(City::class, 'cities'),
            'tags' => $this->getFilterOptions(Tag::class, 'tags'),
            'companies' => $this->getFilterOptions(Company::class, 'companies'),
            'professions' => $this->getProfessionOptions(),
        ];
    }

    public function getSalaryRange(array $requestData): array
    {
        $stats = Vacancy::selectRaw('MIN(salary_start) as min, MAX(salary_start) as max')
            ->first();

        return [
            'minSalary' => $requestData['salary_start'] ?? $stats->min,
            'maxSalary' => $requestData['salary_end'] ?? $stats->max,
        ];
    }

    public function getVacancyRelationsData(Vacancy $vacancy): array
    {
        return [
            'tags' => $this->getTagStates($vacancy),
            'cities' => $this->getCityStates($vacancy),
            'users' => $vacancy->userLiked()->get(),
        ];
    }

    protected function getFilterOptions(string $model, string $key): Collection
    {
        return $model::whereHas('vacancies')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'active' => in_array($item->id, $this->data[$key] ?? [])
            ]);
    }

    protected function getProfessionOptions(): Collection
    {
        return Vacancy::select('title')
            ->distinct()
            ->get()
            ->map(fn($item) => [
                'id' => $item->title,
                'name' => $item->title,
                'active' => in_array($item->title, $this->data['professions'] ?? [])
            ]);
    }

    protected function getTagStates(Vacancy $vacancy): Collection
    {
        return Tag::all()->map(fn($tag) => [
            'id' => $tag->id,
            'name' => $tag->name,
            'active' => $vacancy->tags->contains($tag)
        ]);
    }

    protected function getCityStates(Vacancy $vacancy): Collection
    {
        return City::all()->map(fn($city) => [
            'id' => $city->id,
            'name' => $city->name,
            'active' => $city->id == $vacancy->city?->id
        ]);
    }
}
