@props([
    'form-id', // Available as $formId variable (kebab-case converted to camelCase)
])

<button
    {{ $attributes }}
    type="button"
    x-data
    x-on:click.prevent="Livewire.dispatch('openIgFeedback', {id: '{{ $formId }}'})"
>{{ $slot->isNotEmpty() ? $slot : $formId }}</a>