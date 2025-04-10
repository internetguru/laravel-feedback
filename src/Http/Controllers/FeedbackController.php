<?php

namespace InternetGuru\LaravelFeedback\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use InternetGuru\LaravelCommon\Support\Helpers;
use InternetGuru\LaravelFeedback\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'email' => 'required|email',
        ]);

        Mail::to(config('feedback.email'))
            ->send(new FeedbackMail($validated));

        return back()->with('success', __('ig-feedback::modal.success_message') . Helpers::getEmailClientLink());
    }
}
