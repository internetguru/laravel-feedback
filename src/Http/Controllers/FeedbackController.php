<?php

namespace InternetGuru\LaravelFeedback\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use InternetGuru\LaravelCommon\Contracts\ReCaptchaInterface;
use InternetGuru\LaravelCommon\Support\Helpers;
use InternetGuru\LaravelFeedback\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function send(Request $request, ReCaptchaInterface $recaptcha)
    {
        $recaptcha->validate($request);
        $validated = $request->validate([
            'message' => 'required|string',
            'email' => 'nullable|email',
        ]);

        Mail::to(config('feedback.email'))
            ->send(new FeedbackMail($validated));

        return back()->with('success', __('ig-feedback::layouts.modal.success_message') . Helpers::getEmailClientLink());
    }
}
