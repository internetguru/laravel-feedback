@extends('ig-common::layouts.email-html')

@section('content')
@foreach($feedback as $field)
<p>
    <strong>{{ $field['label'] }}:</strong><br/>
    {!! nl2br(e($field['value'])) !!}
</p>
@endforeach

<p>
    <strong>@lang('ig-feedback::layouts.email.sent_from'):</strong><br/>
    <a href="{{ session('currentPage') ?? '-' }}">{{ session('currentPage') ?? '-' }}</a><br/>
    {{ InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
</p>
@endsection
