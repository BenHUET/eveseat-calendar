@include('calendar::operation.partials.actions.show')

@if(carbon()->now()->lt($op->start_at))
  @if(! $op->is_cancelled)
    @include('calendar::operation.partials.actions.subscribe')
  @endif
  @if(auth()->user()->has('calendar.update_all', false) || $op->user->id == auth()->user()->id)
      @include('calendar::operation.partials.actions.edit')
  @endif
@endif

@if(carbon()->now()->gt($op->start_at) && in_array($op->end_at, [null, carbon()->now()]))
  @if(auth()->user()->has('calendar.close_all', false) || $op->user->id == auth()->user()->id)
    @include('calendar::operation.partials.actions.close')
  @endif
@endif

@if(auth()->user()->has('calendar.cancel_all', false) || $op->user->id == auth()->user()->id)
  @if($op->is_cancelled)
    @include('calendar::operation.partials.actions.enable')
  @else
    @if(carbon()->now()->lt($op->start_at))
      @include('calendar::operation.partials.actions.cancel')
    @endif
  @endif
@endif

@if(auth()->user()->has('calendar.delete_all', false) || $op->user->id == auth()->user()->id)
  @include('calendar::operation.partials.actions.destroy')
@endif
