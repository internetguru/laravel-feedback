@extends('ig-common::layouts.email-plain')

@section('content')
@lang('ig-feedback::layouts.form.message'):
{{ $feedback['message'] ?? __('ig-feedback::layouts.email.no_message') }}

@lang('ig-feedback::layouts.form.email'):
{{ $feedback['email'] ?? __('ig-feedback::layouts.email.anonymous') }}

@parent
@endsection

@section('footer')
@parent

{{ \InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
@endsection
