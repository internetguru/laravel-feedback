<div>
    {{--
        The modal is rendered hidden, so a trigger shows it without a round trip; the
        component keeps `isOpen` in sync and opens the form when the URL points at it.
    --}}
    <x-ig::modal
        :id="$id . '-modal'"
        :title="$title"
        :open="$isOpen"
        :hash="$id"
        wire-open="isOpen"
        wire:key="{{ $id }}-modal"
    >
        @if($description)
            <p class="me-5">{{ $description }}</p>
        @endif

        <x-ig::form
            wire:submit.prevent="send"
            class="editable-skip"
        >
            {{--
                Always rendered: the modal is opened client-side, so gating this on `$isOpen`
                would only add the token after the first submit had already been rejected.
                The snippet itself fetches the token lazily, on the first interaction with
                the form, so a closed modal still costs no token on page load.
            --}}
            @recaptchaLivewire('feedback_send')
            @foreach($fields as $index => $field)
                @php
                    $config = config("ig-feedback.names." . $field['name'] , []);
                    $attributes = array_diff_key(
                        array_merge($config, $field),
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
                    :value="$formData[$index] ?? null"
                    :inputAttributes="$attributes"
                />
            @endforeach

            <x-ig::submit>
                <x-ig::admin-button-text>{{ $submit }}</x-ig::admin-button-text>
            </x-ig::submit>
        </x-ig::form>
    </x-ig::modal>
</div>
