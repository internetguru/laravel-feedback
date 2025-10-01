<div class="feedback-container">
    <!-- Feedback Link -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#{{ $internalId }}Modal" class="feedback-link">
        {{ $title ?? __('ig-feedback::layouts.modal.link_text') }}
    </a>

    <!-- Modal -->
    <div class="modal fade" id="{{ $internalId }}Modal" tabindex="-1" aria-labelledby="{{ $internalId }}ModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $internalId }}ModalLabel">{{ $title ?? __('ig-feedback::layouts.modal.link_text') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->has('validation'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('validation') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="send" id="{{ $internalId }}Form" class="editable-skip">
                        @if ($nameVisibility !== 'hidden')
                            <x-ig::input
                                type="text"
                                name="name"
                                wire:model="formName"
                                :required="$nameVisibility === 'required'"
                            >
                                {{ __('ig-feedback::layouts.form.name') }}{{ $nameVisibility === 'optional' ? ' (' . __('ig-feedback::layouts.form.optional') . ')' : '' }}
                            </x-ig::input>
                        @endif

                        @if ($emailVisibility !== 'hidden')
                            <x-ig::input
                                type="email"
                                name="email"
                                wire:model="formEmail"
                                :required="$emailVisibility === 'required'"
                            >
                                {{ __('ig-feedback::layouts.form.email') }}{{ $emailVisibility === 'optional' ? ' (' . __('ig-feedback::layouts.form.optional') . ')' : '' }}
                            </x-ig::input>
                        @endif

                        @if ($phoneVisibility !== 'hidden')
                            <x-ig::input
                                type="tel"
                                name="phone"
                                wire:model="formPhone"
                                :required="$phoneVisibility === 'required'"
                            >
                                {{ __('ig-feedback::layouts.form.phone') }}{{ $phoneVisibility === 'optional' ? ' (' . __('ig-feedback::layouts.form.optional') . ')' : '' }}
                            </x-ig::input>
                        @endif

                        @if ($noteVisibility !== 'hidden')
                            <x-ig::input
                                type="textarea"
                                name="note"
                                rows="10"
                                wire:model="formNote"
                                :required="$noteVisibility === 'required'"
                            >
                                {{ __('ig-feedback::layouts.form.note') }}{{ $noteVisibility === 'optional' ? ' (' . __('ig-feedback::layouts.form.optional') . ')' : '' }}
                            </x-ig::input>
                        @endif

                        <x-ig::submit>
                            {{ $submit ?? __('ig-feedback::layouts.form.submit') }}
                        </x-ig::submit>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@script
<script>
    $js('feedbackSent', (data) => {
        let internalId = data.internalId || 'feedback';
        let close = document.querySelector('#' + internalId + 'Modal .btn-close');
        if (close) {
            close.click();
        }
    });
</script>
@endscript
