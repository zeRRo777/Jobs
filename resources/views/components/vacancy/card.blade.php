@props(['vacancy' => null])

@if ($vacancy)
<div class="bg-gray-800 p-6 rounded-lg hover:scale-105 transform transition h-full">
    <a wire:navigate href="{{ route('vacancy.show', $vacancy->id) }}" class="block">
        <x-html.h3 class="overflow-hidden overflow-ellipsis text-xl" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical;">
            {{ $vacancy->title }}
        </x-html.h3>
        <x-html.p class="mb-2">Компания: {{ $vacancy->company->name }}</x-html.p>
        <x-html.p class="mb-2">Город:
            @if (!empty($vacancy->city))
            {{ $vacancy->city->name }}
            @else
            Не указан
            @endif
        </x-html.p>
        <x-html.p class="mb-2">
            Зарплата:
            @if (!empty($vacancy->salary_start) && !empty($vacancy->salary_end))
            от {{ $vacancy->salary_start . ' до ' . $vacancy->salary_end}} руб.
            @elseif(!empty($vacancy->salary_start))
            от {{ $vacancy->salary_start }} руб.
            @elseif(!empty($vacancy->salary_end))
            до {{ $vacancy->salary_end }} руб.
            @else
            Не указано
            @endif
        </x-html.p>
    </a>
    <div class="mt-2 flex flex-wrap gap-2">
        @foreach ($vacancy->tags as $tag)
        <x-vacancy.tag :tag="$tag" />
        @endforeach
    </div>
    @can('like', $vacancy)
    <livewire:vacancy-like :vacancy="$vacancy" />
    @endcan
</div>
@endif