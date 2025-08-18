<?php

namespace InternetGuru\LaravelFeedback;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
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
        Route::middleware('web')->group(__DIR__ . '/../routes/web.php');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'ig-feedback');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'feedback');

        // Register blade components
        Blade::component('ig-feedback', \InternetGuru\LaravelFeedback\View\Components\Feedback::class);

        // Publish assets
        $this->publishes([
            __DIR__ . '/../config/feedback.php' => config_path('feedback.php'),
            __DIR__ . '/../resources/views' => resource_path('views/vendor/feedback'),
            __DIR__ . '/../resources/js' => public_path('vendor/feedback'),
        ], 'ig-feedback:assets');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../lang' => base_path('lang/vendor/ig-feedback'),
        ], 'ig-feedback:lang');
    }
}
