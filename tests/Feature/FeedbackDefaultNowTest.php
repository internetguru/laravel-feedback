<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Notification;
use InternetGuru\LaravelFeedback\Livewire\Feedback;
use Livewire\Livewire;
use Tests\TestCase;

class FeedbackDefaultNowTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('ig-feedback.names.visit_date', ['type' => 'date', 'validation' => 'date']);
        $app['config']->set('ig-feedback.names.visit_at', ['type' => 'datetime-local', 'validation' => 'date']);
        $app['config']->set('ig-feedback.names.visit_time', ['type' => 'time', 'validation' => 'string']);
    }

    private function params(array $fields): array
    {
        return [
            'id' => 'complaints-form',
            'email' => 'support@example.com',
            'name' => 'Support Team',
            'fields' => $fields,
        ];
    }

    public function test_an_opted_in_field_starts_with_the_current_date_or_time_in_its_format()
    {
        $this->travelTo('2026-11-14 10:15:00');

        Livewire::test(Feedback::class, $this->params([
            ['name' => 'visit_date', 'default_now' => true],
            ['name' => 'visit_at', 'default_now' => true],
            ['name' => 'visit_time', 'default_now' => true],
        ]))
            ->assertSet('formData.0', '2026-11-14')
            ->assertSet('formData.1', '2026-11-14T10:15')
            ->assertSet('formData.2', '10:15');
    }

    public function test_a_date_field_stays_empty_unless_it_opts_in()
    {
        Livewire::test(Feedback::class, $this->params([['name' => 'visit_date']]))
            ->assertSet('formData.0', '');
    }

    public function test_the_opt_in_can_come_from_the_field_definition()
    {
        config()->set('ig-feedback.names.visit_date.default_now', true);
        $this->travelTo('2026-11-14 10:00:00');

        Livewire::test(Feedback::class, $this->params([['name' => 'visit_date']]))
            ->assertSet('formData.0', '2026-11-14')
            ->assertDontSeeHtml('default_now');
    }

    public function test_the_field_is_filled_again_after_sending()
    {
        Notification::fake();
        $this->travelTo('2026-11-14 10:00:00');

        Livewire::test(Feedback::class, $this->params([
            ['name' => 'message', 'required' => true],
            ['name' => 'visit_date', 'default_now' => true],
        ]))
            ->set('formData.0', 'Hello')
            ->set('formData.1', '2026-11-01')
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('formData.1', '2026-11-14');
    }
}
