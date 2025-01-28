@props(['title' => '', 'route' => '', 'subjectSort' => ''])



<!-- Сортировка -->
<div class="mb-4 flex md:justify-end">
    <div class="flex flex-col md:items-center space-y-2 md:space-x-2 md:flex-row">
        <x-html.h3>{{ $title }}</x-html.h3>
        <form method="GET" action="{{ route($route) }}">
            @foreach (request()->except($subjectSort) as $key => $value )
            @if (is_array($value))
            @foreach ($value as $v)
            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
            @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
            @endforeach
            <div class="relative">
                <select
                    name="{{ $subjectSort }}"
                    onchange="this.form.submit()"
                    class="block appearance-none w-full bg-gray-800 border border-gray-300 hover:border-gray-400 px-4 py-2 pr-8 rounded leading-tight focus:outline-none focus:shadow-outline cursor-pointer">
                    <option value="" disabled {{ !request($subjectSort) ? 'selected' : '' }}>Выберите</option>
                    <option value="asc" {{ request( $subjectSort) == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                    <option value="desc" {{ request($subjectSort) == 'desc' ? 'selected' : '' }}>По убыванию</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-white h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M5.516 7.548a.5.5 0 0 1 .697-.716L10 10.318l3.787-3.486a.5.5 0 0 1 .697.716l-4 3.682a.5.5 0 0 1-.697 0l-4-3.682z" />
                    </svg>
                </div>
            </div>
        </form>
    </div>
</div>