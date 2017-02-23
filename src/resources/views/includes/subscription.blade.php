@if($op->attendees->where('user_id', '=', auth()->user()->id)->count())
	@foreach($op->attendees->where('user_id', '=', auth()->user()->id) as $attendee)
		@if($attendee->status == "yes")
			<small class="label bg-green">{{ $attendee->character->characterName }}</small>
		@endif
		@if($attendee->status == "maybe")
			<small class="label bg-yellow">{{ $attendee->character->characterName }}</small>
		@endif
		@if($attendee->status == "no")
			<small class="label bg-red">{{ $attendee->character->characterName }}</small>
		@endif
{{-- 		@if (!$loop->last)
			<br/>
		@endif --}}
	@endforeach
@else
	<b class="text-danger">{{ trans('calendar::seat.not_answered') }}</b>
@endif