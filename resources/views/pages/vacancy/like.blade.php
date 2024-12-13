<x-layouts.app>
    <x-slot:title>Понравившиеся Вакансии</x-slot:title>
    <x-slot:header>Понравившиеся вакансии</x-slot:header>

    <x-form.form size="">
        <div class="flex space-x-2">
            <x-form.input type="text" name="q" placeholder="Введите город, профессию, тег или компанию и мы постараемся найти вакансии" />
            <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Найти</x-button>
        </div>
    </x-form.form>

    <!-- Контейнер для фильтра и карточек вакансий -->
    <div class="flex flex-col lg:flex-row lg:space-x-8 mt-5">
        <!-- Фильтр -->
        <div class="w-full lg:w-1/4 mb-8 lg:mb-0">

            <x-form.form method="GET" action="" size="">
                <x-html.h3>Умный фильтр</x-html.h3>
                <!-- Фильтр по городу -->
                <x-form.filter-select label="Город" name="city" :options="['Москва', 'Санкт-Петербург', 'Екатеринбург', 'Новосибирск','Москва', 'Санкт-Петербург', 'Екатеринбург', 'Новосибирск','Москва', 'Санкт-Петербург', 'Екатеринбург', 'Новосибирск','Москва', 'Санкт-Петербург', 'Екатеринбург', 'Новосибирск']" />

                <!-- Фильтр по профессии -->
                <x-form.filter-select label="Профессия" name="profession" :options="['Программист', 'Дизайнер', 'Менеджер', 'Аналитик']" />

                <!-- Фильтр по тегу -->
                <x-form.filter-select label="Тег" name="tag" :options="['PHP', 'Laravel', 'Vue.js', 'JavaScript']" />

                <!-- Фильтр по компании -->
                <x-form.filter-select label="Компания" name="company" :options="['Компания А', 'Компания Б', 'Компания В', 'Компания Г']" />

                <!-- Фильтр по зарплате -->
                <div class="flex flex-row lg:flex-col xl:flex-row space-x-4 lg:space-x-0 lg:space-y-4 xl:space-y-0 xl:space-x-4">
                    <div class="flex-1">
                        <x-form.input-group label="Зарплата от">
                            <x-form.input type="number" name="salary_from" min="0" />
                        </x-form.input-group>
                    </div>
                    <div class="flex-1">
                        <x-form.input-group label="Зарплата до">
                            <x-form.input type="number" name="salary_to" min="0" />
                        </x-form.input-group>
                    </div>
                </div>

                <!-- Кнопка применить фильтр -->
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Применить фильтр</x-button>
                </div>
            </x-form.form>
        </div>

        <!-- Карточки вакансий -->
        <div class="w-full lg:w-3/4">
            <x-vacancy.list>
                <x-vacancy.card vacancy="gg" />
                <x-vacancy.card vacancy="gg" />
                <x-vacancy.card vacancy="gg" />
                <x-vacancy.card vacancy="gg" />
            </x-vacancy.list>
        </div>
    </div>

</x-layouts.app>