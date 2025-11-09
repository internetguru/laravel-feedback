@props([
    'form-id',
])

<button
    {{ $attributes }}
    type="button"
    x-data
    x-on:click.prevent="Livewire.dispatch('openFeedback', {id: '{{ $attributes->get('form-id') }}'})"
>{{ $slot ?? $attributes->get('form-id') }}</button>
