<tr class="success">
	<th colspan="9" class="table-title">
		<i class="fa fa-play"></i>&nbsp;&nbsp;{{ trans('calendar::seat.ongoing_operations') }}
	</th>
</tr>
@if($ops_ongoing->count() > 0)
	<tr class="success">
		<th>{{ trans('calendar::seat.title') }}</th>
		<th>{{ trans('calendar::seat.type') }}</th>
		<th>{{ trans('calendar::seat.importance') }}</th>
		<th>{{ trans('calendar::seat.started') }}</th>
		<th>{{ trans('calendar::seat.ends_in') }}</th>
		<th>{{ trans('calendar::seat.fleet_commander') }}</th>
		<th>{{ trans('calendar::seat.staging') }}</th>
		<th>{{ trans('calendar::seat.subscription') }}</th>
		<th>{{ trans('calendar::seat.actions') }}</th>
	</tr>
	<?php $table = "ongoing"; ?>
	@foreach($ops_ongoing as $op)
		<tr>
			<td>{{ $op->title }}</td>
			<td>{{ $op->type }}</td>
			<td>
				@for ($i = 0; $i < $op->importance; $i++)
					<i class="fa fa-star"></i>
				@endfor
			</td>
			<td><span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.started_at') }} {{ $op->start_at }}">{{ $op->started }} ago</span></td>
			<td><span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.ends_at') }} {{ $op->end_at }}">{{ $op->ends_in }}</span></td>
			<td>{{ $op->fc }}</td>
			<td>{{ $op->staging }}</td>
			<td>
				@include('calendar::includes.subscription')
			</td>
			<td>
				@include('calendar::includes.actions')
			</td>
		</tr>
	@endforeach
@else
	<tr>
		<td colspan="9" class="text-center"><i>{{ trans('calendar::seat.none') }}</i></th>
	</tr>
@endif