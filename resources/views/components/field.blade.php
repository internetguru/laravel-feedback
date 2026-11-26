@props([
    'index',
    /** The form's id, so the fields of several forms on one page keep ids of their own. */
    'formId' => null,
    'field',
    'value' => null,
    'inputAttributes' => [],
])

<x-ig::input
    name="formData.{{ $index }}"
    :id="$formId ? $formId . '-' . $index : null"
    wire:model="formData.{{ $index }}"
    :attributes="new Illuminate\View\ComponentAttributeBag($inputAttributes)"
>{{ $field['label'] }}</x-ig::input>
