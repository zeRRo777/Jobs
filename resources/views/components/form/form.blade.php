@props(['size' => 'max-w-md'])

<form {{ $attributes->merge(['class' => "{$size} space-y-6  bg-gray-800 p-8 rounded-lg shadow-lg"]) }}>
    {{ $slot }}
</form>