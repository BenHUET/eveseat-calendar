@extends('web::layouts.grids.12')

@section('title', trans('calendar::seat.plugin_name') . ' | ' . trans('calendar::seat.operations'))
@section('page_header', trans('calendar::seat.all_operations'))

@section('full')

	@if(auth()->user() != null && auth()->user()->has('calendar.create', false))
		<div style="margin-bottom: 20px;" class="pull-right">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateOperation">
				<i class="fa fa-plus"></i>&nbsp;&nbsp;
				{{ trans('calendar::seat.add_operation') }}
			</button>
		</div>
	@endif

	@include('calendar::operation.includes.modals.create_operation')
	@include('calendar::operation.includes.modals.update_operation')
	@include('calendar::operation.includes.modals.confirm_delete')
	@include('calendar::operation.includes.modals.confirm_close')
	@include('calendar::operation.includes.modals.confirm_cancel')
	@include('calendar::operation.includes.modals.confirm_activate')
	@include('calendar::operation.includes.modals.subscribe')
	@include('calendar::operation.includes.modals.details')

	<table class="table table-bordered" id="incoming-operations">
		@include('calendar::operation.includes.ongoing')
		@include('calendar::operation.includes.incoming')
		@include('calendar::operation.includes.faded')
	</table>

@stop

@push('head')
	<link rel="stylesheet" href="{{ asset('web/css/daterangepicker.css') }}" />
	<link rel="stylesheet" href="{{ asset('web/css/bootstrap-slider.min.css') }}" />

	<link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
@endpush

@push('javascript')
	<script src="{{ asset('web/js/daterangepicker.js') }}"></script>
	<script src="{{ asset('web/js/bootstrap-slider.min.js') }}"></script>
	<script src="{{ asset('web/js/jquery.autocomplete.min.js') }}"></script>
	
	<script src="{{ asset('web/js/calendar.js') }}"></script>
@endpush