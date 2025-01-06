<x-layouts.app>
    <x-slot:title>{{ $vacancy->company->name . ' ' . $vacancy->title }}</x-slot:title>
    <x-slot:header>{{ $vacancy->title }}</x-slot:header>

    @if (session('success'))
        <x-success>{{ session('success') }}</x-success>
    @endif

    @if (session('delete_vacancy'))
        <x-success>{{ session('delete_vacancy') }}</x-success>
    @endif
    
    <div x-data="{ editing: @js($errors->any()) }">
            <div class="flex justify-end mb-4 gap-3">
                <x-button @click="editing = !editing" class="text-sm px-4 py-2" type="button" type_component="button">
                    <span x-text="editing ? 'Продолжить просмотр' : 'Редактировать'"></span>
                </x-button>
                <form action="{{ route('vacancy.delete', $vacancy->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Удалить</x-button>
                </form>
                
            </div>

        <x-html.h3 class="mb-2">
            Зарплата:
                @if (!empty($vacancy->salary_start) && !empty($vacancy->salary_end))
                от {{ $vacancy->salary_start . ' до ' . $vacancy->salary_end}} руб.
                @elseif(!empty($vacancy->salary_start))
                от {{ $vacancy->salary_start }} руб.
                @elseif(!empty($vacancy->salary_end))
                до {{ $vacancy->salary_end }} руб.
                @else
                Не указано
                @endif
        </x-html.h3>
        <x-html.h3 class="mb-2">Город: {{ $vacancy->city->name }}  </x-html.h3>
        <x-html.h3>Информация о вакансии:</x-html.h3>
        <x-html.p class="mb-2">
            @if (!empty($vacancy->description))
            {{ $vacancy->description }}
            @else
            Не указана
            @endif
        </x-html.p>

        @if (!empty($vacancy->tags))
        <x-html.h3 class="mb-2">Теги:</x-html.h3>
        <div class="flex flex-wrap gap-2 mb-2">
            @foreach ($vacancy->tags as $tag)
            <x-vacancy.tag :tag="$tag" />
            @endforeach
        </div>
        @endif

        <div class="flex flex-row items-center space-x-4 mb-2">
            <x-html.h3>Компания: {{ $vacancy->company->name }}</x-html.h3>
            @if (!empty($vacancy->company->photo))
            <img src="{{ asset('storage/' . $vacancy->company->photo) }}" alt="Логотип {{ $vacancy->company->name }}" class="h-10 w-10 mb-2">
            @endif
        </div>

        @if (!empty($vacancy->company->description))
        <x-html.p class="mb-2">
            {{ $vacancy->company->description }}
        </x-html.p>
        @endif
    

        <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Откликнуться</x-button>

        <div x-show="editing">
            <x-html.h2>Редактирование</x-html.h2>
            <x-form size="" method="POST" action="{{ route('vacancy.update', $vacancy->id) }}">
                @csrf
                <x-form.input-group label="Название вакансии">
                    <x-form.input type="text" name="title" placeholder="Название вакансии" value="{{ $vacancy->title }}" />
                    <x-form.error field="title" />
                </x-form.input-group>
            
                <x-form.input-group label="Описание вакансии">
                    <x-form.textarea name="description" placeholder="Описание вакансии">
                        {{ $vacancy->description }}
                    </x-form.textarea>
                    <x-form.error field="description" />
                </x-form.input-group>
            
                <x-form.input-group label="Выберите город или введите новый">
                    <x-form.input type="text" name="new_city" placeholder="Название города" value="{{ old('new_city') }}" />
                    <x-form.error field="new_city" />
                </x-form.input-group>
            
                @livewire('multiple-select', [
                        'label' => 'Поиск города',
                        'name' => 'city_id',
                        'data' => $cities,
                        'type' => 'radio'
                        ])

                <x-form.input-group label="Новые теги">
                    <x-form.input type="text" name="new_tags" placeholder="Введите новые теги через запятую" value="{{ old('new_tags') }}" />
                    <x-form.error field="new_tags" />
                </x-form.input-group>            
            
                @livewire('multiple-select', [
                        'label' => 'Поск тега',
                        'name' => 'tags',
                        'data' => $tags,
                        ])

                <x-form.input-group label="Зарплата от">
                    <x-form.input type="number" name="salary_start" placeholder="Зарплата от" value="{{ $vacancy->salary_start }}" />
                    <x-form.error field="salary_start" />
                </x-form.input-group>  
            
                <x-form.input-group label="Зарплата до">
                    <x-form.input type="number" name="salary_end" placeholder="Зарплата до" value="{{ $vacancy->salary_end }}" />
                    <x-form.error field="salary_end" />
                </x-form.input-group>    
            
                <x-form.error field="error" />

                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
                </div>
            
            </x-form>
        </div>
    </div>

</x-layouts.app>