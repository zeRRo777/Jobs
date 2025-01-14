@props(['user' => null])

@if (!empty($user))
<a wire:navigate href="{{ route('user.show', $user->id) }}" class="block">
    <div class="relative bg-gray-800 rounded-lg shadow hover:shadow-xl transform hover:scale-105 transition duration-300 h-full overflow-hidden">
        @if (!empty($user->photo))
        <x-logo src="{{ asset('storage/' . $user->photo) }}" class="w-full h-48 object-cover" alt="Логотип {{ $user->name }}" />
        @else
        <div class="h-48"></div>
        @endif

        <div class="p-6">
            <x-html.h3 class="mb-2">{{ $user->name }}</x-html.h3>
            @if (!empty($user->profession))
            <x-html.p class="mb-2">Профессия: {{ $user->profession }}</x-html.p>
            @endif
            <x-html.p class="mb-2">Почта: {{ $user->email }}</x-html.p>
        </div>
    </div>
</a>
@endif