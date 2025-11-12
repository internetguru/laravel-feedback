@props([
    'index',
    'field',
    'inputAttributes' => [],
])

<x-ig::input
    name="formData.{{ $index }}"
    wire:model="formData.{{ $index }}"
    :attributes="new Illuminate\View\ComponentAttributeBag($inputAttributes)"
>{{ $field['label'] }}</x-ig::input>
