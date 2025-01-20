<?php

namespace App\Livewire;

use App\Mail\OfferVacancy;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class OfferJob extends Component
{

    public Vacancy $vacancy;

    public User $user;
    public User $admin;

    public bool $isOffer = false;

    public function mount(User $user, Vacancy $vacancy)
    {

        $this->admin = Auth::user();

        if (!$this->admin) {
            return redirect()->route('login')->withErrors(['error' => 'Перед тем как предложить работу на вакансию, сначала авторизуйтесь!']);
        }

        $this->user = $user;
        $this->vacancy = $vacancy;
        $this->isOffer = $user->offeredVacancies->contains($vacancy->id);
    }

    public function offer()
    {
        Gate::authorize('offer', $this->vacancy);

        try {
            Mail::to($this->user)->send(new OfferVacancy($this->vacancy, $this->user, $this->admin));
            $this->dispatch('notify', 'Письмо успешно отправлено!');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Произошла ошибка при отправке письма. Попробуйте еще раз!');
            Log::error('Ошибка отправки письма: ' . $e->getMessage());
            return;
        }

        if (!$this->isOffer) {
            try {
                $this->user->offeredVacancies()->attach($this->vacancy->id);
                $this->isOffer = true;
            } catch (\Exception $e) {
                $this->dispatch('notify', 'Произошла ошибка при отправке письма. Попробуйте еще раз!');
                Log::error('Ошибка отправки письма: ' . $e->getMessage());
                return;
            }
        }
    }

    public function render()
    {
        return view('livewire.offer-job');
    }
}
