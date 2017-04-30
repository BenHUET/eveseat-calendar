<small class="text-nowrap">{{ $row->created_at }}</small>
@if($row->created_at != $row->updated_at)
	<br/>
	<small class="text-nowrap">{{ trans('calendar::seat.update') }} : {{ $row->updated_at }}</small>
@endif