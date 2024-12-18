@props(['page' => '/'])

<a
    {{ $attributes->merge([
        'class' => request()->is($page) ? 
            'block mt-4 lg:inline-block lg:mt-0 text-gray-600 hover:text-white mr-8' : 
            'block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white mr-8'
    ]) }}>
    {{ $slot }}
</a>