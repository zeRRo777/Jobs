<x-layouts.app>
    <x-slot:title>Вакансии</x-slot:title>
    <x-slot:header>Все вакансии</x-slot:header>

    <x-form.form size="">
        <div class="flex space-x-2">
            <x-form.input type="text" name="q" placeholder="Введите город, профессию или компанию и мы постараемся найти вакансии" />
            <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Найти</x-button>
        </div>
    </x-form.form>

    <!-- Контейнер для фильтра и карточек вакансий -->
    <div class="flex flex-col lg:flex-row lg:space-x-8 mt-5">
        <!-- Фильтр -->
        <div class="w-full lg:w-1/4 mb-8 lg:mb-0">

            <x-form.form method="GET" action="" size="">
                <x-html.h3>Умный фильтр</x-html.h3>

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
                            <x-form.input type="number" name="salary_from" min="0" />
                        </x-form.input-group>
                    </div>
                    <div class="flex-1">
                        <x-form.input-group label="Зарплата до">
                            <x-form.input type="number" name="salary_to" min="0" />
                        </x-form.input-group>
                    </div>
                </div>

                <!-- Кнопка применить фильтр -->
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Применить фильтр</x-button>
                </div>
            </x-form.form>
        </div>

        <!-- Карточки вакансий -->
        <div class="w-full lg:w-3/4">
            <x-vacancy.list class="mb-5">
                @foreach ($vacancies as $vacancy)
                <x-vacancy.card :vacancy="$vacancy" />
                @endforeach
            </x-vacancy.list>
            {{ $vacancies->links() }}
        </div>
    </div>

</x-layouts.app>