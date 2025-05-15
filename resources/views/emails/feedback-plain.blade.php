@extends('ig-common::layouts.email-plain')

@section('content')
@lang('ig-common::messages.email.hello')


@lang('ig-feedback::layouts.form.message')

    {{ $feedback['message'] ?? __('ig-feedback::layouts.email.no_message') }}

@lang('ig-feedback::layouts.form.email')

    {{ $feedback['email'] ?? __('ig-feedback::layouts.email.anonymous') }}

@endsection

@section('footer')
@lang('ig-common::messages.email.regards'),
{{ \InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
{{ url()->previous() }}
@stop