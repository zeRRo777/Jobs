@props(['type_component' => 'link'])

@if ($type_component == 'link')
<div>
    <a
        {{ $attributes->merge(['class' => "border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white"]) }}>
        {{ $slot }}
    </a>
</div>

@elseif($type_component == 'button')
<div>
    <button
        {{ $attributes->merge(['class' => "border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white"]) }}>
        {{ $slot }}
    </button>
</div>

@endif