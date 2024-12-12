<x-layouts.app>
    <x-slot:title>Компания TEST</x-slot:title>
    <x-slot:header>Компания TEST</x-slot:header>

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
</x-layouts.app>