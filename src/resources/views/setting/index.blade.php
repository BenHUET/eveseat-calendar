@extends('web::layouts.grids.4-4-4')

@section('title', trans('calendar::seat.plugin_name') . ' | ' . trans('calendar::seat.settings'))
@section('page_header', trans('calendar::seat.settings'))

@section('left')
    @include('calendar::setting.includes.slack')
@stop

@section('center')
    @include('calendar::setting.includes.tags')
@stop

@include('calendar::setting.includes.modals.confirm_delete_tag')

@push('head')
    <link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
@endpush

@push('javascript')
    <script src="{{ asset('web/js/settings.js') }}"></script>
@endpush
