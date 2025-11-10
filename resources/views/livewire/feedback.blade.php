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
                                        $config = config("feedback.names." . $field['name'], []);
                                        $attributes = array_diff_key(
                                            array_merge($field, $config),
                                            array_flip(config('feedback.exclude_attributes', []))
                                        );
                                    @endphp
                                    @if(empty($config))
                                        {!! "<!-- Field config not found for {$field['name']} -->" !!}
                                        @continue
                                    @endif

                                    <x-ig::input
                                        name="formData.{{ $index }}"
                                        wire:model="formData.{{ $index }}"
                                        :attributes="new Illuminate\View\ComponentAttributeBag($attributes)"
                                    >{{ $field['label'] }}</x-ig::input>
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
