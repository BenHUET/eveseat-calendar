<tr class="bg-grey">
	<th colspan="9" class="table-title text-muted">
		<i class="fa fa-pause"></i>&nbsp;&nbsp;{{ trans('calendar::seat.faded_operations') }}
	</th>
</tr>
@if($ops_faded->count() > 0)
	<tr class="bg-grey text-muted">
		<th>{{ trans('calendar::seat.title') }}</th>
		<th>{{ trans('calendar::seat.type') }}</th>
		<th>{{ trans('calendar::seat.importance') }}</th>
		<th>{{ trans('calendar::seat.started_at') }}</th>
		<th>{{ trans('calendar::seat.ended_at') }}</th>
		<th>{{ trans('calendar::seat.fleet_commander') }}</th>
		<th>{{ trans('calendar::seat.staging') }}</th>
		<th>{{ trans('calendar::seat.subscription') }}</th>
		<th>{{ trans('calendar::seat.actions') }}</th>
	</tr>
	<?php $table = "faded"; ?>
	@foreach($ops_faded as $op)
		<tr class="{{ $op->is_cancelled == 0 ? '' : 'danger' }} text-muted" >
			<td>{{ $op->title }}</td>
			<td>{{ $op->type }}</td>
			<td>
				@include('calendar::includes.importance')
			</td>
			<td>{{ $op->start_at }}</td>
			<td>{{ $op->end_at }}</td>
			<td>
				@include('calendar::includes.fleet_commander')
			</td>
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