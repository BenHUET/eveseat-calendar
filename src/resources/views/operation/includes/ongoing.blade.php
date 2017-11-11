<div class="box box-widget widget-user-2">
	<div class="widget-user-header bg-green">
		<h4 class="widget-user-username">
			<i class="fa fa-play-circle"></i>
			{{ trans('calendar::seat.ongoing_operations') }}
		</h4>
	</div>
	<div class="box-footer no-padding">
		<table class="table table-striped table-hover">
			<thead class="bg-green">
				<tr>
					<th>{{ trans('calendar::seat.title') }}</th>
					<th class="hidden-xs">{{ trans('calendar::seat.tags') }}</th>
					<th>{{ trans('calendar::seat.importance') }}</th>
					<th>{{ trans('calendar::seat.started') }}</th>
					<th class="hidden-xs">{{ trans('calendar::seat.ends_in') }}</th>
					<th class="hidden-xs">{{ trans('calendar::seat.fleet_commander') }}</th>
					<th>{{ trans('calendar::seat.staging') }}</th>
					<th class="hidden-portrait-xs">{{ trans('calendar::seat.subscription') }}</th>
					<th class="hidden-xs"></th>
				</tr>
			</thead>
			<tbody>
				@forelse($ops_ongoing->sortBy('start_at') as $op)
				@if($default_op == $op->id)
				<tr data-attr-op="{{ $op->id }}" data-attr-default="true">
				@else
				<tr data-attr-op="{{ $op->id }}">
				@endif
					<td>
						<span>{{ $op->title }}</span>
						<span class="pull-right">
							@include('calendar::operation.includes.attendees')
						</span>
					</td>
					<td class="hidden-xs">
						@include('calendar::operation.includes.tags')
					</td>
					<td>
						@include('calendar::operation.includes.importance')
						<span class="visible-xs-inline-block">
							@include('calendar::operation.includes.actions')
						</span>
					</td>
					<td>
						<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.started_at') }} {{ $op->start_at }}">{{ $op->started }} ago</span>
					</td>
					<td class="hidden-xs">
						<span data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.ends_at') }} {{ $op->end_at }}">{{ $op->ends_in }}</span>
					</td>
					<td class="hidden-xs">
						@include('calendar::operation.includes.fleet_commander')
					</td>
					<td>
						@include('calendar::operation.includes.staging')
					</td>
					<td class="hidden-portrait-xs">
						@include('calendar::operation.includes.subscription')
					</td>
					<td class="hidden-xs">
						@include('calendar::operation.includes.actions')
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="9" class="text-center"><i>{{ trans('calendar::seat.none') }}</i></th>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
