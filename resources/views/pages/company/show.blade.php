<x-layouts.app>
    <x-slot:title>Компания TEST</x-slot:title>
    <x-slot:header>Компания TEST</x-slot:header>

    <div class="flex justify-end mb-4">
        <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
    </div>


    <x-html.h2>О нас</x-html.h2>
    <x-logo class="h-52 w-52 mx-auto" />
    <x-html.h3>Город: Ковров</x-html.h3>
    <x-html.p class="mb-10">
        Информация о компании A. Это описание компании,
        которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
    </x-html.p>

    <x-html.h2>Вакансии</x-html.h2>

    <x-vacancy.list>
        <x-vacancy.card vacancy="gg" />
        <x-vacancy.card vacancy="gg" />
        <x-vacancy.card vacancy="gg" />
        <x-vacancy.card vacancy="gg" />
    </x-vacancy.list>

    <x-html.h2>Редактирование</x-html.h2>
    <x-form size="">

        <x-form.input-group label="Название">
            <x-form.input type="text" name="company" placeholder="Название компании" value="Название" />
            <x-form.error field="company" />
        </x-form.input-group>

        <x-form.input-group label="Город(вы можете написать несколько городов через заяпятую)">
            <x-form.input type="text" name="city" placeholder="Название городов" value="Ковров, Владимир" />
            <x-form.error field="city" />
        </x-form.input-group>

        <x-form.input-group label="Информация о компании">
            <x-form.textarea name="about" placeholder="Информация о компании">
                Информация о компании A. Это описание компании,которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
            </x-form.textarea>
            <x-form.error field="about" />
        </x-form.input-group>

        <x-form.input-group label="Загрузить файл">
            <x-form.input type="file" name="file" />
            <x-form.error field="file" />
        </x-form.input-group>

        <div class="mt-6">
            <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
        </div>

    </x-form>
</x-layouts.app>