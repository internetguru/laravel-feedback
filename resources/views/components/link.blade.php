@props([
    'form-id',
])

<a
    {{ $attributes }}
    href="Javascript:void(0)"
    x-data
    x-on:click.prevent="Livewire.dispatch('openFeedback', {id: '{{ $attributes->get('form-id') }}'})"
>{{ $slot ?? $attributes->get('form-id') }}</a>