@if ($row->status == "yes")
	<span class="label label-success">{{ trans('calendar::seat.attending_yes') }}</span>
@endif
@if ($row->status == "no")
	<span class="label label-danger">{{ trans('calendar::seat.attending_no') }}</span>
@endif
@if ($row->status == "maybe")
	<span class="label label-warning">{{ trans('calendar::seat.attending_maybe') }}</span>
@endif
