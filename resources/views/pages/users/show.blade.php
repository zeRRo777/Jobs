<x-layouts.app>
    <x-slot:title>{{ $user->name . ' page' }}</x-slot:title>
    <x-slot:header>{{ $user->name }}</x-slot:header>

    @if (!empty($user->photo))
    <img src="{{ asset('storage/' . $user->photo) }}" alt="Лого компании {{ $user->name }}" class="h-52 w-52 mx-auto mb-4">
    @endif

    <x-html.h3 class="mb-2">Почта: {{ $user->email }}</x-html.h3>
    @if (!empty($user->phone))
    <x-html.h3 class="mb-2">Профессия: {{ $user->profession }}</x-html.h3>
    @endif
    <x-html.h3 class="mb-2">
        @if ($user->cities->count() > 0)
        @if ($user->cities->count() == 1)
        Город: {{ $user->cities->first()->name }}
        @else
        Города:
        @foreach ($user->cities as $city)
        @if ($loop->last)
        {{ $city->name }}.
        @else
        {{ $city->name }},
        @endif
        @endforeach
        @endif
        @else
        Город: Не указан
        @endif
    </x-html.h3>

    @if(!empty($user->resume))
    <x-html.h3 class="mb-2">Резюме:</x-html.h3>
    <x-html.p class="mb-4">{{ $user->resume }}</x-html.p>
    @endif
    <x-button type_component="button" class="text-sm px-4 py-2">Предложить работу</x-button>
</x-layouts.app>