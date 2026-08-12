@props([
    'form-id', // Available as $formId variable (kebab-case converted to camelCase)
])

<button
    {{ $attributes }}
    type="button"
    onclick="window.igModal.open('{{ $formId }}-modal')"
>{{ $slot->isNotEmpty() ? $slot : __('ig-feedback::layouts.modal.link') }}</button>
