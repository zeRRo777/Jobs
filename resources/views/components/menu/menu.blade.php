<!-- Меню -->
<div id="nav-content" class="w-full flex-grow lg:flex lg:items-center lg:w-auto">

    <div class="text-lg lg:flex-grow">
        <x-menu.link :href="route('main')">Главная</x-menu.link>
        <x-menu.link :href="route('about')">О нас</x-menu.link>

        <x-menu.link href="#">Все вакансии</x-menu.link>
    </div>

    <div class="flex items-center space-x-4 mt-4 lg:mt-0">
        @if (!empty($navigate_buttons))
        {{ $navigate_buttons }}
        @else
        <x-button class="text-sm px-4 py-2" :href="route('login')" type_component="link">Войти</x-button>
        <x-button class="text-sm px-4 py-2" :href="route('register')" type_component="link">Зарегистрироваться</x-button>
        <!-- <x-button class="text-sm px-4 py-2" href="#" type_component="link">Профиль</x-button> -->
        @endif
    </div>
</div>