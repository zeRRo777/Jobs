@error($slot)
<p {{ $attributes->merge(['class' => 'mt-2 text-sm text-red-500']) }}>{{ $message }}</p>
@enderror