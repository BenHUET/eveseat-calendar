@extends('web::layouts.grids.12')

@section('title', trans('calendar::seat.all_operations'))
@section('page_header', trans('calendar::seat.all_operations'))

@section('full')

	@include('calendar::includes.modals.create_operation')
	@include('calendar::includes.modals.confirm_delete')
	@include('calendar::includes.modals.confirm_close')
	@include('calendar::includes.modals.subscribe')

	@include('calendar::includes.panels.ongoing_operations')
	@include('calendar::includes.panels.incoming_operations')
	@include('calendar::includes.panels.faded_operations')

@stop

@push('head')
	<link rel="stylesheet" href="{{ asset('web/css/daterangepicker.css') }}" />
	<link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
@endpush

@push('javascript')
	<script src="{{ asset('web/js/daterangepicker.js') }}"></script>
	<script src="{{ asset('web/js/calendar.js') }}"></script>
@endpush