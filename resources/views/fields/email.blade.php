<x-ig::input
    type="email"
    name="formData.{{ $index }}"
    wire:model="formData.{{ $index }}"
    :required="$isRequired"
>
    {{ $fieldLabel }}{{ !$isRequired ? ' (' . __('feedback::fields.optional') . ')' : '' }}
</x-ig::input>
