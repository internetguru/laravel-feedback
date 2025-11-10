<?php

namespace InternetGuru\LaravelFeedback\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use InternetGuru\LaravelCommon\Mail\MailMessage as IgMailMessage;
use InternetGuru\LaravelUser\Models\TokenAuth;

class FeedbackNotification extends Notification
{
    use Queueable;

    public function __construct(
        public array $feedback,
        public ?string $subject = null,
    ) {}

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = (new IgMailMessage())
            ->subject($this->subject ?? __('ig-feedback::layouts.email.subject', ['app_www' => config('app.www')]))
            ->view(
                [
                    'html' => 'ig-feedback::emails.feedback-html',
                    'text' => 'ig-feedback::emails.feedback-plain',
                ],
                [
                    'feedback' => $this->feedback,
                ],
            );
        if (isset($this->feedback['email']) && filter_var($this->feedback['email'], FILTER_VALIDATE_EMAIL)) {
            $message->replyTo($this->feedback['email'], $this->feedback['fullname'] ?? null);
        }

        return $message;
    }
}
