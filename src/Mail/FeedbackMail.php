<?php

namespace InternetGuru\LaravelFeedback\Mail;

use InternetGuru\LaravelCommon\Mail\IgMailable;

class FeedbackMail extends IgMailable
{
    public $feedback;

    public function __construct(array $feedback)
    {
        $this->feedback = $feedback;
    }

    public function build()
    {
        $sendFromUrl = url()->previous();

        $email = $this->subject(__('ig-feedback::layouts.email.subject', ['app_www' => config('app.www')]))
            ->view([
                'html' => 'feedback::emails.feedback-html',
                'text' => 'feedback::emails.feedback-plain',
            ])
            ->with(['sendFromUrl' => $sendFromUrl]);

        if (isset($this->feedback['email'])) {
            $email->replyTo($this->feedback['email']);
        }

        return $email;
    }
}
