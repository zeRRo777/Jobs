<?php

namespace App\Livewire;

use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Component;

class VacancyLike extends Component
{

    public Vacancy $vacancy;

    public bool $isLike = false;

    public string $class = 'w-full text-sm px-4 py-2';

    public string $successMessage = '';

    public function mount($vacancy): void
    {
        $this->vacancy = $vacancy;

        $this->isLike = $this->vacancy->userLiked->contains(Auth::id());
    }

    public function like(): void
    {
        Gate::authorize('like', $this->vacancy);

        $user = Auth::user();

        if (!$this->isLike) {
            $this->vacancy->userLiked()->attach($user->id);
            $this->isLike = true;
            $this->successMessage = 'Вы успешно откликнулись на вакансию!';
        }
    }

    public function unlike(): void
    {
        Gate::authorize('like', $this->vacancy);

        $user = Auth::user();

        if ($this->isLike) {
            $this->vacancy->userLiked()->detach($user->id);
            $this->isLike = false;
            $this->successMessage = 'Вы успешно отменили отклик на вакансию!';
        }
    }

    public function render(): View
    {
        return view('livewire.vacancy-like');
    }
}
