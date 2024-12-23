<x-layouts.app>
    <x-slot:title>Компании</x-slot:title>
    <x-slot:header>Все компании</x-slot:header>

    <!-- Контейнер для фильтра и карточек вакансий -->
    <div class="flex flex-col lg:flex-row lg:space-x-8 mt-5">
        <!-- Фильтр -->
        <div class="w-full lg:w-1/4 mb-8 lg:mb-0">

            <x-form.form method="GET" action="{{ route('companies') }}" size="">
                <x-html.h3>Умный фильтр</x-html.h3>

                <x-form.errors />

                @livewire('multiple-select', [
                'label' => 'Компании',
                'name' => 'companies',
                'data' => $companiesFilter,
                ])

                @livewire('multiple-select', [
                'label' => 'Города',
                'name' => 'cities',
                'data' => $cities,
                ])

                <!-- Кнопка применить фильтр -->
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Применить фильтр</x-button>
                </div>
                <div class="mt-6">
                    <x-button :href="route('companies')" class="block text-center w-full text-sm px-4 py-2" type_component="link">Сбросить фильтр</x-button>
                </div>
            </x-form.form>
        </div>

        <!-- Карточки вакансий -->
        <div class="w-full lg:w-3/4">
            @if ($companies->count() > 0)
            <x-company.list class="mb-5">
                @foreach ($companies as $company)
                <x-company.card :company="$company" />
                @endforeach
            </x-company.list>
            {{ $companies->links() }}
            @else
            <x-html.h2>Компаний не найдено</x-html.h2>
            @endif
        </div>
    </div>

</x-layouts.app>