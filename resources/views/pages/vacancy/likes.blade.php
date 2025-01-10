<x-layouts.app>
    <x-slot:title>Понравившиеся вакансии</x-slot:title>
    <x-slot:header>Понравившиеся вакансии</x-slot:header>

    <!-- Карточки вакансий -->
    <div class="w-full @if ($vacancies->count() === 0) 'text-center' @endif">
        @if ($vacancies->count() > 0)
        <x-vacancy.list class="mb-5">
            @foreach ($vacancies as $vacancy)
            <x-vacancy.card :vacancy="$vacancy" />
            @endforeach
        </x-vacancy.list>
        {{ $vacancies->links() }}
        @else
        <x-html.h2>Вы пока не откликнулись на вакансии!</x-html.h2>
        <x-button :href="route('vacancies')" class="inline-block text-sm px-4 py-2" type_component="link">Все вакансии</x-button>
        @endif
    </div>
</x-layouts.app>