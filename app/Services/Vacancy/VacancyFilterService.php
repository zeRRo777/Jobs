<?php

namespace App\Services\Vacancy;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class VacancyFilterService
{
    protected array $data = [];
    protected Builder $query;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->query = Vacancy::query();
    }

    public function applyFilters(): Builder
    {
        $this->applyCityFilter();
        $this->applyTagFilter();
        $this->applyProfessionFilter();
        $this->applyCompanyFilter();
        $this->applySalaryFilter();
        return $this->query;
    }

    protected function applyCityFilter(): void
    {
        if (!empty($this->data['cities'])) {
            $this->query->whereIn('city_id', $this->data['cities']);
        }
    }

    protected function applyTagFilter(): void
    {
        if (!empty($this->data['tags'])) {
            $tags = $this->data['tags'];
            $this->query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('id', $tags);
            });
        }
    }

    protected function applyProfessionFilter(): void
    {
        if (!empty($this->data['professions'])) {
            $this->query->whereIn('title', array_values($this->data['professions']));
        }
    }

    protected function applyCompanyFilter(): void
    {
        if (!empty($this->data['companies'])) {
            $this->query->whereIn('company_id', $this->data['companies']);
        }
    }

    protected function applySalaryFilter(): void
    {
        if (!empty($this->data['salary_start'])) {
            $this->query->where(function ($subQuery) {
                $subQuery->where('salary_start', '>=', $this->data['salary_start'])
                    ->orWhereNull('salary_start');
            });
        }
        if (!empty($this->data['salary_end'])) {
            $this->query->where(function ($subQuery) {
                $subQuery->where('salary_end', '<=', $this->data['salary_end'])
                    ->orWhereNull('salary_end');
            });
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getFilterData($model, $key): Collection
    {
        return $model::whereHas('vacancies')
            ->pluck('name', 'id')->unique()
            ->map(function ($name, $id) use ($key) {
                return [
                    'id' => $id,
                    'name' => $name,
                    'active' => in_array($id, $this->data[$key] ?? []),
                ];
            });
    }

    public function getProfessionFilterData(): Collection
    {
        return Vacancy::select('title')
            ->distinct()
            ->pluck('title')
            ->map(function ($name) {
                return [
                    'id' => $name,
                    'name' => $name,
                    'active' => in_array($name, $this->data['professions'] ?? []),
                ];
            });
    }
}
