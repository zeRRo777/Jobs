<div>
    <x-form.input-group label="Название компании или код(если хотите присоединиться к существующей компании)">
        @if ($inputType === 'company')
        <x-form.input type="text" name="company" placeholder="Введите название компании" value="{{ $inputValue }}" wire:model="inputValue" />
        <x-form.error field="company" />
        @else
        <x-form.input type="text" name="secret_code" placeholder="Введите секретный код" value="{{ $inputValue }}" wire:model="inputValue" />
        <x-form.error field="secret_code" />
        @endif
    </x-form.input-group>

    <x-button
        class="w-full text-sm px-4 py-2 mt-3"
        wire:click="switchInputType"
        type="button"
        type_component="button">
        @if ($inputType === 'company')
        Секретный код
        @else
        Название компании
        @endif
    </x-button>
</div>