<?php

namespace InternetGuru\LaravelFeedback\Livewire;

use App\Models\User;
use InternetGuru\LaravelCommon\Contracts\ReCaptchaInterface;
use InternetGuru\LaravelCommon\Support\Helpers;
use InternetGuru\LaravelFeedback\Notification\FeedbackNotification;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Feedback extends Component
{
    #[Locked]
    public string $id;

    #[Locked]
    public string $email;

    #[Locked]
    public string $name;

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
        string $email,
        string $name,
        ?string $subject = null,
        ?string $title = null,
        ?string $description = null,
        ?string $success = null,
        ?string $submit = null,
        ?array $fields = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->subject = $subject ?? config('app.name') . ' ' . $id;
        $this->title = $title ?? __('ig-feedback::layouts.modal.title');
        $this->submit = $submit ?? __('ig-feedback::fields.submit');
        $this->description = $description ?? __('ig-feedback::layouts.modal.description');
        $this->success = $success ?? __('ig-feedback::layouts.modal.success');

        $defaultFields = [
            ['name' => 'message', 'required' => true],
            ['name' => 'email', 'label' => __('ig-feedback::fields.email_optional') ],
        ];

        $this->fields = $this->normalizeFields($fields ?? $defaultFields);
        $this->initializeFormData();
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

            if (!isset($nameCounts[$fieldName])) {
                $nameCounts[$fieldName] = 0;
            }
            $nameCounts[$fieldName]++;

            if (!isset($field['error']) && isset($config['error_translation_key'])) {
                $field['error'] = __($config['error_translation_key']);
            }

            // Generate label if not provided
            if (!isset($field['label'])) {
                $labelKey = $config['label_translation_key'] ?? "ig-feedback::fields.{$fieldName}";
                $baseLabel = __($labelKey);

                // Add counter for duplicates
                if ($nameCounts[$fieldName] > 1) {
                    $field['label'] = "{$baseLabel} {$nameCounts[$fieldName]}";
                } else {
                    // Check if there will be duplicates
                    $totalCount = count(array_filter($fields, fn($f) => ($f['name'] ?? '') === $fieldName));
                    if ($totalCount > 1) {
                        $field['label'] = "{$baseLabel} 1";
                    } else {
                        $field['label'] = $baseLabel;
                    }
                }
            }

            if (! $isRequired) {
                $field['label'] .= ' (' . __('ig-feedback::fields.optional') . ')';
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
            $this->formData[$index] = '';
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

    #[On('openIgFeedback')]
    public function openFeedback($id = null)
    {
        if ($id === $this->id) {
            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function send(ReCaptchaInterface $recaptcha)
    {
        $recaptcha->validate(request());

        // Build validation rules dynamically based on fields
        $rules = [];
        $messages = [];
        foreach ($this->fields as $index => $field) {
            $fieldName = $field['name'] ?? '';
            $isRequired = $field['required'] ?? false;

            $config = config("ig-feedback.names.{$fieldName}", []);
            $validation = $config['validation'] ?? 'string|max:255';
            $fieldKey = "formData.{$index}";
            $rules[$fieldKey] = $isRequired ? "required|{$validation}" : "nullable|{$validation}";
            if (isset($field['error'])) {
                $messages[$fieldKey] = $field['error'];
            }
        }

        $this->validate($rules, $messages);

        // Prepare data for email
        $emailData = [];
        foreach ($this->fields as $index => $field) {
            $emailData[] = [
                'label' => $field['label'] ?? '',
                'value' => $this->formData[$index] ?? '-',
                'name' => $field['name'] ?? '',
            ];
        }

        User::make([
            'email' => $this->email,
            'name' => $this->name
        ])->notify(
            (new FeedbackNotification($emailData, $this->subject))->locale(app()->getLocale())
        );

        // Reset form
        $this->initializeFormData();
        $this->dispatch('ig-message',
            type: 'success',
            message: $this->success . Helpers::getEmailClientLink(),
        );

        // Close modal after successful send
        $this->closeModal();
    }

    public function render()
    {
        return view('ig-feedback::livewire.feedback');
    }
}
