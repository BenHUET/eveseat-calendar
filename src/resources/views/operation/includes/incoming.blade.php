<tr class="info">
	<th colspan="9" class="table-title">
		<i class="fa fa-forward"></i>&nbsp;&nbsp;{{ trans('calendar::seat.incoming_operations') }}
	</th>
</tr>
@if($ops_incoming->count() > 0)
	<tr class="info">
		<th>{{ trans('calendar::seat.title') }}</th>
		<th>{{ trans('calendar::seat.type') }}</th>
		<th>{{ trans('calendar::seat.importance') }}</th>
		<th>{{ trans('calendar::seat.starts_in') }}</th>
		<th>{{ trans('calendar::seat.duration') }}</th>
		<th>{{ trans('calendar::seat.fleet_commander') }}</th>
		<th>{{ trans('calendar::seat.staging') }}</th>
		<th>{{ trans('calendar::seat.subscription') }}</th>
		<th>{{ trans('calendar::seat.actions') }}</th>
	</tr>
	<?php $table = "incoming"; ?>
	@foreach($ops_incoming as $op)
		<tr>
			<td>{{ $op->title }}</td>
			<td>{{ $op->type }}</td>
			<td>
				@include('calendar::operation.includes.importance')
			</td>
			<td><span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.starts_at') }} {{ $op->start_at }}">{{ $op->starts_in }}</span></td>
			<td><span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.ends_at') }} {{ $op->end_at }}">{{ $op->duration }}</span></td>
			<td>
				@include('calendar::operation.includes.fleet_commander')
			</td>
			<td>{{ $op->staging }}</td>
			<td>
				@include('calendar::operation.includes.subscription')
			</td>
			<td>
				@include('calendar::operation.includes.actions')
			</td>
		</tr>
	@endforeach
@else
	<tr>
		<td colspan="9" class="text-center"><i>{{ trans('calendar::seat.none') }}</i></th>
	</tr>
@endif