@extends('ig-common::layouts.email-plain')

@section('content')
@foreach($feedback as $field)
{{ $field['label'] }}:
{!! html_entity_decode($field['value']) !!}

@endforeach
@lang('feedback::layouts.email.sent_from'):
{{ session('currentPage') ?? '-' }}
{{ InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
@endsection
