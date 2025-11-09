@props([
    'form-id', // Available as $formId variable (kebab-case converted to camelCase)
])

<button
    {{ $attributes }}
    type="button"
    x-data
    x-on:click.prevent="Livewire.dispatch('openFeedback', {id: '{{ $formId }}'})"
>{{ $slot ?? $formId }}</button>
