<?php

namespace InternetGuru\LaravelFeedback\View\Components;

use Illuminate\View\Component;

class Feedback extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $linkText = null,
        public string $modalTitle = null
    ) {
        $this->linkText = $linkText ?? config('feedback.modal.link_text');
        $this->modalTitle = $modalTitle ?? config('feedback.modal.title');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('feedback::components.feedback');
    }
}
