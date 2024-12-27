@props(['type_component' => 'link'])

@if ($type_component == 'link')

<a
    wire:navigate
    {{ $attributes->merge(['class' => "border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white"]) }}>
    {{ $slot }}
</a>


@elseif($type_component == 'button')

<button
    {{ $attributes->merge(['class' => "border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white"]) }}>
    {{ $slot }}
</button>

@endif