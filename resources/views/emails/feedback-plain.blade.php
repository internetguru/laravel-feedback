@extends('ig-common::layouts.email-plain')

@section('content')
@if(isset($feedback['name']))
@lang('ig-feedback::layouts.form.name'):
{{ $feedback['name'] }}

@endif
@if(isset($feedback['email']))
@lang('ig-feedback::layouts.form.email'):
{{ $feedback['email'] }}

@endif
@if(isset($feedback['phone']))
@lang('ig-feedback::layouts.form.phone'):
{{ $feedback['phone'] }}

@endif
@if(isset($feedback['note']))
@lang('ig-feedback::layouts.form.note'):
{{ $feedback['note'] }}
@endif

@lang('ig-feedback::layouts.email.send_from')
{{ session('currentPage') ?? '-' }}
{{ InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
@endsection
