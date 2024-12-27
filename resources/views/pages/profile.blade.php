<x-layouts.app>
    <x-slot:title>Name SurName</x-slot:title>
    <x-slot:header>Профиль</x-slot:header>

    <div class="flex justify-end mb-4">
        <x-button class="text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
    </div>

    <div class="flex justify-between flex-wrap">
        <div>
            <x-html.h3 class="mb-4">Имя: Name SurName</x-html.h3>
            <x-html.h3 class="mb-4">ПроффессияПрограммист разработчик</x-html.h3>
            <x-html.h3 class="mb-4">Москва</x-html.h3>
            <x-html.h3 class="mb-4">test@yandex.ru</x-html.h3>
            <x-html.h3 class="mb-4">TestCompanyName</x-html.h3>
        </div>
        <x-logo class="h-52 w-52" />

    </div>

    <x-html.h3 class="mb-1">Резюме:</x-html.h3>

    <x-html.p>
        Образование:
        - Университет, Факультет, Специальность, Годы обучения

        Опыт работы:
        1. Название компании
        [Должность]
        [Период работы]
        - Краткое описание обязанностей и достижений
        - Пункт об успехах: "Увеличил продажи на 15%", "Улучшил процессы"

        2. Название компании
        [Должность]
        [Период работы]
        - Задачи на предыдущем месте
        - Достигнутые результаты

        Навыки и компетенции:
        - Отличное владение укажите навыки, например "MS Office, AutoCAD, SQL"
        - Знание языков программирования, техники, методик, языков общения
        - Умение работать в команде, соблюдать сроки и находить решения сложных задач

        Дополнительная информация:
        - Знание иностранных языков: например, английский – Upper-Intermediate
        - Имеется действующая водительская категория A, B, C
        - Готовность к командировкам

        Личные качества: Ответственность, коммуникабельность, стремление к саморазвитию.
    </x-html.p>

    <x-html.h2>Редактирование</x-html.h2>
    <x-form size="" class="mb-10">

        <x-form.input-group label="Имя">
            <x-form.input type="text" name="name" placeholder="Введите имя" value="Name" />
            <x-form.error field="name" />
        </x-form.input-group>

        <x-form.input-group label="Почта">
            <x-form.input type="text" name="email" placeholder="Введите почту" value="test@email.com" />
            <x-form.error field="email" />
        </x-form.input-group>

        <x-form.input-group label="Профессия">
            <x-form.input type="text" name="profession" placeholder="Введите профессию" value="Программист-разработчик" />
            <x-form.error field="profession" />
        </x-form.input-group>

        <x-form.input-group label="Город(можете написать несколько городов через запятую)">
            <x-form.input type="text" name="city" placeholder="Введите название города" value="Москва" />
            <x-form.error field="city" />
        </x-form.input-group>


        <x-form.input-group label="Город(вы можете написать несколько городов через заяпятую)">
            <x-form.input type="text" name="city" placeholder="Название городов" value="Ковров, Владимир" />
            <x-form.error field="city" />
        </x-form.input-group>

        <x-form.input-group label="Резюме">
            <x-form.textarea name="desription" placeholder="Резюме">
                Опыт работы:
                1. Название компании
                [Должность]
                [Период работы]
                - Краткое описание обязанностей и достижений
                - Пункт об успехах: "Увеличил продажи на 15%", "Улучшил процессы"

                2. Название компании
                [Должность]
                [Период работы]
                - Задачи на предыдущем месте
                - Достигнутые результаты

                Навыки и компетенции:
                - Отличное владение укажите навыки, например "MS Office, AutoCAD, SQL"
                - Знание языков программирования, техники, методик, языков общения
                - Умение работать в команде, соблюдать сроки и находить решения сложных задач

                Дополнительная информация:
                - Знание иностранных языков: например, английский – Upper-Intermediate
                - Имеется действующая водительская категория A, B, C
                - Готовность к командировкам

                Личные качества: Ответственность, коммуникабельность, стремление к саморазвитию.
            </x-form.textarea>
            <x-form.error field="desription" />
        </x-form.input-group>

        <x-form.input-group label="Загрузите фотографию">
            <x-form.input type="file" name="file" />
            <x-form.error field="file" />
        </x-form.input-group>

        <div class="mt-6">
            <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Редактировать</x-button>
        </div>

    </x-form>

    <div class="flex flex-col md:flex-row gap-6">
        <x-form.form class="mb-10" size="w-full">
            <x-html.h3 class="mb-4">Смена пароля</x-html.h3>
            <x-form.input-group label="Текущий пароль">
                <x-form.input type="password" name="current_password" placeholder="Введите текущий пароль" value="" />
                <x-form.error field="current_password" />
            </x-form.input-group>

            <x-form.input-group label="Новый пароль">
                <x-form.input type="password" name="new_password" placeholder="Введите новый пароль" value="" />
                <x-form.error field="new_password"></x-form.error>
            </x-form.input-group>

            <div class="mt-6">
                <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Сменить</x-button>
            </div>
        </x-form.form>

        <x-form.form class="mb-10" size="w-full">
            <x-html.h3 class="mb-4">Генерация секретного кода</x-html.h3>
            <x-html.p class="mb-4">
                После генерации вам нужно будет скопировать код и отправить новому сотруднику. После использования или новой генерации код удаляется.
            </x-html.p>

            <x-form.input-group label="Сгенерированный код">
                <x-form.input type="text" name="secret_code" value="fdhdhfhdfhdshfi8976548973jvhjciuh" disabled />
                <x-form.error field="new_password" />
            </x-form.input-group>

            <div class="mt-6">
                <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Сменить</x-button>
            </div>
        </x-form.form>
    </div>




    <div class="flex flex-col md:flex-row gap-6">
        <form class=" bg-gray-800 p-8 rounded-lg shadow-lg w-full mb-10" method="POST" action="{{ route('logout') }}">
            @csrf
            @method('DELETE')
            <x-html.h3 class="mb-4">Выйти из аккаунта</x-html.h3>
            <div class="mt-6">
                <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Выйти</x-button>
            </div>
        </form>
        <form class=" bg-gray-800 p-8 rounded-lg shadow-lg w-full mb-10" method="POST" action="">
            @csrf
            @method('DELETE')
            <x-html.h3 class="mb-4">Удаление аккаунта</x-html.h3>
            <div class="mt-6">
                <x-button class="w-full text-sm px-4 py-2" type="submit" type_component="button">Удалить</x-button>
            </div>
        </form>
    </div>

</x-layouts.app>