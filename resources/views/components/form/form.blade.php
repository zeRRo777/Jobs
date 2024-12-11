<div class="max-w-md mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
    <form {{ $attributes->merge(['class' => 'space-y-6']) }}>
        {{ $slot }}
    </form>
</div>