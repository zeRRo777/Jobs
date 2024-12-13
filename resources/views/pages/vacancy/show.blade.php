<x-layouts.app>
    <x-slot:title>ЭлРос Программист разработчик</x-slot:title>
    <x-slot:header>Программист разработчик</x-slot:header>

    <div class="flex justify-end mb-4">
        <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
    </div>

    <!-- <x-logo class="h-52 w-52 mx-auto" /> -->

    <x-html.h3 class="mb-2">Зарплата от: 50,000 рублей</x-html.h3>
    <x-html.h3 class="mb-2">Город: Ковров</x-html.h3>
    <x-html.h3>Информация о вакансии:</x-html.h3>
    <x-html.p class="mb-2">
        Информация о компании A. Это описание компании,
        которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
    </x-html.p>
    <div class="flex flex-row items-center space-x-4 mb-2">
        <x-html.h3>Компания: ЭлРос</x-html.h3>
        <x-logo class="block h-8 w-8" />
    </div>

    <x-html.p class="mb-2">
        Информация о компании A. Это описание компании,
        которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
    </x-html.p>

    <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Откликнуться</x-button>

    <x-html.h2>Редактирование</x-html.h2>
    <x-form size="">

        <x-form.input-group label="Название вакансии">
            <x-form.input type="text" name="name" placeholder="Название вакансии" value="Программист разработчик" />
            <x-form.error>name</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Описание вакансии">
            <x-form.textarea name="description" placeholder="Описание вакансии">
                Информация о компании A. Это описание компании,которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
            </x-form.textarea>
            <x-form.error>description</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Город">
            <x-form.input type="text" name="city" placeholder="Название городов" value="Ковров" />
            <x-form.error>city</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Название компании">
            <x-form.input type="text" name="company" placeholder="Название компании" value="ЭлРос" />
            <x-form.error>company</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Описание компании">
            <x-form.textarea name="description_company" placeholder="Описание компании">
                Информация о компании A. Это описание компании,которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
            </x-form.textarea>
            <x-form.error>description</x-form.error>
        </x-form.input-group>

        <x-form.input-group label="Загрузить файл">
            <x-form.input type="file" name="file" />
            <x-form.error>file</x-form.error>
        </x-form.input-group>

        <div class="mt-6">
            <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
        </div>

    </x-form>

</x-layouts.app>