@extends('ig-common::layouts.email-html')

@section('content')
<p>@lang('ig-common::messages.email.hello')</p>

<p>
    <strong>@lang('ig-feedback::layouts.form.subject')</strong><br>
    {{ $feedback['subject'] ?? __('ig-feedback::layouts.email.no_subject') }}
</p>

<p>
    <strong>@lang('ig-feedback::layouts.form.message')</strong><br>
    {{ $feedback['message'] ?? __('ig-feedback::layouts.email.no_message') }}
</p>

<p>
    <strong>@lang('ig-feedback::layouts.form.email')</strong><br>
    {{ $feedback['email'] ?? __('ig-feedback::layouts.email.anonymous') }}
</p>

@endsection
