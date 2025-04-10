@extends('ig-common::layouts.email-plain')

@section('content')
@lang('ig-common::messages.email.hello')

@lang('ig-feedback::email.subject')

@lang('ig-feedback::form.subject'): {{ $feedback['subject'] ?? __('ig-feedback::email.no_subject') }}

@lang('ig-feedback::form.message'):
{{ $feedback['message'] ?? __('ig-feedback::email.no_message') }}

@lang('ig-feedback::form.email'): {{ $feedback['email'] ?? __('ig-feedback::email.anonymous') }}

@lang('ig-feedback::email.send_from') {{ $sendFromUrl }}
@endsection
