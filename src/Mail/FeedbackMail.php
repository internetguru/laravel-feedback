<?php

namespace InternetGuru\LaravelFeedback\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;

    public function __construct(array $feedback)
    {
        $this->feedback = $feedback;
    }

    public function build()
    {
        $sendFromUrl = url()->full();

        return $this->subject(__('ig-feedback::email.subject', ['app_name' => config('app.name')]))
            ->view([
                'html' => 'feedback::emails.feedback-html',
                'text' => 'feedback::emails.feedback-plain',
            ])
            ->with(['sendFromUrl' => $sendFromUrl]);
    }
}
