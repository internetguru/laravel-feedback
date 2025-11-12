@extends('ig-common::layouts.email-plain')

@section('content')
@foreach($feedback as $field)
{{ $field['label'] }}:
{!! html_entity_decode($field['value']) !!}

@endforeach
@endsection