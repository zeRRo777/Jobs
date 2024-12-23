<x-layouts.app>
    <x-slot:title>Вакансии</x-slot:title>
    <x-slot:header>Все вакансии</x-slot:header>

    <!-- Контейнер для фильтра и карточек вакансий -->
    <div class="flex flex-col lg:flex-row lg:space-x-8 mt-5">
        <!-- Фильтр -->
        <div class="w-full lg:w-1/4 mb-8 lg:mb-0">

            <x-form.form method="GET" action="{{ route('vacancies') }}" size="">
                <x-html.h3>Умный фильтр</x-html.h3>

                <x-form.errors />

                @livewire('multiple-select', [
                'label' => 'Города',
                'name' => 'cities',
                'data' => $cities,
                ])

                @livewire('multiple-select', [
                'label' => 'Профессии',
                'name' => 'professions',
                'data' => $professions,
                ])

                @livewire('multiple-select', [
                'label' => 'Теги',
                'name' => 'tags',
                'data' => $tags,
                ])

                @livewire('multiple-select', [
                'label' => 'Компании',
                'name' => 'companies',
                'data' => $companies,
                ])

                <!-- Фильтр по зарплате -->
                <div class="flex flex-row lg:flex-col xl:flex-row space-x-4 lg:space-x-0 lg:space-y-4 xl:space-y-0 xl:space-x-4">
                    <div class="flex-1">
                        <x-form.input-group label="Зарплата от">
                            <x-form.input type="number" name="salary_start" min="0" :value="$minSalary" />
                        </x-form.input-group>
                    </div>
                    <div class="flex-1">
                        <x-form.input-group label="Зарплата до">
                            <x-form.input type="number" name="salary_end" min="0" :value="$maxSalary" />
                        </x-form.input-group>
                    </div>
                </div>

                <!-- Кнопка применить фильтр -->
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Применить фильтр</x-button>
                </div>
                <div class="mt-6">
                    <x-button :href="route('vacancies')" class="block text-center w-full text-sm px-4 py-2" type_component="link">Сбросить фильтр</x-button>
                </div>
            </x-form.form>
        </div>

        <!-- Карточки вакансий -->
        <div class="w-full lg:w-3/4">
            @if ($vacancies->count() > 0)
            <x-vacancy.list class="mb-5">
                @foreach ($vacancies as $vacancy)
                <x-vacancy.card :vacancy="$vacancy" />
                @endforeach
            </x-vacancy.list>
            {{ $vacancies->links() }}
            @else
            <x-html.h2>Вакансий не найдено</x-html.h2>
            @endif
        </div>
    </div>

</x-layouts.app>