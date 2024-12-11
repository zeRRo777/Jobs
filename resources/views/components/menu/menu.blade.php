<!-- Меню -->
<div id="nav-content" class="w-full flex-grow lg:flex lg:items-center lg:w-auto">
    @if (!empty($menu))
    <div class="text-2xl lg:flex-grow">
        {{ $menu }}
    </div>
    @endif

    @if (!empty($navigate_buttons))
    <div class="flex items-center space-x-4 mt-4 lg:mt-0">
        {{ $navigate_buttons }}
    </div>
    @endif
</div>