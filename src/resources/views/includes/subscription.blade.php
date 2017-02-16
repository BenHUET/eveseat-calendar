@if($op->attendees->where('user_id', '=', auth()->user()->id)->count())
	@foreach($op->attendees->where('user_id', '=', auth()->user()->id) as $attendee)
		@if($attendee->status == "yes")
			<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}"><i class="fa fa-thumbs-o-up"></i>&nbsp;{{ $attendee->character->characterName }}</span>
		@endif
		@if($attendee->status == "maybe")
			<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}"><i class="fa fa-question-circle"></i>&nbsp;{{ $attendee->character->characterName }}</span>
		@endif
		@if($attendee->status == "no")
			<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}"><i class="fa fa-thumbs-o-down"></i>&nbsp;{{ $attendee->character->characterName }}</span>
		@endif
		@if (!$loop->last)
			<br/>
		@endif
	@endforeach
@else
	<b class="text-danger">{{ trans('calendar::seat.not_answered') }}</b>
@endif