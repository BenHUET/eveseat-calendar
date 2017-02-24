@extends('web::layouts.grids.12')

@section('title', trans('calendar::seat.all_operations'))
@section('page_header', trans('calendar::seat.all_operations'))

@section('full')

	@if(auth()->user()->has('calendar.create', false))
		<div style="margin-bottom: 20px;" class="pull-right">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateOperation">
				<i class="fa fa-plus"></i>&nbsp;&nbsp;
				{{ trans('calendar::seat.add_operation') }}
			</button>
		</div>
	@endif

	@include('calendar::includes.modals.create_operation')
	@include('calendar::includes.modals.confirm_delete')
	@include('calendar::includes.modals.confirm_close')
	@include('calendar::includes.modals.confirm_cancel')
	@include('calendar::includes.modals.confirm_activate')
	@include('calendar::includes.modals.subscribe')
	@include('calendar::includes.modals.details')

	<table class="table table-bordered" id="incoming-operations">
		@include('calendar::includes.ongoing')
		@include('calendar::includes.incoming')
		@include('calendar::includes.faded')
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