@extends('ig-common::layouts.email-plain')

@section('content')
@lang('ig-common::messages.email.hello')


@lang('ig-feedback::layouts.form.subject')

    {{ $feedback['subject'] ?? __('ig-feedback::layouts.email.no_subject') }}

@lang('ig-feedback::layouts.form.message')

    {{ $feedback['message'] ?? __('ig-feedback::layouts.email.no_message') }}

@lang('ig-feedback::layouts.form.email')

    {{ $feedback['email'] ?? __('ig-feedback::layouts.email.anonymous') }}

@endsection
