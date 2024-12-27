<a wire:navigate
    class="block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white mr-8"
    wire:current.exact="text-gray-600"
    {{ $attributes }}>
    {{ $slot }}
</a>