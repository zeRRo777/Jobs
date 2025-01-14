<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class VacancyLike extends Component
{

    public Vacancy $vacancy;

    public bool $isLike = false;

    public string $class = 'w-full text-sm px-4 py-2';

    public string $successMessage = '';

    public function mount(Vacancy $vacancy)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Перед тем как откликнуться на вакансию, сначала авторизуйтесь!']);
        }
        $this->vacancy = $vacancy;

        $this->isLike = $this->vacancy->userLiked->contains($user->id);
    }

    public function like()
    {
        Gate::authorize('like', $this->vacancy);

        $user = Auth::user();

        if (!$this->isLike) {
            $this->vacancy->userLiked()->attach($user->id);
            $this->isLike = true;
            $this->successMessage = 'Вы успешно откликнулись на вакансию!';
        }
    }

    public function unlike()
    {
        Gate::authorize('like', $this->vacancy);

        $user = Auth::user();

        if ($this->isLike) {
            $this->vacancy->userLiked()->detach($user->id);
            $this->isLike = false;
            $this->successMessage = 'Вы успешно отменили отклик на вакансию!';
        }
    }

    public function render()
    {
        return view('livewire.vacancy-like');
    }
}
