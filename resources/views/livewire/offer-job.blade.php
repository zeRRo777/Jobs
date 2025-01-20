<div class="px-6 pb-6 text-center">
    <x-button class="text-sm px-4 py-2 w-full"
        wire:click="offer"
        wire:loading.attr="disabled"
        type_component="button">
        Предложить работу
    </x-button>
    @if ($isOffer)
    <div class="mt-4 text-green-600">
        Вы уже отправляли письмо с предложением работы данному пользователю!
    </div>
    @endif
</div>