<?php

namespace App\Services\User;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;


class UserFilterService
{
    protected array $data = [];
    protected Builder $query;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->query = User::query();
    }

    protected function applyCitiesFilter(): void
    {
        if (!empty($this->data['cities'])) {
            $cities = $this->data['cities'];
            $this->query->whereHas('cities', function ($q) use ($cities) {
                $q->whereIn('id', $cities);
            });
        }
    }

    protected function applyProfessionFilter(): void
    {
        if (!empty($this->data['professions'])) {
            $this->query->whereIn('profession', array_values($this->data['professions']));
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getProfessionFilterData(): Collection
    {
        return User::where('id', '!=', auth()->id())
            ->whereNotNull('profession')
            ->distinct()
            ->pluck('profession')
            ->map(
                function ($name) {
                    return [
                        'id' => $name,
                        'name' => $name,
                        'active' => in_array($name, $this->data['professions'] ?? []),
                    ];
                }
            );
    }

    public function getCitiesFilterData(): Collection
    {
        return City::whereHas('users', function ($q) {
            $q->where('id', '!=', auth()->id());
        })->pluck('name', 'id')
            ->unique()
            ->map(function ($name, $id) {
                return [
                    'id' => $id,
                    'name' => $name,
                    'active' => in_array($id, $this->data['cities'] ?? []),
                ];
            });
    }

    public function applyFilters(): Builder
    {
        $this->applyCitiesFilter();
        $this->applyProfessionFilter();
        return $this->query;
    }
}
