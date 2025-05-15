@extends('ig-common::layouts.email-html')

@section('content')
<p>@lang('ig-common::messages.email.hello')</p>

<p>
    <strong>@lang('ig-feedback::layouts.form.message')</strong><br>
    {{ $feedback['message'] ?? __('ig-feedback::layouts.email.no_message') }}
</p>

<p>
    <strong>@lang('ig-feedback::layouts.form.email')</strong><br>
    {{ $feedback['email'] ?? __('ig-feedback::layouts.email.anonymous') }}
</p>

@endsection

@section('footer')
<p>
    @lang('ig-common::messages.email.regards'),<br />
    {{ \InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}<br />
    {{ url()->previous() }}
</p>
@stop
