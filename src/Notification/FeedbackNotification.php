<?php

namespace InternetGuru\LaravelFeedback\Notification;

use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use InternetGuru\LaravelCommon\Notifications\BaseNotification;

class FeedbackNotification extends BaseNotification
{
    /**
     * @param  array<int, array{disk: string, path: string, name: string, mime: string}>  $attachments
     */
    public function __construct(
        public array $feedback,
        public string $subject,
        public array $attachments = [],
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

        foreach ($this->attachments as $attachment) {
            $message->attach(
                Attachment::fromStorageDisk($attachment['disk'], $attachment['path'])
                    ->as($attachment['name'])
                    ->withMime($attachment['mime'])
            );
        }

        $email = collect($this->feedback)->firstWhere('name', 'email')['original_value'] ?? null;
        $fullname = collect($this->feedback)->firstWhere('name', 'fullname')['original_value'] ?? null;

        if (! empty($email)) {
            $message = $message->replyTo($email, $fullname);
        }

        return $message;
    }
}
