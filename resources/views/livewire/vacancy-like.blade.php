<div class="mt-2">
    @if (!empty($successMessage))
    <div class="mb-4 text-green-600">
        {{ $successMessage }}
    </div>
    @endif

    @if ($isLike)
    <x-button class="{{ $class }}" wire:click="unlike" wire:loading.attr="disabled" type_component="button">
        Отменить отклик
    </x-button>
    @else
    <x-button class="{{ $class }}" wire:click="like" wire:loading.attr="disabled" type_component="button">
        Откликнуться
    </x-button>
    @endif
</div>