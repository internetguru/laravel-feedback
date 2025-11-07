<x-ig::input
    type="text"
    name="formData.{{ $index }}"
    wire:model="formData.{{ $index }}"
    :required="$isRequired"
>
    {{ $fieldLabel }}{{ !$isRequired ? ' (' . __('ig-feedback::fields.optional') . ')' : '' }}
</x-ig::input>
