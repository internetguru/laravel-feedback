<?php

namespace InternetGuru\LaravelFeedback\View\Components;

use Illuminate\View\Component;

class Feedback extends Component
{
    public function render()
    {
        return view('feedback::components.feedback');
    }
}
