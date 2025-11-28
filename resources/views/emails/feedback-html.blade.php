@extends('ig-common::layouts.email-html')

@section('content')
@foreach($feedback as $field)
<p>
    <strong>{{ strip_tags($field['label']) }}</strong><br/>
    {!! nl2br(e($field['value'])) !!}
</p>
@endforeach
@endsection
