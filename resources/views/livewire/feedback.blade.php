<div>
    @if($isOpen)
        <!-- Modal -->
        <div class="modal fade show" id="{{ $id }}-modal" tabindex="-1"
            aria-labelledby="{{ $id }}-modal-label" aria-hidden="false" style="display: block;"
            wire:key="{{ $id }}-modal"
            x-data x-on:keydown.escape.window="$wire.closeModal()"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="modal-title" id="{{ $id }}-modal-label">{{ $title }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                        </div>

                        @if($description)
                            <p class="me-5">{{ $description }}</p>
                        @endif

                        @if($showSuccess)
                            <div class="alert alert-success" role="alert">
                                {{ __('feedback::messages.success') }}
                            </div>
                        @else
                            <form wire:submit.prevent="send" class="editable-skip">
                                @foreach($fields as $index => $field)
                                    @php
                                        $fieldName = $field['name'] ?? '';
                                        $fieldLabel = $field['label'] ?? '';
                                        $isRequired = $field['required'] ?? false;
                                        $config = config("feedback.names.{$fieldName}", []);
                                        $customView = $config['view'] ?? null;
                                    @endphp

                                    @if($customView && view()->exists($customView))
                                        @include($customView, [
                                            'field' => $field,
                                            'index' => $index,
                                            'fieldName' => $fieldName,
                                            'fieldLabel' => $fieldLabel,
                                            'isRequired' => $isRequired,
                                            'config' => $config,
                                        ])
                                    @else
                                        {{-- skipping unknown field name: {{ $fieldName }} --}}
                                    @endif
                                @endforeach

                                <x-ig::submit>
                                    {{ $submit ?? __('feedback::fields.submit') }}
                                </x-ig::submit>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
