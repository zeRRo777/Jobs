<?php

namespace App\Services\Vacancy;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VacancyFilterService
{
    protected array $data = [];
    protected Builder $query;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->query = Vacancy::query()->with([
            'tags',
            'city',
            'company',
            'userLiked' => function ($query) {
                $query->where('user_id', Auth::id());
            }
        ]);
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

    public function getFilteredVacancies(): Builder
    {
        $this->applyAllFilters()
            ->applySorting();

        return $this->query;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    protected function applyAllFilters(): self
    {
        $filters = [
            'cities' => 'applyCityFilter',
            'tags' => 'applyTagFilter',
            'professions' => 'applyProfessionFilter',
            'companies' => 'applyCompanyFilter',
            'salary_start' => 'applySalaryFilter',
            'salary_end' => 'applySalaryFilter',
        ];


        foreach ($filters as $key => $method) {
            if (!empty($this->data[$key])) {
                $this->$method();
            }
        }

        return $this;
    }

    protected function applySorting(): self
    {
        $sortColumn = isset($this->data['sort_salary_start']) ? 'salary_start' : 'created_at';
        $sortDirection = $this->data['sort_salary_start'] ?? 'desc';

        $this->query->orderBy($sortColumn, $sortDirection);

        return $this;
    }
}
