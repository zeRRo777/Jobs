<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css'])
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <title>{{ $title ?? 'Платформа для просмотра и управления вакансиями' }}</title>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col">
    <!-- Навигационная панель -->
    <x-nav />

    <!-- Заголовок страницы -->
    @if (!empty($header))
    <header class="container mx-auto px-4 text-center my-8">
        <h1 class="text-4xl font-bold">{{ $header }}</h1>
    </header>
    @endif

    <!-- Основной контент -->
    <main class="container mx-auto px-4 flex-grow">
        {{ $slot }}
    </main>

    <!-- Футер -->
    <x-footer>2024 Платформа для просмотра и управления вакансиями. Все права защищены. Лицензия Test.</x-footer>
</body>

</html>