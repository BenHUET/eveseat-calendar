@if($op->attendees->where('user_id', '=', auth()->user()->id)->count())
	@foreach($op->attendees->where('user_id', '=', auth()->user()->id) as $attendee)
		@if($attendee->status == "yes")
			<span class="text-bg-green" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}"><b>{{ $attendee->character->characterName }}</b></span>
		@endif
		@if($attendee->status == "maybe")
			<span class="text-bg-orange" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}"><b>{{ $attendee->character->characterName }}</b></span>
		@endif
		@if($attendee->status == "no")
			<span class="text-bg-red" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}"><b>{{ $attendee->character->characterName }}</b></span>
		@endif
		@if (!$loop->last)
			&nbsp;&nbsp;
		@endif
	@endforeach
@else
	<b class="text-danger">{{ trans('calendar::seat.not_answered') }}</b>
@endif