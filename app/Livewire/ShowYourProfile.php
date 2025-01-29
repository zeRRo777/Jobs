<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class ShowYourProfile extends Component
{
    public bool $checked = false;

    public function mount(): void
    {
        $this->checked = Auth::user()->show;
    }

    public function updatedChecked(bool $value): void
    {
        $this->checked = $value;

        $user = Auth::user();
        $user->show = $value;
        $user->save();

        if ($this->checked) {
            $this->dispatch('notify', 'Работодатель сможет просматривать ваш профиль!');
        } else {
            $this->dispatch('notify', 'Работодатель не сможет просматривать ваш профиль!');
        }
    }

    public function render(): View
    {
        return view('livewire.show-your-profile');
    }
}
