<!-- Навигационная панель -->
<nav class="container mx-auto px-4 flex items-center justify-between flex-wrap py-6">
    <!-- Логотип -->
    <div class="flex items-center flex-shrink-0 mr-6">
        <x-logo />
    </div>

    <!-- Меню -->
    <x-menu.menu>
        <x-slot:menu>
            {{ $menu }}
        </x-slot:menu>
        <x-slot:navigate_buttons>
            {{ $navigate_buttons }}
        </x-slot:navigate_buttons>
    </x-menu.menu>
</nav>