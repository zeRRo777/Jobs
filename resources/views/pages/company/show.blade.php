<x-layouts.app>
    <x-slot:title>{{ $company->name }}</x-slot:title>
    <x-slot:header>{{ $company->name }}</x-slot:header>

    @if (session('success'))
    <x-success>{{ session('success') }}</x-success>
    @endif

    <div x-data="{
        editing: @js($errors->hasAny(['name_company', 'company_id_company', 'description_company', 'new_cities_company', 'cities_company', 'photo_company', 'delete_photo_company', 'error_company'])),
        add_vacancy: @js($errors->hasAny(['title_vacancy', 'description_vacancy', 'new_city_vacancy', 'company_id_vacancy', 'city_id_vacancy', 'new_tags_vacancy', 'tags_vacancy', 'salary_start_vacancy', 'salary_end_vacancy', 'error_vacancy']))
    }">
        @can('update', $company)
        <div class="flex justify-end mb-4">
            <x-button @click="editing = !editing" class="text-sm px-4 py-2" type="button" type_component="button">
                <span x-text="editing ? 'Продолжить просмотр' : 'Редактировать'"></span>
            </x-button>
        </div>
        @endcan


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

        <div x-show="editing" class="mb-5">
            <x-html.h2>Редактирование</x-html.h2>
            <x-form size="" method="POST" action="{{ route('company.update', $company->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-form.input-group label="Название">
                    <x-form.input type="text" name="name_company" placeholder="Название компании" value="{{ $company->name }}" />
                    <x-form.error field="name_company" />
                </x-form.input-group>

                <x-form.input-group label="Информация о компании">
                    <x-form.textarea name="description_company" placeholder="Информация о компании">
                        {{ $company->description }}
                    </x-form.textarea>
                    <x-form.error field="description_company" />
                </x-form.input-group>

                <x-form.input-group label="Выберите города или введите новые через запятую">
                    <x-form.input type="text" name="new_cities_company" placeholder="Название новых городов" value="{{ old('new_cities') }}" />
                    <x-form.error field="new_cities_company" />
                </x-form.input-group>

                @livewire('multiple-select', [
                'label' => 'Поиск города',
                'name' => 'cities_company',
                'data' => $cities,
                ])

                <div x-data="{ deletePhoto: false }">
                    <x-form.input-group label="Загрузить файл">
                        @if (!empty($company->photo))
                        <img src="{{ asset('storage/' . $company->photo) }}" alt="Логотип {{ $company->name }}" class="h-40 w-40 mb-2">
                        @endif

                        <div x-show="!deletePhoto">
                            <x-form.input type="file" name="photo_company" />
                        </div>

                        <x-form.error field="photo_company" />
                    </x-form.input-group>

                    @if(!empty($company->photo))
                    <x-form.input-group label="Удалить текущее фото">
                        <input type="checkbox" name="delete_photo_company" x-model="deletePhoto">
                        <x-form.error field="delete_photo_company" />
                    </x-form.input-group>
                    @endif
                </div>

                <x-form.error field="error_company" />

                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
                </div>

            </x-form>
        </div>

        <x-html.h2>Вакансии</x-html.h2>

        @can('createVacancy', $company)
        <div class="flex justify-end mb-4">
            <x-button @click="add_vacancy = !add_vacancy" class="text-sm px-4 py-2" type="button" type_component="button">
                <span x-text="add_vacancy ? 'Продолжить создание' : 'Создать'"></span>
            </x-button>
        </div>
        @endcan

        <div x-show="add_vacancy" class="mb-5">
            <x-html.h2>Создание вакансии</x-html.h2>

            <x-form size="" method="POST" action="{{ route('vacancy.store', $company->id) }}">
                @csrf
                <x-form.input-group label="Название вакансии">
                    <x-form.input type="text" name="title_vacancy" placeholder="Название вакансии" value="{{ old('title_vacancy') }}" />
                    <x-form.error field="title_vacancy" />
                </x-form.input-group>

                <x-form.input-group label="Описание вакансии">
                    <x-form.textarea name="description_vacancy" placeholder="Описание вакансии">
                        {{ old('description_vacancy') }}
                    </x-form.textarea>
                    <x-form.error field="description_vacancy" />
                </x-form.input-group>

                <x-form.input-group label="Выберите город или введите новый">
                    <x-form.input type="text" name="new_city_vacancy" placeholder="Название города" value="{{ old('new_city_vacancy') }}" />
                    <x-form.error field="new_city_vacancy" />
                </x-form.input-group>

                @livewire('multiple-select', [
                'label' => 'Поиск города',
                'name' => 'city_id_vacancy',
                'data' => $cities_vacancy,
                'type' => 'radio'
                ])

                <x-form.input-group label="Новые теги">
                    <x-form.input type="text" name="new_tags_vacancy" placeholder="Введите новые теги через запятую" value="{{ old('new_tags_vacancy') }}" />
                    <x-form.error field="new_tags_vacancy" />
                </x-form.input-group>

                @livewire('multiple-select', [
                'label' => 'Поск тега',
                'name' => 'tags_vacancy',
                'data' => $tags_vacancy,
                ])

                <x-form.input-group label="Зарплата от">
                    <x-form.input type="number" name="salary_start_vacancy" placeholder="Зарплата от" value="{{ old('salary_start_vacancy') }}" />
                    <x-form.error field="salary_start_vacancy" />
                </x-form.input-group>

                <x-form.input-group label="Зарплата до">
                    <x-form.input type="number" name="salary_end_vacancy" placeholder="Зарплата до" value="{{ old('salary_end_vacancy') }}" />
                    <x-form.error field="salary_end_vacancy" />
                </x-form.input-group>

                <x-form.error field="error_vacancy" />

                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Создать</x-button>
                </div>

            </x-form>
        </div>

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
    </div>
</x-layouts.app>