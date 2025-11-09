<x-ig::input
    type="textarea"
    name="formData.{{ $index }}"
    wire:model="formData.{{ $index }}"
    :required="$isRequired"
    rows="8"
>
    {{ $fieldLabel }}{{ !$isRequired ? ' (' . __('feedback::fields.optional') . ')' : '' }}
</x-ig::input>
