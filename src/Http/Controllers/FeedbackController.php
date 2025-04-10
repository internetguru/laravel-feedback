<?php

namespace InternetGuru\LaravelFeedback\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use InternetGuru\LaravelFeedback\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => config('feedback.form.subject.required') ? 'required|string|max:255' : 'nullable|string|max:255',
            'message' => config('feedback.form.message.required') ? 'required|string' : 'nullable|string',
            'email' => config('feedback.form.email.required') ? 'required|email' : 'nullable|email',
        ]);

        Mail::to(config('feedback.email'))
            ->send(new FeedbackMail($validated));

        return back()->with('success', config('feedback.modal.success_message'));
    }
}
