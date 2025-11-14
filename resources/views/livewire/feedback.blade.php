<div>
    @if($isOpen)
        <!-- Modal -->
        <div class="modal fade show" id="{{ $id }}-modal" tabindex="-1"
            aria-labelledby="{{ $id }}-modal-label" aria-hidden="false" style="display: block;"
            wire:key="{{ $id }}-modal"
            x-data x-on:keydown.escape.window="$wire.closeModal()"
            x-init="window.location.hash = '{{ $id }}'"
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

                        <form wire:submit.prevent="send" class="editable-skip">
                            @foreach($fields as $index => $field)
                                @php
                                    $config = config("ig-feedback.names." . $field['name'] , []);
                                    $attributes = array_diff_key(
                                        array_merge($field, $config),
                                        array_flip(config('ig-feedback.exclude_attributes', []))
                                    );
                                    $inputView = $config['input_view'] ?? 'ig-feedback::field';
                                @endphp
                                @if(empty($config))
                                    {!! "<!-- Field config not found for {$field['name']} -->" !!}
                                    @continue
                                @endif

                                <x-dynamic-component
                                    :component="$inputView"
                                    :index="$index"
                                    :field="$field"
                                    :inputAttributes="$attributes"
                                />
                            @endforeach

                            <x-ig::submit>
                                {{ $submit }}
                            </x-ig::submit>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <script>
        // Check if URL hash matches this feedback ID on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash === '#{{ $id }}') {
                Livewire.dispatch('open-ig-feedback', {id: '{{ $id }}'});
            }
        });
        document.addEventListener('livewire:init', () => {
            // Remove hash when modal closes
            Livewire.on('ig-feedback-closed', (event) => {
                if (event.id === '{{ $id }}' && window.location.hash === '#{{ $id }}') {
                    history.replaceState(null, null, ' ');
                }
            });
        });
    </script>
</div>
