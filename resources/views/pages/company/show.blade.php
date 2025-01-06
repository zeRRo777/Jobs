<x-layouts.app>
    <x-slot:title>{{ $company->name }}</x-slot:title>
    <x-slot:header>{{ $company->name }}</x-slot:header>

    @if (session('success'))
        <x-success>{{ session('success') }}</x-success>
    @endif

    <div x-data="{ editing: @js($errors->any()) }">
        <div class="flex justify-end mb-4">
            <x-button @click="editing = !editing" class="text-sm px-4 py-2" type="button" type_component="button">
                <span x-text="editing ? 'Продолжить просмотр' : 'Редактировать'"></span>
            </x-button>
        </div>


        <x-html.h2>О нас</x-html.h2>
        @if (!empty($company->photo))
        <img src="{{ asset('storage/' . $company->photo) }}" alt="Лого компании {{ $company->name }}" class="h-52 w-52 mx-auto mb-4">
        @endif

        @if (!empty($company->cities))
            <x-html.h3 class="mb-2">

                @if ($company->cities->count() > 0)
                    @if ($company->cities->count() == 1)
                        Город: {{ $company->cities->first()->name }}
                    @else
                        Города:
                        @foreach ($company->cities as $city)
                            @if ($loop->last)
                                {{ $city->name }}.
                            @else
                                {{ $city->name }},
                            @endif
                        @endforeach
                    @endif
                @else
                    Город: Не указан
                @endif
            </x-html.h3>
        @endif

        @if (!empty($company->description))
        <x-html.p class="mb-10">
           {{ $company->description }}
        </x-html.p>
        @endif


        <x-html.h2>Вакансии</x-html.h2>

        @if ($vacancies->count() > 0)
        <x-vacancy.list class="mb-5">
            @foreach ($vacancies as $vacancy)
            <x-vacancy.card :vacancy="$vacancy" />
            @endforeach
        </x-vacancy.list>
        {{ $vacancies->links() }}
        @else
        <x-html.h2>У данной компании нет вакансий</x-html.h2>
        @endif

        <div x-show="editing">
            <x-html.h2>Редактирование</x-html.h2>
            <x-form size="" method="POST" action="{{ route('company.update', $company->id) }}" enctype="multipart/form-data">
                @csrf
                <x-form.input-group label="Название">
                    <x-form.input type="text" name="name" placeholder="Название компании" value="{{ $company->name }}" />
                    <x-form.error field="name" />
                </x-form.input-group>
            
                <x-form.input-group label="Информация о компании">
                    <x-form.textarea name="description" placeholder="Информация о компании">
                        {{ $company->description }}
                    </x-form.textarea>
                    <x-form.error field="description" />
                </x-form.input-group>
            
                <x-form.input-group label="Выберите города или введите новые через запятую">
                    <x-form.input type="text" name="new_cities" placeholder="Название новых городов" value="{{ old('new_cities') }}" />
                    <x-form.error field="new_cities" />
                </x-form.input-group>
            
                @livewire('multiple-select', [
                        'label' => 'Поиск города',
                        'name' => 'cities',
                        'data' => $cities,
                        ])
    
                <div x-data="{ deletePhoto: false }">
                    <x-form.input-group label="Загрузить файл">
                        @if (!empty($company->photo))
                        <img src="{{ asset('storage/' . $company->photo) }}" alt="Логотип {{ $company->name }}" class="h-40 w-40 mb-2">
                        @endif
                    
                        <div x-show="!deletePhoto">
                            <x-form.input type="file" name="photo"/>
                        </div>
                    
                        <x-form.error field="photo" />
                    </x-form.input-group>
                
                    @if(!empty($company->photo))
                    <x-form.input-group label="Удалить текущее фото">
                        <input type="checkbox" name="delete_photo" x-model="deletePhoto">
                        <x-form.error field="delete_photo" />
                    </x-form.input-group>
                    @endif
                </div>

                <x-form.error field="error" />
            
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
                </div>
            
            </x-form>
        </div>
    </div>
</x-layouts.app>