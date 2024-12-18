@props(['tag' => null])

@if ($tag)
<span class="inline-block bg-gray-700 text-white text-xs font-semibold px-2 py-1 rounded">
    {{ $tag->name }}
</span>
@endif