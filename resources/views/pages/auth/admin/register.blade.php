<x-layouts.app>
    <x-slot:title>Регистрация</x-slot:title>
    <x-slot:menu>
        <x-menu.link href="#">Пункт 1</x-menu.link>
        <x-menu.link href="#">Пункт 2</x-menu.link>
        <x-menu.link href="#">Пункт 3</x-menu.link>
        <x-menu.link href="#">Пункт 4</x-menu.link>
    </x-slot:menu>
    <x-slot:navigate_buttons>
        <x-button class="text-sm px-4 py-2" :href="route('admin.register')" type_component="link">Регистрация для админа</x-button>
        <x-button class="text-sm px-4 py-2" :href="route('login')" type_component="link">Войти</x-button>
    </x-slot:navigate_buttons>
    <x-slot:header>Регистрация для админов</x-slot:header>

    <x-form.form action="" method="POST">
        @csrf
        <div>
            <x-form.label for="name">
                Имя
            </x-form.label>
            <x-form.input type="text" id="name" name="name" placeholder="Введите име" value="{{ old('name') }}" />
            <x-form.error>name</x-form.error>
        </div>

        <div>
            <x-form.label for="email">
                Почта
            </x-form.label>
            <x-form.input type="email" id="email" name="email" placeholder="Введите почту" value="{{ old('email') }}" />
            <x-form.error>email</x-form.error>
        </div>

        <div>
            <x-form.label for="company">
                Название компании или код(если хотите присоединиться к существующей компании)
            </x-form.label>
            <x-form.input type="text" id="company" name="company" placeholder="Введите навзание компании или код" value="{{ old('company') }}" />
            <x-form.error>company</x-form.error>
        </div>

        <div>
            <x-form.label for="password">
                Пароль
            </x-form.label>
            <x-form.input type="email" id="email" name="email" placeholder="Введите пароль" value="{{ old('password') }}" />
            <x-form.error>password</x-form.error>
        </div>
        <div>
            <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Зарегистрироваться</x-button>
        </div>

    </x-form.form>

</x-layouts.app>