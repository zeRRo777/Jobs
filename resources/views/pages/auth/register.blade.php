<x-layouts.app>
    <x-slot:title>Регистрация</x-slot:title>
    <x-slot:navigate_buttons>
        <x-button class="text-sm px-4 py-2" :href="route('admin.register')" type_component="link">Регистрация для админа</x-button>
        <x-button class="text-sm px-4 py-2" :href="route('login')" type_component="link">Войти</x-button>
    </x-slot:navigate_buttons>
    <x-slot:header>Регистрация</x-slot:header>

    <x-form.form action="" method="POST" class="mx-auto">
        @csrf
        <x-form.input-group label="Имя">
            <x-form.input type="text" name="name" placeholder="Введите имя" value="{{ old('name') }}" />
            <x-form.error>name</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Почта">
            <x-form.input type="email" name="email" placeholder="Введите почту" value="{{ old('email') }}" />
            <x-form.error>email</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Пароль">
            <x-form.input type="password" name="password" placeholder="Введите пароль" value="{{ old('password') }}" />
            <x-form.error>password</x-form.error>
        </x-form.input-group>

        <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Зарегистрироваться</x-button>
    </x-form.form>

</x-layouts.app>