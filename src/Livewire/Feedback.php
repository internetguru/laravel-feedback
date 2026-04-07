<?php

namespace InternetGuru\LaravelFeedback\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InternetGuru\LaravelCommon\Support\Helpers;
use InternetGuru\LaravelFeedback\Notification\FeedbackNotification;
use InternetGuru\LaravelRecaptchaV3\Traits\WithRecaptcha;
use InvalidArgumentException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Feedback extends Component
{
    use WithRecaptcha;

    #[Locked]
    public string $id;

    #[Locked]
    public string|array $email;

    #[Locked]
    public string $name;

    #[Locked]
    public array $recipients = [];

    #[Locked]
    public string $pageUrl = '';

    public ?string $subject = null;

    public ?string $title = null;

    public ?string $description = null;

    public ?string $success = null;

    public ?string $submit = null;

    public array $fields = [];

    public array $formData = [];

    public bool $isOpen = false;

    public function mount(
        string $id,
        string|array $email,
        string $name,
        ?string $subject = null,
        ?string $title = null,
        ?string $description = null,
        ?string $success = null,
        ?string $submit = null,
        ?array $fields = null
    ) {
        $this->recipients = $this->parseRecipients($email, $name);
        $this->email = array_key_first($this->recipients);
        $this->name = $this->recipients[$this->email];

        try {
            Validator::make(
                data: ['id' => $id, 'recipients' => $this->recipients],
                rules: [
                    'id' => 'required|string|regex:/^[a-z][a-z0-9]*(-[a-z0-9]+)*$/',
                    'recipients' => 'required|array|min:1',
                    'recipients.*' => 'required|string|min:2|max:100',
                ]
            )->validate();
            Validator::make(
                data: ['emails' => array_keys($this->recipients)],
                rules: ['emails.*' => 'required|email:rfc|max:255'],
            )->validate();
        } catch (ValidationException $e) {
            $errors = collect($e->errors())->flatten()->implode(', ');
            throw new InvalidArgumentException('Feedback component error: '.$errors);
        }
        $this->id = $id;
        $this->pageUrl = url()->full();
        $this->subject = $subject ?? config('app.name').' '.$id;
        $this->title = $title ?? __('ig-feedback::layouts.modal.title');
        $this->submit = $submit ?? __('ig-feedback::fields.submit');
        $this->description = $description ?? __('ig-feedback::layouts.modal.description');
        $this->success = $success ?? __('ig-feedback::layouts.modal.success');

        $defaultFields = [
            ['name' => 'message', 'required' => true],
            ['name' => 'email', 'label' => __('ig-feedback::fields.email_optional')],
        ];

        $this->fields = $this->normalizeFields($fields ?? $defaultFields);
        $this->initializeFormData();
    }

    /**
     * Parse email recipients from string or array input.
     */
    protected function parseRecipients(string|array $email, string $name): array
    {
        if (is_array($email)) {
            $recipients = [];
            foreach ($email as $key => $value) {
                if (is_string($key) && filter_var($key, FILTER_VALIDATE_EMAIL)) {
                    $recipients[$key] = $value;
                } else {
                    $recipients[$value] = $name;
                }
            }

            return $recipients;
        }

        $emails = array_map('trim', explode(',', $email));

        $recipients = [];
        foreach ($emails as $e) {
            $recipients[$e] = $name;
        }

        return $recipients;
    }

    /**
     * Normalize fields by generating labels for duplicate names
     */
    protected function normalizeFields(array $fields): array
    {
        $nameCounts = [];
        $normalized = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? '';
            $isRequired = (bool) ($field['required'] ?? false);
            $config = config("ig-feedback.names.{$fieldName}", []);
            $field['autocomplete'] = $field['autocomplete'] ?? $config['autocomplete'] ?? 'off';

            if (! isset($nameCounts[$fieldName])) {
                $nameCounts[$fieldName] = 0;
            }
            $nameCounts[$fieldName]++;

            // convert to array if error messages are not array
            if (isset($field['error']) && ! is_array($field['error'])) {
                $field['error'] = ['*' => $field['error']];
            }
            if (isset($config['error_translation_key']) && ! is_array($config['error_translation_key'])) {
                $config['error_translation_key'] = ['*' => $config['error_translation_key']];
            }
            // update field error messages from config
            foreach ($config['error_translation_key'] ?? [] as $rule => $key) {
                if (! isset($field['error'][$rule])) {
                    $field['error'][$rule] = __($key);
                }
            }

            // Handle value translations
            if (isset($config['value_translation_key']) && is_array($config['value_translation_key'])) {
                $field['values'] = [];
                foreach ($config['value_translation_key'] as $val => $key) {
                    $field['values'][$val] = __($key);
                }
            }

            // Make sure checkbox / radio default values are translated
            if (in_array($config['type'] ?? '', ['checkbox', 'radio'])) {
                if (! isset($field['values'][0])) {
                    $field['values'][0] = __('ig-feedback::fields.no');
                }
                if (! isset($field['values'][1])) {
                    $field['values'][1] = __('ig-feedback::fields.yes');
                }
            }

            // Generate label if not provided
            if (! isset($field['label'])) {
                $labelKey = $config['label_translation_key'] ?? "ig-feedback::fields.{$fieldName}";
                $baseLabel = __($labelKey);

                // Add counter for duplicates
                if ($nameCounts[$fieldName] > 1) {
                    $field['label'] = "{$baseLabel} {$nameCounts[$fieldName]}";
                } else {
                    // Check if there will be duplicates
                    $totalCount = count(array_filter($fields, fn ($f) => ($f['name'] ?? '') === $fieldName));
                    if ($totalCount > 1) {
                        $field['label'] = "{$baseLabel} 1";
                    } else {
                        $field['label'] = $baseLabel;
                    }
                }
            }

            if (! $isRequired) {
                $field['label'] .= ' ('.__('ig-feedback::fields.optional').')';
            }

            // Set default fallback if not provided
            if (! isset($field['fallback'])) {
                $field['fallback'] = __('ig-feedback::fields.not_provided');
            }

            $normalized[] = $field;
        }

        return $normalized;
    }

    /**
     * Initialize form data array based on fields
     */
    protected function initializeFormData(): void
    {
        foreach ($this->fields as $index => $field) {
            $fieldName = $field['name'] ?? '';
            $config = config("ig-feedback.names.{$fieldName}", []);
            $options = $field['options'] ?? $config['options'] ?? [];
            $useOptionKeys = $field['useoptionkeys'] ?? $config['useoptionkeys'] ?? false;

            $this->formData[$index] = $this->getFirstOptionValue($options, $useOptionKeys);
            $fieldKey = "formData.{$index}";
            $this->fields[$index]['key'] = $fieldKey;
        }

        // Pre-fill name and email if logged user
        if (auth()->check() && empty(array_filter($this->formData))) {
            $emailFilled = false;
            $fullnameFilled = false;

            foreach ($this->fields as $index => $field) {
                $fieldName = $field['name'] ?? '';

                // Fill first email field
                if ($fieldName === 'email' && ! $emailFilled) {
                    $this->formData[$index] = auth()->user()->email ?? '';
                    $emailFilled = true;
                }

                // Fill first fullname field
                if ($fieldName === 'fullname' && ! $fullnameFilled) {
                    $this->formData[$index] = auth()->user()->name ?? '';
                    $fullnameFilled = true;
                }
            }
        }
    }

    /**
     * Get the value of the first option
     */
    protected function getFirstOptionValue(array $options, bool $useOptionKeys = false): string
    {
        if (empty($options)) {
            return '';
        }

        $firstKey = array_key_first($options);
        $firstOption = $options[$firstKey];

        if (is_array($firstOption)) {
            return (string) ($firstOption['id'] ?? '');
        }

        if ($useOptionKeys) {
            return (string) $firstKey;
        }

        return (string) $firstOption;
    }

    #[On('open-ig-feedback')]
    public function openFeedback($id = null)
    {
        if ($id === $this->id) {
            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('ig-feedback-closed', id: $this->id);
    }

    public function send()
    {
        try {
            $this->verifyRecaptcha();
        } catch (ValidationException $e) {
            $this->dispatch('ig-message',
                type: 'error',
                message: __('recaptchav3::messages.failed'),
            );

            return;
        }

        // Build validation rules and messages dynamically based on fields
        $rules = [];
        $messages = [];
        foreach ($this->fields as $index => $field) {
            $fieldName = $field['name'] ?? '';
            $isRequired = $field['required'] ?? false;
            $config = config("ig-feedback.names.{$fieldName}", []);
            $validation = $config['validation'] ?? 'string|max:255';
            $rules[$field['key']] = $isRequired ? "required|{$validation}" : "nullable|{$validation}";
            foreach ($field['error'] ?? [] as $rule => $message) {
                $messages["{$field['key']}.{$rule}"] = $message;
            }
        }

        $this->validate($rules, $messages);

        // Prepare data for email
        $emailData = [
            [
                'label' => $this->title.' ('.$this->id.')',
                'value' => $this->description,
                'name' => 'description',
            ],
            [
                'label' => __('ig-feedback::fields.url'),
                'value' => $this->pageUrl,
                'name' => 'url',
            ],
        ];
        foreach ($this->fields as $index => $field) {
            $value = $this->formData[$index] ?? null;
            $originalValue = $value;

            if (isset($field['values'])) {
                $normalizedKey = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

                if (isset($field['values'][$normalizedKey])) {
                    $value = $field['values'][$normalizedKey];
                }
            }

            if (! empty($value) && isset($field['value_suffix'])) {
                $value .= $field['value_suffix'];
            }

            if (empty($value) && $value !== '0' && $value !== 0) {
                $value = $field['fallback'];
            }

            $emailData[] = [
                'label' => $field['label'] ?? '',
                'value' => $value,
                'original_value' => $originalValue,
                'name' => $field['name'] ?? '',
                'key' => $field['key'] ?? '',
            ];
        }

        $recipients = $this->recipients;
        if (auth()->user()?->isAdmin()) {
            $recipients = [__('ig-common::layouts.provider.email') => __('ig-common::layouts.provider.name')];
        }

        Notification::route('mail', $recipients)->notify(
            (new FeedbackNotification($emailData, $this->subject))->locale(app()->getLocale())
        );

        // Reset form
        $this->initializeFormData();
        $this->dispatch('ig-message',
            type: 'success',
            message: $this->success.Helpers::getEmailClientLink(),
        );

        $this->dispatch('ig-feedback-sent', id: $this->id, fields: $emailData);

        // Close modal after successful send
        $this->closeModal();
    }

    public function render()
    {
        return view('ig-feedback::livewire.feedback');
    }
}
