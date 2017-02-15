<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title pull-left" >{{ trans('calendar::seat.incoming_operations') }}</h3>
		@if(auth()->user()->has('calendar.create', false))
			<button type="button" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#modalCreateOperation">
				{{ trans('calendar::seat.add_operation') }}
			</button>
		@endif

		<div class="clearfix"></div>
	</div>

	<div class="panel-body">
		<table class="table compact table-condensed table-hover table-responsive" id="incoming-operations">
			<thead>
				<tr>
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
			</thead>
			<tbody>
				<?php $table = "incoming"; ?>
				@foreach($ops_incoming as $op)
					<tr>
						<td>{{ $op->title }}</td>
						<td>{{ $op->type }}</td>
						<td>{{ $op->importance }}</td>
						<td><span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.starts_at') }} {{ $op->start_at }}">{{ $op->starts_in }}</span></td>
						<td><span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.ends_at') }} {{ $op->end_at }}">{{ $op->duration }}</span></td>
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
	</div>
</div>