<x-layouts.app>
    <x-slot:title>Все пользователи</x-slot:title>
    <x-slot:header>Все пользователи</x-slot:header>


    <div class="flex flex-col lg:flex-row lg:space-x-8 mt-5">
        <div class="w-full lg:w-1/4 mb-8 lg:mb-0">
            <x-form.form method="GET" :action="route('users')" size="">
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
                <!-- Кнопка применить фильтр -->
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Применить фильтр</x-button>
                </div>
                <div class="mt-6">
                    <x-button :href="route('users')" class="block text-center w-full text-sm px-4 py-2" type_component="link">Сбросить фильтр</x-button>
                </div>
            </x-form.form>
        </div>

        <div class="w-full lg:w-3/4">
            @if ($users->count() > 0)
            <x-user.list class="mb-5">
                @foreach ($users as $user)
                <x-user.card :user="$user" />
                @endforeach
            </x-user.list>
            {{ $users->links() }}
            @else
            <x-html.h2>Пользователей не найдено</x-html.h2>
            @endif
        </div>
    </div>

</x-layouts.app>