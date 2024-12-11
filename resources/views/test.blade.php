<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Название вашей страницы</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col">
    <nav class="container mx-auto px-4 flex items-center justify-between flex-wrap py-6">
        <div class="flex items-center flex-shrink-0 mr-6">
            <img src="your-logo.png" alt="Логотип" class="h-8 w-8">
        </div>
        <div id="nav-content" class="w-full flex-grow lg:flex lg:items-center lg:w-auto">
            <div class="text-2xl lg:flex-grow">
                <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white mr-8">Пункт 1</a>
                <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white mr-8">Пункт 2</a>
                <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white mr-8">Пункт 3</a>
                <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white">Пункт 4</a>
            </div>
            <div class="flex items-center space-x-4 mt-4 lg:mt-0">
                <a href="/login" class="text-sm px-4 py-2 border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white">Войти</a>
                <a href="/register" class="text-sm px-4 py-2 border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white">Зарегистрироваться</a>
            </div>
        </div>
    </nav>
    <header class="container mx-auto px-4 text-center my-8">
        <h1 class="text-4xl font-bold">Заголовок страницы</h1>
    </header>
    <main class="container mx-auto px-4 flex-grow"></main>
    <footer class="bg-gray-800 text-gray-400 p-4 text-center">&copy; 2023 Моя Платформа. Все права защищены. Лицензия XYZ.</footer>
</body>

</html>