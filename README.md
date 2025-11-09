# Internet Guru Feedback Component

A configurable Livewire component for collecting feedback through a customizable
form. Built on Internet Guru Laravel Common Component, inheriting its conventions
for email logging, footer rendering, and styling.

## Features

- Pre-styled, responsive form
- Attribute-based validation (name, mode, validation)
- reCAPTCHA support (if configured)
- Inline errors and success messaging
- Sends submissions via mail to your target address

## Installation

1. Install and configure [Livewire](https://livewire.laravel.com/).
1. Install the package via Composer:\
`composer require internetguru/laravel-feedback`
1. Ensure mail configuration is working.
1. Optionally configure reCAPTCHA, see [internetguru/laravel-common](https://github.com/internetguru/laravel-common).

## Usage

### Minimalistic

**Declaration with only required attributes**

```blade
<livewire:feedback
    id="feedback-form"
    email="support@example.com"
    name="Support Team"
/>
```

**Button trigger**

```blade
<x-feedback::button form-id="feedback-form">
    Give Feedback
</x-feedback::button>
```

### Basic

**Declaration with custom subject, title, description, and fields**

```blade
<livewire:feedback
    id="contact-us-form"
    email="helpdesk@example.com"
    name="Helpdesk"
    subject="New Feedback"
    title="Contact Us"
    description="Share your needs and we will respond shortly."
    submit="Send Feedback"
    :fields="[
        ['name' => 'fullname', 'required' => true],
        ['name' => 'email', 'required' => true],
        ['name' => 'message', 'required' => true],
    ]"
/>
```

**Button trigger with custom class**

```blade
<x-feedback::button form-id="contact-us-form" class="btn btn-primary">
    Contact Us
</x-feedback::button>
```

### Advanced

**Declaration with duplicate email field and custom labels**

```blade
<livewire:feedback
    id="detailed-feedback-form"
    email="info@example.com"
    name="Info Center"
    subject="Website Feedback"
    title="Feedback Form"
    submit="Submit"
    :fields="[
        ['name' => 'fullname', 'label' => 'Full Name', 'required' => true],
        ['name' => 'email', 'label' => 'Work Email', 'required' => true],
        ['name' => 'email', 'label' => 'Personal Email'],
        ['name' => 'message', 'label' => 'Feedback Message', 'required' => true],
    ]"
/>
```

**Link trigger**

```blade
<p>
    Do you have questions or need assistance?
    <x-feedback::link form-id="detailed-feedback-form">
        Click here to reach our Helpdesk.
    </x-feedback::link>
</p>
```

## Trigger Components

The package provides two components to trigger the feedback form. Both components accept a `form-id` prop that must match the feedback form component ID. You can add any additional HTML attributes (classes, styles, etc.) and they will be passed through to the rendered element.

**Button trigger**

```blade
<x-feedback::button form-id="feedback-form-id">
    Give Feedback
</x-feedback::button>
```

**Link trigger**

```blade
<x-feedback::link form-id="contact-form-id">
    Click here to contact us.
</x-feedback::link>
```

## Default values

If optional attributes are omitted, the default values are:

```php
[
    'subject' => 'Feedback :app_www',  // Uses app.www config value
    'title' => 'Send Feedback',
    'description' => 'Your feedback helps us improve. Please share your thoughts.',
    'submit' => 'Send',
    'fields' => [
        ['name' => 'message', 'required' => true],
        ['name' => 'email'],
    ],
]
```

## Attributes

- `id` (required)\
  Unique identifier for the component instance. Used to target the component when opening the form.

- `email` (required)\
  Destination email address for submitted feedback.

- `name` (required)\
  Display name for the email recipient.

- `subject` (optional)\
  Subject line for the outgoing email.

- `title` (optional)\
  Heading displayed above the form.

- `description` (optional)\
  Descriptive text displayed below the title to provide context for the form.

- `submit` (optional)\
  Text for the submit button.

- `fields` (optional)\
  Array defining which fields to render and how to validate them. See "Field items" below.

## Field items

- `name` (required)\
  Field name. Supported values include: fullname, email, message, phone.

- `required` (optional)\
  Whether the field is required (false by default).

- `label` (optional)\
  Custom label displayed for the field. If omitted, a reasonable label is generated. For duplicate names, labels will auto-increment when omitted, e.g. Email 1, Email 2.

## Submission behavior

By default, the component sends submitted feedback to the configured email address with the specified subject line. You control the form structure by defining fields with validation rules and required flags.

You can listen to Livewire events to extend or replace the default delivery mechanism (for example, calling an API or enqueueing a job). The component renders inline validation errors and success messages.

## Customization

**Styling**\
The component ships pre-styled. You can override classes by
    publishing the Blade view or wrapping the component in your layout.

**Localization**\
The component uses translation keys under the
    `feedback::` namespace. You can publish the language files to override
    them.

**Custom names**\
You can customize existing fields or add new ones through the configuration file. Each field requires a type, validation rules, label translation key, and view path. Example:

```php
// config/feedback.php
return [
    'names' => [
        // modify existing field
        'email' => [
            'type' => 'email',
            'validation' => 'email:rfc|regex:/@internetguru\.io$/',
            'label_translation_key' => 'feedback::fields.email',
            'view' => 'feedback::fields.email',
        ],
        // add custom field
        'company' => [
            'type' => 'text',
            'validation' => 'string|min:2|max:100',
            'label_translation_key' => 'fields.company',
            'view' => 'feedback::fields.company',
        ],
    ],
];
```

Then use it like any other field:

```blade
:fields="[
    ['name' => 'email'],
    ['name' => 'company'],
]"
```
