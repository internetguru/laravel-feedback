<?php

namespace InternetGuru\LaravelFeedback\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use InternetGuru\LaravelCommon\Contracts\ReCaptchaInterface;
use InternetGuru\LaravelCommon\Support\Helpers;
use InternetGuru\LaravelFeedback\Notification\FeedbackNotification;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Feedback extends Component
{
    public $title = null;
    public $submit = null;
    public $subject = null;
    public $formName = '';
    public $formEmail = '';
    public $formPhone = '';
    public $formNote = '';
    public $internalId;

    public $nameVisibility = 'required';
    public $emailVisibility = 'required';
    public $phoneVisibility = 'required';
    public $noteVisibility = 'required';

    public function mount(
        $title = null,
        $submit = null,
        $subject = null,
        $name = 'required',
        $email = 'required',
        $phone = 'required',
        $note = 'required'
    ) {
        $this->title = $title;
        $this->submit = $submit;
        $this->subject = $subject;
        $this->nameVisibility = $name;
        $this->emailVisibility = $email;
        $this->phoneVisibility = $phone;
        $this->noteVisibility = $note;
        $this->internalId = 'feedback_' . uniqid();

        // Pre-fill email if user is authenticated
        if (auth()->check() && $this->emailVisibility !== 'hidden') {
            $this->formEmail = auth()->user()->email ?? '';
        }
    }

    public function send(ReCaptchaInterface $recaptcha)
    {
        $recaptcha->validate(request());

        $rules = [];
        $data = [];

        // Build validation rules dynamically based on field visibility
        if ($this->nameVisibility !== 'hidden') {
            $rules['name'] = $this->nameVisibility === 'required' ? 'required|string|max:255' : 'nullable|string|max:255';
            $data['name'] = $this->formName;
        }

        if ($this->emailVisibility !== 'hidden') {
            $rules['email'] = $this->emailVisibility === 'required' ? 'required|email|max:255' : 'nullable|email|max:255';
            $data['email'] = $this->formEmail;
        }

        if ($this->phoneVisibility !== 'hidden') {
            $rules['phone'] = $this->phoneVisibility === 'required' ? 'required|string|max:255' : 'nullable|string|max:255';
            $data['phone'] = $this->formPhone;
        }

        if ($this->noteVisibility !== 'hidden') {
            $rules['note'] = $this->noteVisibility === 'required' ? 'required|string' : 'nullable|string';
            $data['note'] = $this->formNote;
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $this->addError('validation', $validator->errors()->first());
            return;
        }

        $validated = $validator->validated();

        // If empty set tp '-'
        foreach($validated as $key => $value) {
            if (empty($value)) {
                $validated[$key] = '-';
            }
        }

        User::make([
            'email' => config('feedback.email'),
            'name' => config('feedback.name')
        ])->notify(
            (new FeedbackNotification($validated, $this->subject))->locale(app()->getLocale())
        );

        // Reset form
        $this->reset(['formName', 'formEmail', 'formPhone', 'formNote']);

        // Pre-fill email again if user is authenticated
        if (auth()->check() && $this->emailVisibility !== 'hidden') {
            $this->formEmail = auth()->user()->email ?? '';
        }

        $this->dispatch('ig-message',
            type: 'success',
            message: __('ig-feedback::layouts.modal.success_message') . Helpers::getEmailClientLink(),
        );
        $this->js('feedbackSent', ['internalId' => $this->internalId]);
    }

    public function render()
    {
        return view('feedback::livewire.feedback');
    }
}
