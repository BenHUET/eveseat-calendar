@if($row->created_at != $row->updated_at)
	<small class="text-nowrap">{{ $row->created_at }}</small>
@else
	<small class="text-nowrap">{{ $row->updated_at }}</small>
@endif