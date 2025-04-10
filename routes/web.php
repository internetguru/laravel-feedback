<?php

use Illuminate\Support\Facades\Route;
use InternetGuru\LaravelFeedback\Http\Controllers\FeedbackController;

Route::post('/feedback/send', [FeedbackController::class, 'send'])->name('feedback.send');
