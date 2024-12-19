<div>
    <div class="mb-6">
        <x-form.input-group :label="$label">

            <x-form.input type="text" placeholder="" class="mb-2" wire:model.live="query" />

            <div class="mt-2 max-h-40 overflow-y-auto bg-gray-700 rounded-md p-2">
                @foreach($filteredData as $key => $value)
                <div wire:key="{{ $key }}" class="option-item">
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="{{ $name }}[]"
                            value="{{ $key }}"
                            class="form-checkbox text-indigo-600"
                            @if ($value['active'])
                            checked
                            @endif>
                        <span class="ml-2 text-gray-200">{{ $value['name'] }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </x-form.input-group>
    </div>

</div>