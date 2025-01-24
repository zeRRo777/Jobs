<div class="mb-4">
    <x-html.p class="mb-2">
        Многие функции приложения не доступны. Подтвердите регистрацию. При регистрации вам было отправлено письмо на почту. Нажмите на кнопку и вам будет отправлено письмо еще раз.
    </x-html.p>
    <x-button
        wire:click="verify"
        wire:loading.attr="disabled"
        type_component="button"
        type="button"
        class="text-sm px-4 py-2">Подтвердить регистрацию
    </x-button>
</div>