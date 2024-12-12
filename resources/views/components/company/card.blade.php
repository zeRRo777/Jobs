@props(['company' => 'test', 'number' => null])
@if ($company)
<a href="#" class="block">
    <div class="relative bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transform hover:scale-105 transition duration-300">
        <div class="flex flex-row justify-between items-center mb-2">
            @if ($number)
            <div>
                <span class="text-4xl font-bold text-white opacity-90">{{ $number }}</span>
            </div>
            @endif
            <!-- Логотип компании -->
            <img src="{{ asset('images/logo.png') }}" alt="Логотип компании А" class="h-16 w-16">
        </div>
        <!-- Название компании -->
        <x-html.h3 class="overflow-hidden overflow-ellipsis text-xl" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical;">Компания А</x-html.h3>
        <x-html.p class="mb-2">Число вакансий: 5</x-html.p>
        <x-html.p class="mb-2">Город: Город</x-html.p>
        <x-html.p class="mb-2 overflow-hidden overflow-ellipsis" style="-webkit-line-clamp: 3; display: -webkit-box; -webkit-box-orient: vertical;">Информация о компании A. Это описание компании, которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.</x-html.p>
    </div>
</a>
@endif