<?php

namespace InternetGuru\LaravelFeedback\Notification;

use Illuminate\Notifications\Messages\MailMessage;
use InternetGuru\LaravelCommon\Notifications\BaseNotification;

class FeedbackNotification extends BaseNotification
{
    public function __construct(
        public array $feedback,
        public string $subject,
    ) {
        parent::__construct();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = parent::toMail($notifiable)
            ->subject($this->subject)
            ->view(
                [
                    'html' => 'ig-feedback::emails.feedback-html',
                    'text' => 'ig-feedback::emails.feedback-plain',
                ],
                [
                    'feedback' => $this->feedback,
                ],
            );

        $email = collect($this->feedback)->firstWhere('name', 'email')['value'] ?? null;
        $fullname = collect($this->feedback)->firstWhere('name', 'fullname')['value'] ?? null;

        if (! empty($email)) {
            $message = $message->replyTo($email, $fullname);
        }

        return $message;
    }
}
