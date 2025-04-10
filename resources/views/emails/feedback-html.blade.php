@extends('ig-common::layouts.email-html')

@section('content')
<p>@lang('ig-common::messages.email.hello')</p>

<p><strong>@lang('ig-feedback::form.subject'):</strong> {{ $feedback['subject'] ?? __('ig-feedback::email.no_subject') }}</p>
<p><strong>@lang('ig-feedback::form.message'):</strong></p>
<p>{{ $feedback['message'] ?? __('ig-feedback::email.no_message') }}</p>
<p><strong>@lang('ig-feedback::form.email'):</strong> {{ $feedback['email'] ?? __('ig-feedback::email.anonymous') }}</p>

<p>@lang('ig-feedback::email.send_from') {{ $sendFromUrl }}</p>
@endsection
