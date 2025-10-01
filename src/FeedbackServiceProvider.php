<?php

namespace InternetGuru\LaravelFeedback;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class FeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/feedback.php', 'feedback');
    }

    public function boot()
    {
        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'ig-feedback');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'feedback');

        // Register Livewire component
        Livewire::component('ig-feedback', \InternetGuru\LaravelFeedback\Livewire\Feedback::class);

        // Publish assets
        $this->publishes([
            __DIR__ . '/../config/feedback.php' => config_path('feedback.php'),
        ], 'ig-feedback:config');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/feedback'),
        ], 'ig-feedback:views');
        $this->publishes([
            __DIR__ . '/../lang' => base_path('lang/vendor/ig-feedback'),
        ], 'ig-feedback:lang');
    }
}
