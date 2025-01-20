<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    @vite(['resources/css/app.css'])
    <title>Приглашение на собеседование</title>
</head>

<body class="bg-gray-900 p-4">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-md p-6">
        <h1 class="text-4xl font-bold text-center text-white mb-6">Приглашение на собеседование</h1>
        <x-html.p class="mb-4">Уважаемый(ая) {{ $user->name }},</x-html.p>
        <x-html.p class="mb-4">
            Мы рады пригласить вас на собеседование на позицию <strong>{{ $vacancy->title }}</strong> в компании <strong>{{ $vacancy->company->name }}</strong>.
        </x-html.p>
        <x-html.p class="mb-4">
            Пожалуйста, сообщите нам о вашей доступности, чтобы мы могли назначить удобное время.
        </x-html.p>
        <x-html.p class="mb-4">
            С нетерпением ждем возможности встретиться с вами.
        </x-html.p>
        <x-html.p>С уважением,</x-html.p>
        <x-html.p>{{ $admin->name }}</x-html.p>
        <x-html.p>{{ $admin->email }}</x-html.p>
    </div>
</body>

</html>