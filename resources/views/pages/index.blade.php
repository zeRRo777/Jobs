<x-layouts.app>
    <x-slot:title>Главная</x-slot:title>
    <x-slot:menu>
        <x-menu.link href="#">Пункт 1</x-menu.link>
        <x-menu.link href="#">Пункт 2</x-menu.link>
        <x-menu.link href="#">Пункт 3</x-menu.link>
        <x-menu.link href="#">Пункт 4</x-menu.link>
    </x-slot:menu>
    <x-slot:navigate_buttons>
        <x-button class="text-sm px-4 py-2" :href="route('login')" type_component="link">Войти</x-button>
        <x-button class="text-sm px-4 py-2" :href="route('register')" type_component="link">Зарегистрироваться</x-button>
    </x-slot:navigate_buttons>
    <x-slot:header>Список компаний с наибольшим количесвом вакансий</x-slot:header>
    <x-company.list>
        <x-company.card />
        <x-company.card />
        <x-company.card />
        <x-company.card />
        <x-company.card />
        <x-company.card />
        <x-company.card />
    </x-company.list>
</x-layouts.app>