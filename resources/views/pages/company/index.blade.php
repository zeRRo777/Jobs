<x-layouts.app>
    <x-slot:title>Компании</x-slot:title>
    <x-slot:header>Все компании</x-slot:header>

    <x-form.form size="">
        <div class="flex space-x-2">
            <x-form.input type="text" name="q" placeholder="Введите профессию,город, тег или компанию и мы постараемя их найти" />
            <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Найти</x-button>
        </div>
    </x-form.form>

    <!-- Контейнер для фильтра и карточек вакансий -->
    <div class="flex flex-col lg:flex-row lg:space-x-8 mt-5">
        <!-- Фильтр -->
        <div class="w-full lg:w-1/4 mb-8 lg:mb-0">

            <x-form.form method="GET" action="" size="">
                <x-html.h3>Умный фильтр</x-html.h3>

                <x-form.input-group label="Компания">
                    <x-form.input type="text" name="company" placeholder="Название компании" value="" />
                    <x-form.error>company</x-form.error>
                </x-form.input-group>

                <x-form.input-group label="Город">
                    <x-form.input type="text" name="city" placeholder="Название города" value="" />
                    <x-form.error>city</x-form.error>
                </x-form.input-group>

                <!-- Кнопка применить фильтр -->
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Применить фильтр</x-button>
                </div>
            </x-form.form>
        </div>

        <!-- Карточки вакансий -->
        <div class="w-full lg:w-3/4">
            <x-company.list>
                <x-company.card />
                <x-company.card />
                <x-company.card />
                <x-company.card />
                <x-company.card />
                <x-company.card />
                <x-company.card />
            </x-company.list>
        </div>
    </div>

</x-layouts.app>