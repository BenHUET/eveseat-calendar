<small class="badge bg-green" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}">{{ $op->attendees->where('status', '=', 'yes')->count() }}</small>
<small class="badge bg-yellow" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}">{{ $op->attendees->where('status', '=', 'maybe')->count() }}</small>
<small class="badge bg-red" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}">{{ $op->attendees->where('status', '=', 'no')->count() }}</small>
