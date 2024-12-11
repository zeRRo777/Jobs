<x-layouts.app>
    <x-slot:title>Авторизация</x-slot:title>
    <x-slot:menu>
        <x-menu.link href="#">Пункт 1</x-menu.link>
        <x-menu.link href="#">Пункт 2</x-menu.link>
        <x-menu.link href="#">Пункт 3</x-menu.link>
        <x-menu.link href="#">Пункт 4</x-menu.link>
    </x-slot:menu>
    <x-slot:navigate_buttons>
        <x-button class="text-sm px-4 py-2" :href="route('register')" type_component="link">Зарегистрироваться</x-button>
    </x-slot:navigate_buttons>
    <x-slot:header>Авторизация</x-slot:header>

    <x-form.form action="" method="POST">
        @csrf
        <div>
            <x-form.label for="email">
                Почта
            </x-form.label>
            <x-form.input type="email" id="email" name="email" placeholder="Введите почту" value="{{ old('email') }}" />
            <x-form.error>email</x-form.error>
        </div>

        <div>
            <x-form.label for="password">
                Пароль
            </x-form.label>
            <x-form.input type="email" id="email" name="email" placeholder="Введите пароль" value="{{ old('password') }}" />
            <x-form.error>password</x-form.error>
        </div>
        <div>
            <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Войти</x-button>
        </div>

    </x-form.form>

</x-layouts.app>