@props([
    'form-id', // Available as $formId variable (kebab-case converted to camelCase)
])

<a
    {{ $attributes }}
    href="Javascript:void(0)"
    x-data
    x-on:click.prevent="Livewire.dispatch('openFeedback', {id: '{{ $formId }}'})"
>{{ $slot ?? $formId }}</a>