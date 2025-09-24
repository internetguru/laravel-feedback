@extends('ig-common::layouts.email-html')

@section('content')
<p>
    <strong>@lang('ig-feedback::layouts.form.message'):</strong><br>
    {{ $feedback['message'] ?? __('ig-feedback::layouts.email.no_message') }}
</p>
<p>
    <strong>@lang('ig-feedback::layouts.form.email'):</strong><br>
    {{ $feedback['email'] ?? __('ig-feedback::layouts.email.anonymous') }}
</p>
@parent
@endsection

@section('footer')
@parent
<pre><code>{{ \InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}</code></pre>
@endsection
