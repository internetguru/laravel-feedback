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

    public function __construct(public array $feedback) {}

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new IgMailMessage())
            ->subject(__('ig-feedback::layouts.email.subject', ['app_www' => config('app.www')]))
            ->view(
                [
                    'html' => 'feedback::emails.feedback-html',
                    'text' => 'feedback::emails.feedback-plain',
                ],
                [
                    'feedback' => $this->feedback,
                ],
            );
    }
}
