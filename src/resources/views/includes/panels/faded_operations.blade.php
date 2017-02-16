<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title pull-left" >{{ trans('calendar::seat.faded_operations') }}</h3>

		<div class="clearfix"></div>
	</div>

	<div class="panel-body">
		@if($ops_faded->count() > 0)
			<table class="table table-striped" id="faded-operations">
				<thead>
					<tr>
						<th>{{ trans('calendar::seat.title') }}</th>
						<th>{{ trans('calendar::seat.type') }}</th>
						<th>{{ trans('calendar::seat.importance') }}</th>
						<th>{{ trans('calendar::seat.started_at') }}</th>
						<th>{{ trans('calendar::seat.ended_at') }}</th>
						<th>{{ trans('calendar::seat.lasted') }}</th>
						<th>{{ trans('calendar::seat.fleet_commander') }}</th>
						<th>{{ trans('calendar::seat.staging') }}</th>
						<th>{{ trans('calendar::seat.subscription') }}</th>
						<th>{{ trans('calendar::seat.actions') }}</th>
					</tr>
				</thead>
				<tbody>
					<?php $table = "faded"; ?>
					@foreach($ops_faded as $op)
						<tr class="{{ $op->is_cancelled == 0 ? '' : 'danger' }}" >
							<td>{{ $op->title }}</td>
							<td>{{ $op->type }}</td>
							<td>
								@for ($i = 0; $i < $op->importance; $i++)
									<i class="fa fa-star"></i>
								@endfor
							</td>
							<td>{{ $op->start_at }}</td>
							<td>{{ $op->end_at }}</td>
							<td>{{ $op->duration }}</td>
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
				</tbody>
			</table>
		@else
			<i>{{ trans('calendar::seat.nothing_to_display') }}</i>
		@endif
	</div>
</div>