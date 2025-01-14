<x-layouts.app>
    <x-slot:title>{{ $user->name }}</x-slot:title>
    <x-slot:header>Профиль</x-slot:header>

    @if (session('success'))
    <x-success>{{ session('success') }}</x-success>
    @endif

    <div x-data="{ editing: @js($errors->hasAny(['name', 'email', 'profession', 'resume', 'new_cities', 'photo', 'delete_photo'])) }">
        <div class="flex justify-end mb-4">
            <x-button @click="editing = !editing" class="text-sm px-4 py-2" type="button" type_component="button">
                <span x-text="editing ? 'Продолжить просмотр' : 'Редактировать'"></span>
            </x-button>
        </div>

        <div class="flex justify-between flex-wrap">
            <div>
                <x-html.h3 class="mb-4">{{ $user->name }}</x-html.h3>

                @if (!empty($user->company))
                <x-html.h3 class="mb-4">Админ <a href="{{ route('company.show', $user->company->id) }}">{{ $user->company->name }}</a></x-html.h3>
                @endif

                <x-html.h3 class="mb-4">
                    @if (!empty($user->profession))
                    {{ $user->profession }}
                    @else
                    Профессия: не указана
                    @endif
                </x-html.h3>

                <x-html.h3 class="mb-4">
                    @if ($user->cities->count() > 0)
                    @if ($user->cities->count() == 1)
                    Город: {{ $user->cities->first()->name }}
                    @else
                    Города:
                    @foreach ($user->cities as $city)
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

                <x-html.h3 class="mb-4">{{ $user->email }}</x-html.h3>
            </div>

            @if (!empty($user->photo))
            <img src="{{ asset('storage/' . $user->photo) }}" alt="Логотип {{ $user->name }}" class="h-52 w-52 mb-4">
            @endif
        </div>

        @if (empty($user->resume))
        <x-html.h3 class="mb-4">Резюме: Не указано</x-html.h3>
        @else
        <x-html.h3 class="mb-1">Резюме:</x-html.h3>
        <x-html.p class="mb-4">{{ $user->resume }}</x-html.p>
        @endif

        <div x-show="editing">
            <x-html.h2>Редактирование</x-html.h2>

            <x-form size="" class="mb-10" action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <x-form.input-group label="ФИО">
                    <x-form.input type="text" name="name" placeholder="Введите фио" value="{{ $user->name }}" />
                    <x-form.error field="name" />
                </x-form.input-group>

                <x-form.input-group label="Электронная почта">
                    <x-form.input type="email" name="email" placeholder="Введите электронную почту" value="{{ $user->email }}" />
                    <x-form.error field="email" />
                </x-form.input-group>

                <x-form.input-group label="Профессия">
                    <x-form.input type="text" name="profession" placeholder="Введите профессию" value="{{ $user->profession }}" />
                    <x-form.error field="profession" />
                </x-form.input-group>

                <x-form.input-group label="Резюме">
                    <x-form.textarea name="resume" placeholder="Резюме">
                        {{ $user->resume }}
                    </x-form.textarea>
                    <x-form.error field="resume" />
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
                        @if (!empty($user->photo))
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Логотип {{ $user->name }}" class="h-40 w-40 mb-2">
                        @endif

                        <div x-show="!deletePhoto">
                            <x-form.input type="file" name="photo" />
                        </div>

                        <x-form.error field="photo" />
                    </x-form.input-group>

                    @if(!empty($user->photo))
                    <x-form.input-group label="Удалить текущее фото">
                        <input type="checkbox" name="delete_photo" x-model="deletePhoto">
                        <x-form.error field="delete_photo" />
                    </x-form.input-group>
                    @endif
                </div>

                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
                </div>

            </x-form>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <x-form.form class="mb-10" size="w-full" action="{{ route('profile.changePassword', $user->id) }}" method="POST">
            @csrf
            <x-html.h3 class="mb-4">Смена пароля</x-html.h3>
            <x-form.input-group label="Текущий пароль">
                <x-form.input type="password" name="current_password" placeholder="Введите текущий пароль" />
                <x-form.error field="current_password" />
            </x-form.input-group>

            <x-form.input-group label="Новый пароль">
                <x-form.input type="password" name="new_password" placeholder="Введите новый пароль" />
                <x-form.error field="new_password" />
            </x-form.input-group>

            <x-form.input-group label="Подтвердите новый пароль">
                <x-form.input type="password" name="new_password_confirmation" placeholder="Подтвердите новый пароль" />
                <x-form.error field="new_password_confirmation" />
            </x-form.input-group>

            <div class="mt-6">
                <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Сменить</x-button>
            </div>
        </x-form.form>

        @can('generateCode', $user->company)
        <div class="space-y-6  bg-gray-800 p-8 rounded-lg shadow-lg w-full mb-10">
            <x-html.h3 class="mb-4">Генерация секретного кода</x-html.h3>
            <x-html.p class="mb-4">
                После генерации вам нужно будет скопировать код и отправить новому сотруднику. После использования или новой генерации код удаляется.
            </x-html.p>
            <x-form.input-group label="Сгенерированный код">
                <div class="w-full px-3 py-2 bg-gray-700 text-gray-200 rounded focus:outline-none focus:shadow-outline">
                    @if (!empty($user->company->secret_code))
                    {{ $user->company->secret_code }}
                    @else
                    Здесь будет находиться код
                    @endif
                </div>
                <x-form.error field="secret_code" />
            </x-form.input-group>
            <form action="{{ route('company.generateSecretCode', [$user->id, $user->company->id]) }}" method="POST">
                @csrf
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Сменить</x-button>
                </div>
            </form>
        </div>
        @endcan
    </div>

    <div x-data="{ deleteModal: false }" x-init="deleteModal = @json($errors->has('password_for_delete'))">
        <div class="flex flex-col md:flex-row gap-6">
            <form class=" bg-gray-800 p-8 rounded-lg shadow-lg w-full mb-10" method="POST" action="{{ route('logout') }}">
                @csrf
                @method('DELETE')
                <x-html.h3 class="mb-4">Выйти из аккаунта</x-html.h3>
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Выйти</x-button>
                </div>
            </form>

            <div class=" bg-gray-800 p-8 rounded-lg shadow-lg w-full mb-10">
                <x-html.h3 class="mb-4">Удаление аккаунта</x-html.h3>
                <div class="mt-6">
                    <x-button class="w-full text-sm px-4 py-2" type="button" type_component="button" @click="deleteModal = true">
                        Удалить
                    </x-button>
                </div>
            </div>
            <div x-show="deleteModal" class="fixed inset-0 flex items-center justify-center">
                <div class="absolute inset-0 bg-black opacity-50"></div>
                <div class="relative bg-gray-800 p-8 rounded-lg shadow-lg">
                    <x-html.h3 class="mb-4">Предупреждение</x-html.h3>
                    <x-html.p class="mb-4">Это действие необратимо. Вы уверены, что хотите удалить аккаунт?</x-html.p>
                    <form method="POST" action="{{ route('profile.deleteAccount') }}" class="space-y-4">
                        @method('DELETE')
                        @csrf

                        <x-form.input-group label="Текущий пароль">
                            <x-form.input type="password" name="password_for_delete" placeholder="Введите текущий пароль" />
                            <x-form.error field="password_for_delete" />
                        </x-form.input-group>

                        <x-button class="w-full text-sm px-4 py-2" type="button" type_component="button" @click="deleteModal = false">
                            Отмена
                        </x-button>

                        <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">
                            Удалить
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-layouts.app>