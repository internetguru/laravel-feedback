@extends('ig-common::layouts.email-plain')

@section('content')
@foreach($feedback as $field)
{{ $field['label'] }}:
{{ $field['value'] }}

@endforeach
@lang('ig-feedback::layouts.email.send_from')
{{ session('currentPage') ?? '-' }}
{{ InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
@endsection
