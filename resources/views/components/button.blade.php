@props(['_type' => 'link'])

@if ($_type == 'link')
<a
    {{ $attributes->merge(['class' => "border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white"]) }}>
    {{ $slot }}
</a>

@elseif($_type == 'button')
<button
    {{ $attributes->merge(['class' => "border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white"]) }}>
    {{ $slot }}
</button>
@endif