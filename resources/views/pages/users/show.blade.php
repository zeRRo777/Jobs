<x-layouts.app>
    <x-slot:title>{{ $user->name . ' page' }}</x-slot:title>
    <x-slot:header>{{ $user->name }}</x-slot:header>

    @if (session('success'))
    <x-success>{{ session('success') }}</x-success>
    @endif

    @if (!empty($user->photo))
    <img src="{{ asset('storage/' . $user->photo) }}" alt="Лого компании {{ $user->name }}" class="h-52 w-52 mx-auto mb-4">
    @endif

    <x-html.h3 class="mb-2">Почта: {{ $user->email }}</x-html.h3>
    @if (!empty($user->phone))
    <x-html.h3 class="mb-2">Профессия: {{ $user->profession }}</x-html.h3>
    @endif
    <x-html.h3 class="mb-2">
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

    @if(!empty($user->resume))
    <x-html.h3 class="mb-2">Резюме:</x-html.h3>
    <x-html.p class="mb-4">{{ $user->resume }}</x-html.p>
    @endif
    <div x-data="{ offerJobModal: false }" x-init="offerJobModal = @js($errors->any())">
        <x-button type_component="button" class="text-sm px-4 py-2" @click="offerJobModal = true">Предложить работу</x-button>
        <div x-show="offerJobModal" class="fixed inset-0 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative bg-gray-800 p-8 rounded-lg shadow-lg flex flex-col">
                <x-html.h2>Вакансии</x-html.h2>
                <x-html.p class="mb-4">Выберите вакансии и отправьте предложения о работе.</x-html.p>
                @if (Auth::user()->company->vacancies->count() > 0)
                <form id="offers-job" method="POST" action="{{ route('user.offers', $user->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 w-full mb-6">
                        @foreach ($vacancies as $vacancy)
                        <x-user.checkbox :vacancy="$vacancy" />
                        @endforeach

                    </div>
                </form>
                <x-form.errors />
                @else
                <div class="flex flex-col justify-center items-center">
                    <x-html.h3 class="mb-3">У вашей компании пока еще нет вакансий!</x-html.h3>
                    <x-button class="text-sm px-4 py-2" :href="route('company.show', ['company' => Auth::user()->company->id, 'add_vacancy' => true])" type_component="link">Добавить вакансии</x-button>
                </div>
                @endif


                <div class="flex justify-between w-full mt-3">
                    <x-button @click="offerJobModal = false" type_component="button" class="text-sm px-4 py-2">Отмена</x-button>
                    @if (Auth::user()->company->vacancies->count() > 0)
                    <x-button type_component="button" form="offers-job" type="submit" class="text-sm px-4 py-2">Отправить</x-button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>