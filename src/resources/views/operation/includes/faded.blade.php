<tr class="bg-grey">
	<th colspan="9" class="table-title text-muted">
		<i class="fa fa-pause"></i>&nbsp;&nbsp;{{ trans('calendar::seat.faded_operations') }}
	</th>
</tr>
@if($ops_faded->count() > 0)
	<tr class="bg-grey text-muted">
		<th>{{ trans('calendar::seat.title') }}</th>
		<th>{{ trans('calendar::seat.tags') }}</th>
		<th>{{ trans('calendar::seat.importance') }}</th>
		<th>{{ trans('calendar::seat.started_at') }}</th>
		<th>{{ trans('calendar::seat.ended_at') }}</th>
		<th>{{ trans('calendar::seat.fleet_commander') }}</th>
		<th>{{ trans('calendar::seat.staging') }}</th>
		<th>{{ trans('calendar::seat.subscription') }}</th>
		<th>{{ trans('calendar::seat.actions') }}</th>
	</tr>
	<?php $table = "faded"; ?>
	@foreach($ops_faded->sortByDesc('start_at') as $op)
		<tr class="{{ $op->is_cancelled == 0 ? '' : 'danger' }} text-muted tr-hoverable" >
			<td>
				<span>{{ $op->title }}</span>
				<span class="pull-right">
					@include('calendar::operation.includes.attendees')
				</span>
			</td>
			<td>
				@include('calendar::operation.includes.tags')
			</td>
			<td>
				@include('calendar::operation.includes.importance')
			</td>
			<td>{{ $op->start_at }}</td>
			<td>{{ $op->end_at }}</td>
			<td>
				@include('calendar::operation.includes.fleet_commander')
			</td>
			<td>
				@include('calendar::operation.includes.staging')
			</td>
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