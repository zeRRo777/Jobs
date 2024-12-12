@props(['label'])

<div>
    <label {{ $attributes }} class="block mb-2 text-sm font-medium text-gray-200">{{ $label }}</label>
    {{ $slot }}
</div>