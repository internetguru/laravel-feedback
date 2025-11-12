@extends('ig-common::layouts.email-html')

@section('content')
@foreach($feedback as $field)
<p>
    <strong>{{ $field['label'] }}:</strong><br/>
    {!! nl2br(e($field['value'])) !!}
</p>
@endforeach
@endsection
