@extends('ig-common::layouts.email-html')

@section('content')
@if(isset($feedback['name']))
<p>
    <strong>@lang('ig-feedback::layouts.form.name'):</strong><br/>
    {{ $feedback['name'] }}
</p>
@endif

@if(isset($feedback['email']))
<p>
    <strong>@lang('ig-feedback::layouts.form.email'):</strong><br/>
    {{ $feedback['email'] }}
</p>
@endif

@if(isset($feedback['phone']))
<p>
    <strong>@lang('ig-feedback::layouts.form.phone'):</strong><br/>
    {{ $feedback['phone'] }}
</p>
@endif

@if(isset($feedback['note']))
<p>
    <strong>@lang('ig-feedback::layouts.form.note'):</strong><br/>
    {{ $feedback['note'] }}
</p>
@endif

<p>
    <strong>@lang('ig-feedback::layouts.email.send_from')</strong><br/>
    {{ session('currentPage') ?? '-' }}<br/>
    {{ InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
</p>
@endsection
