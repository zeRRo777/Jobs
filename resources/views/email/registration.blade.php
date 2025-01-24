<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    @vite(['resources/css/app.css'])
    <title>Подтверждение регистрации</title>
</head>

<body class="bg-gray-900 p-4">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-md p-6">
        <h1 class="text-4xl font-bold text-center text-white mb-6">Подтрверждение регистрации</h1>
        <x-html.p class="mb-4">Уважаемый(ая) {{ $user->name }},</x-html.p>
        <x-html.p class="mb-4">
            Спасибо за регистрацию на нашем сайте! Мы рады приветствовать вас в нашем сообществе.
        </x-html.p>
        <x-html.p class="mb-4">
            Пожалуйста, подтвердите свой адрес электронной почты, нажав на кнопку ниже:
        </x-html.p>
        <div class="mb-4">
            <x-button :href="$verificationUrl" type_component="link" class="text-sm px-4 py-2">Подтвердить электронную почту</x-button>
        </div>
        <x-html.p class="mb-4">
            Если вы не регистрировались у нас, просто игнорируйте это письмо.
        </x-html.p>
        <x-html.p>С уважением,</x-html.p>
        <x-html.p>Команда сайта Jobs</x-html.p>
    </div>
</body>

</html>