@props(['company' => 'test', 'number' => '1'])
@if ($company)
<a href="#" class="block">
    <div class="relative bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transform hover:scale-105 transition duration-300">
        <div class="flex flex-row justify-between items-center">
            <!-- Номер в списке -->
            <div>
                <span class="text-4xl font-bold text-white opacity-90">{{ $number }}</span>
            </div>
            <!-- Логотип компании -->
            <img src="{{ asset('images/logo.png') }}" alt="Логотип компании А" class="h-16 w-16">
        </div>
        <!-- Название компании -->
        <h2 class="text-2xl font-bold mb-2">Компания А</h2>
        <!-- Число вакансий -->
        <p class="text-gray-400 mb-2">Число вакансий: 5</p>
        <!-- Информация о компании -->
        <p class="text-gray-300 overflow-hidden overflow-ellipsis" style="-webkit-line-clamp: 3; display: -webkit-box; -webkit-box-orient: vertical;">
            Информация о компании A. Это описание компании, которое может быть длинным, но мы ограничиваем его до трех строк текста, чтобы карточка сохраняла аккуратный вид и не занимала слишком много места на странице.
        </p>
    </div>
</a>
@endif