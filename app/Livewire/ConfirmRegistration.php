<?php

namespace App\Livewire;

use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ConfirmRegistration extends Component
{
    public function verify()
    {
        $user = Auth::user();

        try {
            Mail::to($user)->send(new RegistrationMail($user));

            $this->dispatch('notify', 'Письмо успешно отправлено!');
        } catch (\Exception $e) {

            $this->dispatch('notify', 'Произошла ошибка при отправке письма. Попробуйте еще раз!');

            Log::error('Ошибка отправки письма: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.confirm-registration');
    }
}
