@props(['label', 'options' => [], 'name' => ''])

<div class="mb-6">
    <x-form.input-group :label="$label">
        <x-form.input type="text" placeholder="Поиск..." class="mb-2" />
        <div class="mt-2 max-h-40 overflow-y-auto bg-gray-700 rounded-md p-2">
            @foreach($options as $option)
            <div class="option-item">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="{{ $name }}[]" value="{{ $option }}" class="form-checkbox text-indigo-600">
                    <span class="ml-2 text-gray-200">{{ $option }}</span>
                </label>
            </div>
            @endforeach
        </div>
    </x-form.input-group>
</div>