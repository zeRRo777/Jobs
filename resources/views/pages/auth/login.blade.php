<x-layouts.app>
    <x-slot:title>Авторизация</x-slot:title>
    <x-slot:navigate_buttons>
        <x-button class="text-sm px-4 py-2" :href="route('register')" type_component="link">Зарегистрироваться</x-button>
    </x-slot:navigate_buttons>
    <x-slot:header>Авторизация</x-slot:header>

    <x-form.form action="" method="POST">
        @csrf
        <x-form.input-group label="Почта">
            <x-form.input type="email" name="email" placeholder="Введите почту" value="{{ old('email') }}" />
            <x-form.error>email</x-form.error>
        </x-form.input-group>
        <x-form.input-group label="Пароль">
            <x-form.input type="password" name="password" placeholder="Введите пароль" value="{{ old('password') }}" />
            <x-form.error>password</x-form.error>
        </x-form.input-group>

        <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Войти</x-button>

    </x-form.form>

</x-layouts.app>