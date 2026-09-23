# Laravel Feedback (internetguru/laravel-feedback)

A Livewire contact or feedback form in a modal, which e-mails each submission (with optional attachments). The full reference is `vendor/internetguru/laravel-feedback/README.md`.

## Usage

- Place the form once on the page: `<livewire:ig-feedback id="contact-form" email="support@example.com" name="Support" … />`. `id`, `email` and `name` are required; `subject`, `title`, `description`, `submit`, `success` and `fields` are optional. Pass the visible texts as translations (`:title="__('contact.title')"`).
- Open it with `<x-ig-feedback::button form-id="contact-form">…</x-ig-feedback::button>` or `<x-ig-feedback::link form-id="contact-form">…</x-ig-feedback::link>`. The `form-id` must equal the form's `id`. The form uses laravel-common's `x-ig::modal`.
- `fields` is a list of items:
  - `name`: one of `fullname`, `email`, `message`, `phone`, `subscribe` or `attachments`; a name may repeat
  - optionally `required`, `label`, `fallback` and `error` (a message, or a rule → message map)
  - any other key becomes an HTML attribute of the input
- For a signed-in user, `email` and `fullname` are prefilled.
- Attachment limits live in `config/ig-feedback.php` under `names.attachments`: 3 files, 5 MB each, images or PDF by default.
- laravel-common's `x-ig::footer` already embeds feedback forms. Don't add a second technical-feedback form to a page that renders the footer.
- Delivery is a queued mail, so a working queue worker and mail configuration are required. Tests should fake mail or notifications.
- Views and translations are namespaced `ig-feedback::`, overridden in `resources/views/vendor/ig-feedback` and `lang/vendor/ig-feedback`.
