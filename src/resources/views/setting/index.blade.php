@extends('web::layouts.grids.12')

@section('title', trans('calendar::seat.all_operations'))
@section('page_header', trans('calendar::seat.all_operations'))

@section('full')

@stop

@push('head')
	<link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
@endpush

@push('javascript')
	<script src="{{ asset('web/js/calendar.js') }}"></script>
@endpush