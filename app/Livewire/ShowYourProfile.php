<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowYourProfile extends Component
{
    public bool $checked = false;

    public function mount()
    {
        $this->checked = Auth::user()->show;
    }

    public function updatedChecked(bool $value)
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

    public function render()
    {
        return view('livewire.show-your-profile');
    }
}
