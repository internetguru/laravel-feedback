@props([
    'form-id', // Available as $formId variable (kebab-case converted to camelCase)
])

<a
    {{ $attributes }}
    href="Javascript:void(0)"
    onclick="window.igModal.open('{{ $formId }}-modal'); return false;"
>{{ $slot->isNotEmpty() ? $slot : __('ig-feedback::layouts.modal.link') }}</a>
