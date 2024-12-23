<?php

namespace App\Services\Company;

use App\Models\City;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CompanyFilterService
{
    protected $data;
    protected Builder $query;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->query = Company::query();
    }

    public function applyFilters(): Builder
    {
        $this->applyCityFilter();
        $this->applyCompanyFilter();

        return $this->query;
    }

    protected function applyCityFilter(): void
    {
        if (!empty($this->data['cities'])) {
            $this->query->whereHas('cities', function ($query) {
                $query->whereIn('cities.id', $this->data['cities']);
            });
        }
    }

    protected function applyCompanyFilter(): void
    {
        if (!empty($this->data['companies'])) {
            $this->query->whereIn('id', $this->data['companies']);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getFilterCityData(): Collection
    {
        return City::whereHas('companies')->pluck('name', 'id')->unique()->map(function ($name, $id) {
            return [
                'id' => $id,
                'name' => $name,
                'active' => in_array($id, $this->data['cities'] ?? []),
            ];
        });
    }

    public function getFilterCompanyData(): Collection
    {
        return Company::pluck('name', 'id')->map(function ($name, $id) {
            return [
                'id' => $id,
                'name' => $name,
                'active' => in_array($id, $this->data['companies'] ?? []),
            ];
        });
    }
}
