<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.details') }}" class="clickable">
    <i class="fa fa-eye text-primary" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalDetails"></i>
</span>

@if(carbon()->now()->lt($op->start_at))
    <span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.subscribe') }}" class="clickable">
        &nbsp;
        <i class="fa fa-reply text-primary" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalSubscribe"></i>
    </span>
    @if(auth()->user()->has('calendar.updateAll', false) || $op->user->id == auth()->user()->id)
        &nbsp;
        <span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.update') }}" class="clickable">
            <i class="fa fa-pencil text-danger" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalUpdateOperation"></i>
        </span>
    @endif
@endif

@if(carbon()->now()->gt($op->start_at) && in_array($op->end_at, [null, carbon()->now()]))
    @if(auth()->user()->has('calendar.closeAll', false) || $op->user->id == auth()->user()->id)
        &nbsp;
        <span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.close') }}" class="clickable">
            <i class="fa fa-check text-success" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmClose"></i>
        </span>
    @endif
@endif

@if(auth()->user()->has('calendar.cancelAll', false) || $op->user->id == auth()->user()->id)
    @if($op->is_cancelled == true)
        &nbsp;
        <span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.activate') }}" class="clickable">
            <i class="fa fa-undo text-success" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmActivate"></i>
        </span>
    @else
        &nbsp;
        <span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.cancel') }}" class="clickable">
            <i class="fa fa-ban text-danger" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmCancel"></i>
        </span>
    @endif
@endif

@if(auth()->user()->has('calendar.deleteAll', false) || $op->user->id == auth()->user()->id)
    &nbsp;
    <span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.delete') }}" class="clickable">
        <i class="fa fa-trash text-danger" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalConfirmDelete"></i>
    </span>
@endif

{{ $table = null }}
