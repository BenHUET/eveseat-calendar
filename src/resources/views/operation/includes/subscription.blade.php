@if($op->attendees->where('user_id', '=', auth()->user()->id)->count())
    @foreach($op->attendees->where('user_id', '=', auth()->user()->id) as $attendee)
        @if($op->status == "incoming")
            <span data-toggle="modal"
            data-op-id="{{ $op->id }}"
            data-status="{{ $attendee->status }}"
            data-character-id="{{ $attendee->character->character_id }}"
            data-target="#modalSubscribe"
            class="clickable">
        @endif
            @if($attendee->status == "yes")
                <small class="label bg-green">{{ $attendee->character->name }}</small>
            @endif
            @if($attendee->status == "maybe")
                <small class="label bg-yellow">{{ $attendee->character->name }}</small>
            @endif
            @if($attendee->status == "no")
                <small class="label bg-red">{{ $attendee->character->name }}</small>
            @endif
        @if($op->status == "incoming")
            </span>
        @endif
        @if($loop->iteration % 3 == 0)
            <br/>
        @endif
    @endforeach
@else
    <b class="text-danger">{{ trans('calendar::seat.not_answered') }}</b>
@endif
