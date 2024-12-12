@props(['vacancy' => null])

@if ($vacancy)

<div>
    <a href="#" class="block bg-gray-800 p-6 rounded-lg hover:scale-105 transform transition">
        <x-html.h3 class="overflow-hidden overflow-ellipsis text-xl" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical;">Программист-Разработчик</x-html.h3>
        <!-- <h2 class="text-xl font-bold mb-2 overflow-hidden overflow-ellipsis" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical;">Программист-Разработчик</h2> -->
        <x-html.p class="mb-2">Компания: Название</x-html.p>
        <x-html.p class="mb-2">Город: Город</x-html.p>
        <x-html.p class="mb-2">Зарплата: 50,000 руб.</x-html.p>
        <!-- <p class="text-gray-400">Компания: Название</p>
        <p class="text-gray-400">Город: Город</p>
        <p class="text-gray-400">Зарплата: 50,000 руб.</p> -->

        <form>
            <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Откликнуться</x-button>
        </form>
    </a>
</div>

@endif