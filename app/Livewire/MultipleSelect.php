<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class MultipleSelect extends Component
{
    public string $label;
    public string $name;
    public Collection $data;
    public string $query = '';
    public string $type = 'checkbox';

    public function mount($label, $name, Collection $data, $type = 'checkbox'): void
    {
        $this->label = $label;
        $this->name = $name;
        $this->data = $data;
        $this->type = $type;
    }

    public function render(): View
    {
        $filteredData = $this->query
            ? $this->data->filter(function ($value) {
                return stripos(mb_strtolower($value['name']), mb_strtolower($this->query)) !== false;
            })
            : $this->data;

        return view('livewire.multiple-select', [
            'filteredData' => $filteredData,
        ]);
    }
}
