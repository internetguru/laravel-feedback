@props([
    'formId',
])

<a
    {{ $attributes }}
    href="Javascript:void(0)"
    x-data
    x-on:click.prevent="Livewire.dispatch('openFeedback', {id: '{{ $formId }}'})"
>{{ $slot ?? $formId }}</a>