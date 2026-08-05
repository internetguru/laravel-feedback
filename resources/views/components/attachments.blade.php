@props([
    'index',
    'field',
    'value' => null,
    'inputAttributes' => [],
])

@php
    $key = "formData.{$index}";
    $config = config("ig-feedback.names." . ($field['name'] ?? ''), []);
    $files = array_filter(
        is_array($value) ? $value : [],
        fn ($file) => $file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
    );
    $fileAttributes = array_intersect_key($inputAttributes, array_flip(['accept', 'capture', 'multiple', 'required']));
    $messages = array_merge($errors->get($key), \Illuminate\Support\Arr::flatten($errors->get("{$key}.*")));
    $maxFiles = preg_match('/max:(\d+)/', $config['validation'] ?? '', $matches) ? (int) $matches[1] : null;
    $maxSize = preg_match('/max:(\d+)/', $config['file_validation'] ?? '', $matches) ? (int) $matches[1] : null;
@endphp

<div class="mt-3" wire:key="{{ $key }}-attachments">
    <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>

    <input
        type="file"
        id="{{ $key }}"
        name="{{ $key }}"
        data-testid="input-{{ $key }}"
        wire:model="{{ $key }}"
        {{ $attributes->merge($fileAttributes)->merge(['class' => 'form-control' . ($messages ? ' is-invalid' : '')]) }}
    />

    @if ($maxFiles && $maxSize)
        <div class="form-text" data-testid="attachments-hint-{{ $index }}">
            @lang('ig-feedback::fields.attachments.hint', ['count' => $maxFiles, 'size' => round($maxSize / 1024)])
        </div>
    @endif

    <div wire:loading wire:target="{{ $key }}" class="form-text" data-testid="attachments-uploading-{{ $index }}">
        @lang('ig-feedback::fields.attachments.uploading')
    </div>

    @if ($files)
        <ul class="list-unstyled mt-2" data-testid="attachments-{{ $index }}">
            @foreach ($files as $fileIndex => $file)
                <li class="d-flex align-items-center justify-content-between" wire:key="{{ $key }}-file-{{ $fileIndex }}">
                    <span>
                        {{ $file->getClientOriginalName() }}
                        <small class="text-muted">({{ round($file->getSize() / 1024) }} kB)</small>
                    </span>
                    <button
                        type="button"
                        class="btn btn-sm btn-link"
                        wire:click="removeAttachment({{ $index }}, {{ $fileIndex }})"
                        data-testid="attachments-remove-{{ $index }}-{{ $fileIndex }}"
                        aria-label="@lang('ig-feedback::fields.attachments.remove')"
                    >&times;</button>
                </li>
            @endforeach
        </ul>
    @endif

    @foreach ($messages as $message)
        <span class="invalid-feedback d-block" role="alert" data-testid="input-error-{{ $key }}">
            <strong>{{ $message }}</strong>
        </span>
    @endforeach
</div>
