<?php

namespace InternetGuru\LaravelFeedback;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class FeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ig-feedback.php', 'ig-feedback');

        // Apply recursive merge for published config
        $publishedConfig = $this->app['config']->get('ig-feedback', []);
        $defaultConfig = require __DIR__.'/../config/ig-feedback.php';

        $this->app['config']->set('ig-feedback', array_replace_recursive($defaultConfig, $publishedConfig));
    }

    public function boot()
    {
        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ig-feedback');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ig-feedback');

        // Register Livewire component
        Livewire::component('ig-feedback', \InternetGuru\LaravelFeedback\Livewire\Feedback::class);

        // Publish assets
        $this->publishes([
            __DIR__.'/../config/ig-feedback.php' => config_path('ig-feedback.php'),
        ], 'ig-feedback:config');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/ig-feedback'),
        ], 'ig-feedback:views');
        $this->publishes([
            __DIR__.'/../lang' => base_path('lang/vendor/ig-feedback'),
        ], 'ig-feedback:lang');
    }
}
