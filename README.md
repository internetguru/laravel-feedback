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
1. Install the package via Composer:

```bash
composer require internetguru/laravel-feedback
```
1. Ensure mail configuration is working.
1. Optional: configure reCAPTCHA, see [internetguru/laravel-common](https://github.com/internetguru/laravel-common).

## Usage

### Minimalistic (only required attributes and button)

```blade
<livewire:ig-feedback
    id="feedback-form"
    email="support@example.com"
    name="Support Team"
/>
<x-feedback::button formId="feedback-form">
    Give Feedback
</x-feedback::button>
```

### Basic (custom subject, title, description, and fields, multiple usages)

```blade
<livewire:ig-feedback
    id="contact-us-form"
    email="helpdesk@example.com"
    name="Helpdesk"
    subject="New Feedback"
    title="Contact Us"
    description="Share your questions, requirements, or special needs and we will respond shortly."
    submit="Send Feedback"
    :fields="[
        ['name'=>'name', 'label'=>'Your Name', 'required' => true],
        ['name'=>'email', 'label'=>'Your Email', 'required' => true],
        ['name'=>'message', 'label'=>'Message', 'required' => true]
    ]"
/>
<x-feedback::button formId="contact-us-form" class="btn btn-primary">
    Contact Us
</x-feedback::button>
```

### Advanced (duplicate field names and custom validation)

```blade
<livewire:ig-feedback
    id="detailed-feedback-form"
    email="info@example.com"
    name="Info Center"
    subject="Website Feedback"
    title="Feedback Form"
    submit="Submit"
    :fields="[
        ['name'=>'name', 'label'=>'Full Name', 'required' => true],
        ['name'=>'email', 'label'=>'Work Email', 'required' => true],
        ['name'=>'email', 'label'=>'Personal Email'],
        ['name'=>'message', 'label'=>'Feedback Message', 'required' => true],
    ]"
/>
<p>
    Do you have questions or need assistance?
    <x-feedback::link formId="detailed-feedback-form">
        Click here to reach our Helpdesk.
    </x-feedback::link>
</p>
```

## Trigger Components

The package provides two components to trigger the feedback form:

**Button component**

```blade
<x-feedback::button formId="feedback-form" class="btn btn-primary">
    Give Feedback
</x-feedback::button>
```

**Link component**

```blade
<x-feedback::link formId="contact-us-form">
    Click here to contact us
</x-feedback::link>
```

Both components accept a `formId` prop that must match the `id` of your feedback form. You can add any additional HTML attributes (classes, styles, etc.) and they will be passed through to the rendered element.

## Default values

If optional attributes are omitted, the default values are:

```php
subject: 'Feedback :app_www'  // Uses app.www config value
title: 'Send Feedback'
description: 'Your feedback helps us improve. Please share your thoughts.'
submit: 'Send'
fields: [
    ['name' => 'message', 'required' => true],
    ['name' => 'email'],
]
```

## Props

id (required)  Unique identifier for the component instance.
    Used to target the component when opening the form.

email (required)  Destination address for submitted feedback.
    Used as the "to" recipient.

name (required)  Display name used for the sender context.
    Appears in email metadata.

subject (optional, default "Feedback")  Subject line used for outgoing mail.

title (optional, default "Feedback")  Heading shown above the form.

description (optional, default "Your feedback is valuable...")  Descriptive text
    displayed below the title to provide context for the form.

submit (optional, default browser value)  Text for the submit button.

fields (optional)  Array defining which fields to render and how to validate them.
    See "Field items" below.

## Field items

name (required)  Field name. Supported values include: name, email, message,
    phone. No implicit default. You must specify a name.

required (optional, default false)  Whether the field is required.

label (optional)  Custom label is displayed. If omitted, a reasonable label
    is generated. For duplicate names, labels will auto-increment when omitted,
    e.g. Email 1, Email 2.

## Submission behavior

By default the component sends the submitted data to the configured email
with the given subject. You control the payload by defining fields,
required and patterns. You can listen into Livewire event to extend or
replace delivery (for example, call an API, enqueue a job). Inline errors
and a success state are rendered by the component.

## Customization

Styling  The component ships pre-styled. You can override classes by
    publishing the Blade view or wrapping the component in your layout.

Localization  The component uses translation keys under the
    `ig-feedback::` namespace. You can publish the language files to override
    them.

Custom names  You can register additional field names via your standard
    config. Define a name key and its renderer and default pattern. Example:

```php
// config/ig-feedback.php
return [
    'names' => [
        'name' => [
            'type' => 'text',
            'validation' => 'string|min:2|max:100', // modified validation
            'label_translation_key' => 'ig-feedback::fields.name',
            'view' => 'ig-feedback::fields.name',
        ],
        // your custom type
        'company' => [
            'type' => 'text',
            'validation' => 'string|min:2|max:100',
            'label_translation_key' => 'fields.company',
            'view' => 'ig-feedback::fields.company',
        ],
    ],
];
```

Then use it like any other name:

```blade
:fields="[
    ['name'=>'company', 'required' => true],
]"
```
