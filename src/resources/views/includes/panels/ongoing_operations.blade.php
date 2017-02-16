<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title pull-left" >{{ trans('calendar::seat.ongoing_operations') }}</h3>

		<div class="clearfix"></div>
	</div>

	<div class="panel-body">
		@if($ops_ongoing->count() > 0)
			<table class="table table-striped" id="ongoing-operations">
				<thead>
					<tr>
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
				</thead>
				<tbody>
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
				</tbody>
			</table>
		@else
			<i>{{ trans('calendar::seat.nothing_to_display') }}</i>
		@endif
	</div>
</div>