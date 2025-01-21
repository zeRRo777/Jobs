@props(['vacancy' => null])

@if (!empty($vacancy))
<div class="bg-gray-700 p-4 rounded-lg relative">

    <label class="flex items-center">
        <input type="checkbox" name="vacancies[]" value="{{$vacancy['id']}}" class="form-checkbox text-blue-500" />
        <span class="ml-2 text-white">{{ $vacancy['title'] }}</span>
    </label>

    @if ($vacancy['isOffered'])
    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">Уже отправляли!</span>
    @endif
    @if ($vacancy['isLiked'])
    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">Пользователь откликнулся!</span>
    @endif
</div>
@endif