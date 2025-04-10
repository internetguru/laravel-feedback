<?php

namespace InternetGuru\LaravelFeedback;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/feedback.php', 'feedback');
    }

    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'feedback');

        // Register blade components
        Blade::component('feedback', \InternetGuru\LaravelFeedback\View\Components\Feedback::class);

        // Publish assets
        $this->publishes([
            __DIR__ . '/../config/feedback.php' => config_path('feedback.php'),
            __DIR__ . '/../resources/views' => resource_path('views/vendor/feedback'),
            __DIR__ . '/../resources/js' => public_path('vendor/feedback'),
        ]);
    }
}
