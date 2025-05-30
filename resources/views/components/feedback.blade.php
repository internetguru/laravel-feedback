<div class="feedback-container">
    <!-- Feedback Link -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal" class="feedback-link">
        {{ __('ig-feedback::layouts.modal.link_text') }}
    </a>

    <!-- Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">{{ __('ig-feedback::layouts.modal.title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-ig::form action="{{ route('feedback.send') }}" id="feedbackForm" class="editable-skip">
                        <x-ig::input
                            type="textarea"
                            name="message"
                            rows="10"
                            required
                        >
                            {{ __('ig-feedback::layouts.form.message') }}
                        </x-ig::input>

                        <x-ig::input
                            type="email"
                            name="email"
                            value="{{ auth()->check() ? auth()->user()->email : '' }}"
                        >
                            {{ __('ig-feedback::layouts.form.email') }}
                        </x-ig::input>

                        <x-ig::submit>
                            {{ __('ig-feedback::layouts.form.submit') }}
                        </x-ig::submit>
                    </x-ig::form>
                </div>
            </div>
        </div>
    </div>
</div>
