<x-layouts.app>
    <x-slot:title>Главная</x-slot:title>
    <x-slot:header>Список компаний с наибольшим количесвом вакансий</x-slot:header>
    <x-company.list>
        @foreach ($companies as $company)
        <x-company.card :company="$company" :number="$loop->iteration" />
        @endforeach
    </x-company.list>
</x-layouts.app>