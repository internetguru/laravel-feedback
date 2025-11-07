<?php

namespace InternetGuru\LaravelFeedback\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
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
    public ?string $submit = null;
    public array $fields = [];

    public array $formData = [];
    public bool $isOpen = false;
    public bool $showSuccess = false;

    protected $listeners = ['openFeedback'];

    public function mount(
        string $id,
        string $email,
        string $name,
        ?string $subject = null,
        ?string $title = null,
        ?string $submit = null,
        ?array $fields = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->subject = $subject ?? __('ig-feedback::layouts.email.subject', ['app_www' => config('app.www')]);
        $this->title = $title ?? __('ig-feedback::layouts.modal.title');
        $this->submit = $submit;

        // Default fields if not provided
        if ($fields === null) {
            $this->fields = [
                ['name' => 'message', 'label' => __('ig-feedback::fields.message'), 'required' => true],
                ['name' => 'email', 'label' => __('ig-feedback::fields.email_contact')],
            ];
        } else {
            $this->fields = $this->normalizeFields($fields);
        }

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

            if (!isset($nameCounts[$fieldName])) {
                $nameCounts[$fieldName] = 0;
            }
            $nameCounts[$fieldName]++;

            // Generate label if not provided
            if (!isset($field['label'])) {
                $config = config("feedback.names.{$fieldName}", []);
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

        // Pre-fill email if user is authenticated
        if (auth()->check()) {
            foreach ($this->fields as $index => $field) {
                if (($field['name'] ?? '') === 'email' && empty($this->formData[$index])) {
                    $this->formData[$index] = auth()->user()->email ?? '';
                    break; // Only fill the first email field
                }
            }
        }
    }

    #[On('openFeedback')]
    public function openFeedback($id = null)
    {
        if ($id === $this->id) {
            $this->isOpen = true;
            $this->showSuccess = false;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->showSuccess = false;
    }

    public function send(ReCaptchaInterface $recaptcha)
    {
        $recaptcha->validate(request());

        // Build validation rules dynamically based on fields
        $rules = [];
        foreach ($this->fields as $index => $field) {
            $fieldName = $field['name'] ?? '';
            $isRequired = $field['required'] ?? false;

            $config = config("feedback.names.{$fieldName}", []);
            $validation = $config['validation'] ?? 'string|max:255';

            $fieldKey = "formData.{$index}";

            if ($isRequired) {
                $rules[$fieldKey] = "required|{$validation}";
            } else {
                $rules[$fieldKey] = "nullable|{$validation}";
            }
        }

        $this->validate($rules);

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
            message: __('ig-feedback::messages.success') . Helpers::getEmailClientLink(),
        );

        // Close modal after successful send
        $this->closeModal();
    }

    public function render()
    {
        return view('feedback::livewire.feedback');
    }
}
