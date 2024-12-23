@props(['company' => null, 'number' => null])
@if ($company)
<a href="#" class="block">
    <div class="relative bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transform hover:scale-105 transition duration-300">
        <div class="flex flex-row justify-between items-center mb-2">
            @if (!empty($number))
            <div>
                <span class="text-4xl font-bold text-white opacity-90">{{ $number }}</span>
            </div>
            @endif
            @if(!empty($company->photo))
            <img src="{{ $company->photo }}" alt="Логотип компании А" class="h-16 w-16">
            @endif
        </div>
        <!-- Название компании -->
        <x-html.h3 class="overflow-hidden overflow-ellipsis text-xl" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical;">
            {{ $company->name }}
        </x-html.h3>

        @if (!empty($company->vacancies_count))
        <x-html.p class="mb-2">Число вакансий: {{ $company->vacancies_count }}</x-html.p>
        @endif

        @if (!empty($company->cities))
        <x-html.p class="mb-2">
            @if ($company->cities->count() > 1)
            Города:
            @else
            Город:
            @endif
            @foreach ($company->cities as $city)
            @if ($loop->last)
            {{ $city->name }}.
            @else
            {{ $city->name }},
            @endif
            @endforeach
        </x-html.p>
        @endif

        @if (!empty($company->description))
        <x-html.p class="mb-2 overflow-hidden overflow-ellipsis" style="-webkit-line-clamp: 3; display: -webkit-box; -webkit-box-orient: vertical;">
            {{ $company->description }}
        </x-html.p>
        @endif
    </div>
</a>
@endif