<x-ig::input
    type="textarea"
    name="formData.{{ $index }}"
    wire:model="formData.{{ $index }}"
    :required="$isRequired"
    rows="8"
>
    {{ $fieldLabel }}{{ !$isRequired ? ' (' . __('ig-feedback::fields.optional') . ')' : '' }}
</x-ig::input>
