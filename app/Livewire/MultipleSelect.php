<?php

namespace App\Livewire;

use Livewire\Component;

class MultipleSelect extends Component
{
    public $label;
    public $name;
    public array $data = [];
    public $query = '';



    public function mount($label, $name, $data = [])
    {
        $this->label = $label;
        $this->name = $name;
        $this->data = $data;
    }

    public function render()
    {
        $filteredData = $this->query
            ? collect($this->data)->filter(function ($value) {
                return stripos(mb_strtolower($value), mb_strtolower($this->query)) !== false;
            })
            : $this->data;

        return view('livewire.multiple-select', [
            'filteredData' => $filteredData,
        ]);
    }
}
