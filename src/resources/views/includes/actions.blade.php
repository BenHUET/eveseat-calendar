<span class="text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.details') }}">
	<i class="fa fa-eye" data-toggle="modal" data-target="#modalDetails-{{ $op->id }}"></i>
</span>

@if($table == "incoming")
	<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.subscribe') }}">
		&nbsp;
		<i class="fa fa-reply" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalSubscribe"></i>
	</span>
@endif

@if($table == "ongoing")
	@if(auth()->user()->has('calendar.closeAll', false) || $op->user->id == auth()->user()->id)
		&nbsp;
		<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.close') }}">
			<i class="fa fa-check text-danger" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmClose"></i>
		</span>
	@endif
@endif

@if(auth()->user()->has('calendar.cancelAll', false) || $op->user->id == auth()->user()->id)
	@if($op->is_cancelled == true)
		&nbsp;
		<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.activate') }}">
			<i class="fa fa-undo text-success" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmActivate"></i>
		</span>
	@else
		&nbsp;
		<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.cancel') }}">
			<i class="fa fa-ban text-danger" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmCancel"></i>
		</span>
	@endif
@endif

@if(auth()->user()->has('calendar.deleteAll', false)  || $op->user->id == auth()->user()->id )
	&nbsp;
	<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.delete') }}">
		<i class="fa fa-trash text-danger" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmDelete"></i>
	</span>
@endif

{{ $table = null }}