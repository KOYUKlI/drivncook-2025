@props([
    'id' => null,
    'name',
    'checked' => false,
    'value' => 1,
    'label' => null,
])

<label class="inline-flex items-center space-x-2">
    <input
        type="checkbox"
        {{ $id ? 'id='.$id : '' }}
        name="{{ $name }}"
        value="{{ $value }}"
        @checked(old($name, $checked))
        class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500"
    />
    @if($label)
        <span class="text-sm text-gray-700">{{ $label }}</span>
    @endif
</label>
