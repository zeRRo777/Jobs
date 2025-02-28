<div>
    <div class="mb-6">
        <x-form.input-group :label="$label">
            <x-form.input type="text" placeholder="" class="mb-2" wire:model.live="query" />

            <div class="mt-2 max-h-40 overflow-y-auto bg-gray-700 rounded-md p-2">

                @if($type === 'radio')
                <div wire:key="empty-value" class="option-item">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="{{ $name }}"
                            value=""
                            class="form-radio text-indigo-600"
                            @if( !$filteredData->pluck('active')->contains(true)) checked @endif
                        >
                        <span class="ml-2 text-gray-200">Пустое значение</span>
                    </label>
                </div>
                @endif

                @foreach($filteredData as $key => $value)
                <div wire:key="{{ $key }}" class="option-item">
                    <label class="inline-flex items-center">
                        <input
                            type="{{ $type }}"
                            name="{{ $type === 'radio' ? $name : $name . '[]' }}"
                            value="{{ $value['id'] }}"
                            class="form-checkbox text-indigo-600"
                            @if ( $value['active']) checked @endif>
                        <span class="ml-2 text-gray-200">{{ $value['name'] }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </x-form.input-group>
        @if ($showError)
        <x-form.error field="{{ $name }}" />
        @endif

    </div>

</div>