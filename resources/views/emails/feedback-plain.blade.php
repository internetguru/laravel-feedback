@extends('ig-common::layouts.email-plain')

@section('content')
@foreach($feedback as $field)
{{ strip_tags($field['label']) }}
{!! html_entity_decode($field['value']) !!}

@endforeach
@endsection