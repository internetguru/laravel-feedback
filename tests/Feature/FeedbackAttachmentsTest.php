<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use InternetGuru\LaravelFeedback\Livewire\Feedback;
use InternetGuru\LaravelFeedback\Notification\FeedbackNotification;
use Livewire\Livewire;
use Tests\TestCase;

class FeedbackAttachmentsTest extends TestCase
{
    private const PARAMS = [
        'id' => 'support-form',
        'email' => 'support@example.com',
        'name' => 'Support Team',
    ];

    public function test_attachments_field_is_part_of_the_default_form()
    {
        Livewire::test(Feedback::class, self::PARAMS)
            ->assertSet('fields.1.name', 'attachments')
            ->assertSet('formData.1', [])
            ->set('isOpen', true)
            ->assertSee(__('ig-feedback::fields.attachments'))
            ->assertSeeHtml('data-testid="input-formData.1"')
            ->assertSeeHtml('accept="image/jpeg,image/png,image/gif,image/webp,image/heic,application/pdf"')
            ->assertSeeHtml('multiple');
    }

    public function test_error_messages_are_resolved_with_the_configured_rule_parameters()
    {
        $errors = Livewire::test(Feedback::class, self::PARAMS)->get('fields.1.error');

        $this->assertSame('You can attach at most 3 files.', $errors['max']);
        $this->assertSame('Each attachment may be at most 5120 kB.', $errors['*.max']);

        foreach ($errors as $rule => $message) {
            $this->assertDoesNotMatchRegularExpression(
                '/(?<!:):[a-zA-Z_]\w*/',
                $message,
                "Unresolved placeholder in the {$rule} message"
            );
        }
    }

    public function test_explicit_fields_do_not_include_attachments()
    {
        Livewire::test(Feedback::class, [
            ...self::PARAMS,
            'fields' => [['name' => 'message', 'required' => true]],
        ])->assertCount('fields', 1);
    }

    public function test_uploaded_files_are_attached_to_the_notification()
    {
        Notification::fake();

        Livewire::test(Feedback::class, self::PARAMS)
            ->set('formData.0', 'Something is broken.')
            ->set('formData.1', [
                UploadedFile::fake()->image('screenshot.png'),
                UploadedFile::fake()->create('report.pdf', 100, 'application/pdf'),
            ])
            ->call('send')
            ->assertHasNoErrors();

        Notification::assertSentOnDemand(
            FeedbackNotification::class,
            function (FeedbackNotification $notification) {
                $this->assertSame(
                    ['screenshot.png', 'report.pdf'],
                    array_column($notification->attachments, 'name')
                );

                $attachment = collect($notification->feedback)->firstWhere('name', 'attachments');
                $this->assertSame('screenshot.png, report.pdf', $attachment['value']);

                return true;
            }
        );
    }

    public function test_attachments_are_optional()
    {
        Notification::fake();

        Livewire::test(Feedback::class, self::PARAMS)
            ->set('formData.0', 'Something is broken.')
            ->call('send')
            ->assertHasNoErrors();

        Notification::assertSentOnDemand(
            FeedbackNotification::class,
            function (FeedbackNotification $notification) {
                $this->assertSame([], $notification->attachments);

                $attachment = collect($notification->feedback)->firstWhere('name', 'attachments');
                $this->assertSame(__('ig-feedback::fields.not_provided'), $attachment['value']);

                return true;
            }
        );
    }

    public function test_too_many_files_are_rejected()
    {
        Notification::fake();

        Livewire::test(Feedback::class, self::PARAMS)
            ->set('isOpen', true)
            ->set('formData.0', 'Something is broken.')
            ->set('formData.1', [
                UploadedFile::fake()->image('one.png'),
                UploadedFile::fake()->image('two.png'),
                UploadedFile::fake()->image('three.png'),
                UploadedFile::fake()->image('four.png'),
            ])
            ->call('send')
            ->assertHasErrors('formData.1')
            ->assertSee(__('ig-feedback::fields.attachments.max_files', ['max' => 3]));

        Notification::assertNothingSent();
    }

    public function test_unsupported_file_types_are_rejected()
    {
        Notification::fake();

        Livewire::test(Feedback::class, self::PARAMS)
            ->set('isOpen', true)
            ->set('formData.0', 'Something is broken.')
            ->set('formData.1', [UploadedFile::fake()->create('payload.exe', 10)])
            ->call('send')
            ->assertHasErrors('formData.1.0')
            ->assertSee(__('ig-feedback::fields.attachments.mimes'));

        Notification::assertNothingSent();
    }

    public function test_oversized_files_are_rejected()
    {
        Notification::fake();

        Livewire::test(Feedback::class, self::PARAMS)
            ->set('isOpen', true)
            ->set('formData.0', 'Something is broken.')
            ->set('formData.1', [UploadedFile::fake()->image('huge.png')->size(6000)])
            ->call('send')
            ->assertHasErrors('formData.1.0')
            ->assertSee(__('ig-feedback::fields.attachments.max_size', ['max' => 5120]));

        Notification::assertNothingSent();
    }

    public function test_a_single_attachment_can_be_removed()
    {
        Livewire::test(Feedback::class, self::PARAMS)
            ->set('formData.1', [
                UploadedFile::fake()->image('one.png'),
                UploadedFile::fake()->image('two.png'),
            ])
            ->call('removeAttachment', 1, 0)
            ->assertCount('formData.1', 1)
            ->set('isOpen', true)
            ->assertSee('two.png')
            ->assertDontSee('one.png');
    }
}
