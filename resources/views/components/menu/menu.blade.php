<!-- Меню -->
<div id="nav-content" class="w-full flex-grow lg:flex lg:items-center lg:w-auto">
    <div class="text-2xl lg:flex-grow">
        <x-menu.link href="#">Пункт 1</x-menu.link>
        <x-menu.link href="#">Пункт 2</x-menu.link>
        <x-menu.link href="#">Пункт 3</x-menu.link>
        <x-menu.link href="#">Пункт 4</x-menu.link>
    </div>

    <!-- Кнопки авторизации -->
    <div class="flex items-center space-x-4 mt-4 lg:mt-0">
        <!-- Если пользователь не авторизован -->

        <x-button class="text-sm px-4 py-2" href="#" _type="link">Войти</x-button>
        <x-button class="text-sm px-4 py-2" href="#" _type="link">Зарегистрироваться</x-button>

        <!-- Если пользователь авторизован -->

        <!-- <x-button class="text-sm px-4 py-2" href="#" _type="link">Профиль</x-button> -->

    </div>
</div>