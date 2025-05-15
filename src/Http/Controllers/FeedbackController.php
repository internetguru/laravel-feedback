<?php

namespace InternetGuru\LaravelFeedback\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use InternetGuru\LaravelCommon\Contracts\ReCaptchaInterface;
use InternetGuru\LaravelCommon\Support\Helpers;
use InternetGuru\LaravelFeedback\Notification\FeedbackNotification;

class FeedbackController extends Controller
{
    public function send(Request $request, ReCaptchaInterface $recaptcha)
    {
        $recaptcha->validate($request);
        $validated = $request->validate([
            'message' => 'required|string',
            'email' => 'nullable|email',
        ]);

        User::make([
            'email' => config('feedback.email'),
            'name' => config('feedback.name')
        ])->notify(
            (new FeedbackNotification($validated))->locale(app()->getLocale())
        );

        return back()->with('success', __('ig-feedback::layouts.modal.success_message') . Helpers::getEmailClientLink());
    }
}
