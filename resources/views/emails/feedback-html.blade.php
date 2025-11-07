@extends('ig-common::layouts.email-html')

@section('content')
@foreach($feedback as $field)
<p>
    <strong>{{ $field['label'] }}:</strong><br/>
    {{ $field['value'] }}
</p>
@endforeach

<p>
    <strong>@lang('ig-feedback::layouts.email.send_from')</strong><br/>
    {{ session('currentPage') ?? '-' }}<br/>
    {{ InternetGuru\LaravelCommon\Support\Helpers::getAppInfo() }}
</p>
@endsection
