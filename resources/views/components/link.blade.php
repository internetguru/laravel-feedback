@props([
    'form-id', // Available as $formId variable (kebab-case converted to camelCase)
])

<a
    {{ $attributes }}
    href="Javascript:void(0)"
    x-data
    x-on:click.prevent="Livewire.dispatch('openIgFeedback', {id: '{{ $formId }}'})"
>{{ $slot->isNotEmpty() ? $slot : __('ig-feedback::layouts.modal.link') }}</a>