<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class AddAdminCompanyInput extends Component
{

    public string $inputType = 'company';

    public string $inputValue = '';

    public function mount(string $value): void
    {
        $this->inputValue = $value;
    }

    public function switchInputType(): void
    {
        $this->inputType = $this->inputType === 'company' ? 'secret_code' : 'company';

        $this->inputValue = '';
    }

    public function render(): View
    {
        return view('livewire.add-admin-company-input');
    }
}
