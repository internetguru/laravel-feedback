<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Notification;
use InternetGuru\LaravelFeedback\Livewire\Feedback;
use InternetGuru\LaravelFeedback\Notification\FeedbackNotification;
use Livewire\Livewire;
use Tests\TestCase;

class FeedbackValidationTest extends TestCase
{
    private const PARAMS = [
        'id' => 'contact-form',
        'email' => 'support@example.com',
        'name' => 'Support Team',
        'fields' => [
            ['name' => 'message', 'required' => true],
            ['name' => 'email', 'required' => true],
            ['name' => 'phone'],
        ],
    ];

    public function test_an_error_names_the_field_by_its_label()
    {
        $errors = Livewire::test(Feedback::class, self::PARAMS)
            ->set('formData.0', 'Hello')
            ->set('formData.1', 'not an address')
            ->call('send')
            ->errors();

        $this->assertStringContainsString(__('ig-feedback::fields.email'), $errors->first('formData.1'));
        $this->assertStringNotContainsString('form data', $errors->first('formData.1'));
    }

    public function test_an_optional_field_is_named_without_the_optional_mark()
    {
        $component = Livewire::test(Feedback::class, self::PARAMS);

        $this->assertSame(__('ig-feedback::fields.phone'), $component->get('fields.2.attribute'));
    }

    public function test_an_address_is_accepted_without_looking_its_domain_up_while_testing()
    {
        Notification::fake();

        Livewire::test(Feedback::class, self::PARAMS)
            ->set('formData.0', 'Hello')
            ->set('formData.1', 'someone@no-such-domain.invalid')
            ->call('send')
            ->assertHasNoErrors();

        Notification::assertSentOnDemand(FeedbackNotification::class);
    }
}
