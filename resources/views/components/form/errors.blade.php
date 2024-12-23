@if ($errors->any())
<div class="bg-red-100 bord text-red-700 p-4 rounded-lg">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif