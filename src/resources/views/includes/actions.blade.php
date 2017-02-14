<a href="#" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.details') }}">
	<i class="fa fa-eye"></i>
</a>

@if($table == "incoming")

	@if($op->getAttendeeStatus(auth()->user()->id) == "yes")
		&nbsp;
		<i class="fa fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}"></i>
	@else
		&nbsp;
		<a href="{{ URL::to('calendar/operation/subscribe', [$op->id, "yes"]) }}" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}">
			<i class="fa fa-thumbs-o-up"></i>
		</a>
	@endif

	@if($op->getAttendeeStatus(auth()->user()->id) == "maybe")
		&nbsp;
		<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}"></i>
	@else
		&nbsp;
		<a href="{{ URL::to('calendar/operation/subscribe', [$op->id, "maybe"]) }}" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}">
			<i class="fa fa-question-circle"></i>
		</a>
	@endif

	@if($op->getAttendeeStatus(auth()->user()->id) == "no")
		&nbsp;
		<i class="fa fa-thumbs-down"  data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}"></i>
	@else
		&nbsp;
		<a href="{{ URL::to('calendar/operation/subscribe', [$op->id, "no"]) }}" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}">
			<i class="fa fa-thumbs-o-down"></i>
		</a>
	@endif
@endif

@if($table == "ongoing")
	@if(auth()->user()->has('calendar.closeAll', false) || $op->user->id == auth()->user()->id)
		&nbsp;
		<a href="{{ URL::to('calendar/operation/close', $op->id) }}" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.close') }}">
			<i class="fa fa-check"></i>
		</a>
	@endif
@endif

@if(auth()->user()->has('calendar.cancelAll', false) || $op->user->id == auth()->user()->id)
	@if($op->is_cancelled == true)
		&nbsp;
		<a href="{{ URL::to('calendar/operation/activate', $op->id) }}" class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.activate') }}">
			<i class="fa fa-undo"></i>
		</a>
	@else
		&nbsp;
		<a href="{{ URL::to('calendar/operation/cancel', $op->id) }}" class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.cancel') }}">
			<i class="fa fa-ban"></i>
		</a>
	@endif
@endif

@if(auth()->user()->has('calendar.deleteAll', false)  || $op->user->id == auth()->user()->id )
	&nbsp;
	<a href="{{ URL::to('calendar/operation/delete', $op->id) }}" class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.delete') }}">
		<i class="fa fa-trash"></i>
	</a>
@endif

{{ $table = null }}