@props([
    'formId',
])

<button
    {{ $attributes }}
    type="button"
    x-data
    x-on:click.prevent="Livewire.dispatch('openFeedback', {id: '{{ $formId }}'})"
>{{ $slot ?? $formId }}</button>
