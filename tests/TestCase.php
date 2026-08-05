<?php

namespace Tests;

use Illuminate\Foundation\Application;
use InternetGuru\LaravelCommon\CommonServiceProvider;
use InternetGuru\LaravelFeedback\FeedbackServiceProvider;
use InternetGuru\LaravelRecaptchaV3\RecaptchaV3ServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            FeedbackServiceProvider::class,
            CommonServiceProvider::class,
            RecaptchaV3ServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.compiled', __DIR__.'/cache');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('mail.default', 'array');
        $app['config']->set('queue.default', 'sync');
    }
}
