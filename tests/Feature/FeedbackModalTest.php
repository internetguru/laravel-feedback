<?php

namespace Tests\Feature;

use InternetGuru\LaravelFeedback\Livewire\Feedback;
use Livewire\Livewire;
use Tests\TestCase;

class FeedbackModalTest extends TestCase
{
    private const PARAMS = [
        'id' => 'support-form',
        'email' => 'support@example.com',
        'name' => 'Support Team',
    ];

    public function test_closed_modal_is_rendered_hidden_so_it_can_be_opened_without_a_round_trip()
    {
        Livewire::test(Feedback::class, self::PARAMS)
            ->assertSet('isOpen', false)
            ->assertSeeHtml('id="support-form-modal" class="ig-modal d-none"')
            ->assertSeeHtml('window.igModal.close(\'support-form-modal\')')
            ->assertSee(__('ig-feedback::layouts.modal.title'));
    }

    public function test_open_modal_drops_the_hidden_class()
    {
        Livewire::test(Feedback::class, self::PARAMS)
            ->set('isOpen', true)
            ->assertSeeHtml('id="support-form-modal" class="ig-modal"')
            ->assertDontSeeHtml('class="ig-modal d-none"');
    }

    public function test_the_open_and_close_events_keep_the_server_state_in_sync()
    {
        Livewire::test(Feedback::class, self::PARAMS)
            ->dispatch('open-ig-feedback', id: 'support-form')
            ->assertSet('isOpen', true)
            ->dispatch('close-ig-feedback', id: 'support-form')
            ->assertSet('isOpen', false);
    }

    public function test_events_for_another_form_are_ignored()
    {
        Livewire::test(Feedback::class, self::PARAMS)
            ->set('isOpen', true)
            ->dispatch('close-ig-feedback', id: 'other-form')
            ->assertSet('isOpen', true);
    }

    public function test_triggers_open_the_modal_with_plain_javascript()
    {
        $link = $this->blade('<x-ig-feedback::link form-id="support-form" />');
        $link->assertSee("window.igModal.open('support-form-modal')", false);
        $link->assertDontSee('Livewire.dispatch', false);

        $button = $this->blade('<x-ig-feedback::button form-id="support-form" />');
        $button->assertSee("window.igModal.open('support-form-modal')", false);
        $button->assertDontSee('Livewire.dispatch', false);
    }
}
