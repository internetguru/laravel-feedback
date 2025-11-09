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
            'label_translation_key' => 'feedback::fields.fullname',
            'view' => 'feedback::fields.fullname',
        ],
        'email' => [
            'type' => 'email',
            'validation' => 'email:rfc,dns|max:255',
            'label_translation_key' => 'feedback::fields.email',
            'view' => 'feedback::fields.email',
        ],
        'message' => [
            'type' => 'textarea',
            'validation' => 'string|min:2|max:2000',
            'label_translation_key' => 'feedback::fields.message',
            'view' => 'feedback::fields.message',
        ],
        'phone' => [
            'type' => 'tel',
            'validation' => 'string|regex:/^(?:[0-9\s\-\(\)\.+]*\d){7,15}$/|max:50',
            'label_translation_key' => 'feedback::fields.phone',
            'view' => 'feedback::fields.phone',
        ],
    ],
];
