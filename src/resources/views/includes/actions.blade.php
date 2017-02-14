<a href="#" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.details') }}">
	<i class="fa fa-eye"></i>
</a>

@if($table == "incoming")

	<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}">
		@if($op->getAttendeeStatus(auth()->user()->id) == "yes")
			&nbsp;
			<i class="fa fa-thumbs-up text-success"></i>
		@else
			&nbsp;
			<i class="fa fa-thumbs-o-up text-muted" data-toggle="modal" data-op-id="{{ $op->id }}" data-status="yes" data-target="#modalSubscribe"></i>
		@endif
	</span>

	<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}">
		@if($op->getAttendeeStatus(auth()->user()->id) == "maybe")
			&nbsp;
			<i class="fa fa-question-circle text-success"></i>
		@else
			&nbsp;
			<i class="fa fa-question-circle text-muted" data-toggle="modal" data-op-id="{{ $op->id }}" data-status="maybe" data-target="#modalSubscribe"></i>
		@endif
	</span>

	<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}">
		@if($op->getAttendeeStatus(auth()->user()->id) == "no")
			&nbsp;
			<i class="fa fa-thumbs-down text-success"></i>
		@else
			&nbsp;
			<i class="fa fa-thumbs-o-down text-muted" data-toggle="modal" data-op-id="{{ $op->id }}" data-status="no" data-target="#modalSubscribe"></i>
		@endif
	</span>
@endif

@if($table == "ongoing")
	@if(auth()->user()->has('calendar.closeAll', false) || $op->user->id == auth()->user()->id)
		&nbsp;
		<a href="{{ URL::to('calendar/operation/close', $op->id) }}" class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.close') }}">
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