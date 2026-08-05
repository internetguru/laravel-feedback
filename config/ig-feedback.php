<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Field Name Definitions
    |--------------------------------------------------------------------------
    |
    | Define custom field types with their validation rules, labels, and views.
    | Each field name can have: type, validation, label_translation_key, view
    |
    */
    'names' => [
        'fullname' => [
            'type' => 'text',
            'validation' => 'string|min:2|max:100',
            'label_translation_key' => 'ig-feedback::fields.fullname',
            'autocomplete' => 'name',
        ],
        'email' => [
            'type' => 'email',
            'validation' => 'email:rfc,dns|max:255',
            'label_translation_key' => 'ig-feedback::fields.email',
            'autocomplete' => 'email',
        ],
        'message' => [
            'type' => 'textarea',
            'validation' => 'string|min:2|max:2000',
            'label_translation_key' => 'ig-feedback::fields.message',
            'rows' => 8,
        ],
        'phone' => [
            'type' => 'tel',
            'validation' => 'string|regex:/^(?:[0-9\s\-\(\)\.+]*\d){7,15}$/|max:50',
            'error_translation_key' => [
                '*' => 'ig-feedback::fields.phone.validation',
            ],
            'label_translation_key' => 'ig-feedback::fields.phone',
            'autocomplete' => 'tel',
        ],
        'attachments' => [
            'type' => 'file',
            'multiple' => true,
            'accept' => 'image/jpeg,image/png,image/gif,image/webp,image/heic,application/pdf',
            'validation' => 'array|max:3',
            'file_validation' => 'file|mimes:jpg,jpeg,png,gif,webp,heic,pdf|max:5120',
            'label_translation_key' => 'ig-feedback::fields.attachments',
            'error_translation_key' => [
                'max' => 'ig-feedback::fields.attachments.max_files',
                '*.mimes' => 'ig-feedback::fields.attachments.mimes',
                '*.max' => 'ig-feedback::fields.attachments.max_size',
                '*.uploaded' => 'ig-feedback::fields.attachments.max_size',
            ],
            'input_view' => 'ig-feedback::attachments',
        ],
        'subscribe' => [
            'type' => 'checkbox',
            'validation' => 'boolean',
            'label_translation_key' => 'ig-feedback::fields.subscribe',
            'value_translation_key' => [
                1 => 'ig-feedback::fields.subscribe_interested',
                0 => 'ig-feedback::fields.subscribe_not_interested',
            ],
        ],
    ],
    'exclude_attributes' => [
        'name',
        'label',
        'error',
        'validation',
        'label_translation_key',
        'error_translation_key',
        'value_translation_key',
        'input_view',
        'values',
        'file_validation',
    ],
];
