@foreach($ops_all as $op)
	<div class="modal fade @if($default_op == $op->id) default-op @endif" tabindex="-1" role="dialog" id="modalDetails-{{ $op->id }}">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">

				<div class="modal-header modal-calendar modal-calendar-green">
					<p>
						<i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.details') }}
					</p>
				</div>

				<div class="modal-body">
					<h3 class="text-center" style="display: inline;">@include('calendar::operation.includes.importance')</h3>
					<div class="pull-right">
						<small>{{ trans('calendar::seat.created_at') }} {{ $op->created_at }}</small>
						@if($op->updated_at != $op->created_at)
							<br><small>{{ trans('calendar::seat.updated_at') }} {{ $op->updated_at }}</small>
						@endif
					</div>

					<h2 class="text-center"><b>{{ $op->title }}</b></h2>
					

					<h3>{{ trans('calendar::seat.informations') }}</h3>
					
					<ul class="list-group col-md-6">

						<li class="list-group-item">
							<b>{{ trans('calendar::seat.status') }}</b> : 
							@if($op->status == 'cancelled')
								<span class="label label-danger">{{ trans('calendar::seat.cancelled') }}</span>
							@else
								@if($op->status == 'ongoing')
									<span class="label label-success">{{ trans('calendar::seat.ongoing_operations') }}</span>
								@endif
								@if($op->status == 'incoming')
									<span class="label label-primary">{{ trans('calendar::seat.incoming_operations') }}</span>
								@endif
								@if($op->status == 'faded')
									<span class="label label-faded">{{ trans('calendar::seat.faded_operations') }}</span>
								@endif
							@endif
						</li>

						<li class="list-group-item">
							<b>{{ trans('calendar::seat.type') }}</b> : {{ $op->type }}
						</li>

						<li class="list-group-item">
							<b>{{ trans('calendar::seat.fleet_commander') }}</b> : 
							@if($op->fc)
								@if($op->fc_character_id)
									&nbsp;
								@endif
								@include('calendar::operation.includes.fleet_commander')
							@else
								<i>{{ trans('calendar::seat.unknown') }}</i>
							@endif
						</li>

						<li class="list-group-item">
							<b>{{ trans('calendar::seat.staging') }}</b> : 
							@if($op->staging_sys_id)
								@include('calendar::operation.includes.staging')
							@else
								<i>{{ trans('calendar::seat.unknown') }}</i>
							@endif
						</li>

						<li class="list-group-item">
							<b>{{ trans('calendar::seat.staging_info') }}</b> : 
							@if($op->staging_info)
								{{ $op->staging_info }}
							@else
								<i>{{ trans('calendar::seat.unknown') }}</i>
							@endif
						</li>

					</ul>


					<ul class="list-group col-md-6">

						<li class="list-group-item"><b>{{ trans('calendar::seat.starts_at') }}</b> : {{ $op->start_at }}</li>
						<li class="list-group-item">
							<b>{{ trans('calendar::seat.ends_at') }}</b> : 
							@if($op->end_at)
								{{ $op->end_at }}
							@else
								<i>{{ trans('calendar::seat.unknown') }}</i>
							@endif
						</li>
						<li class="list-group-item">
							<b>{{ trans('calendar::seat.duration') }}</b> : 
							@if($op->end_at)
								{{ $op->duration }}
							@else
								<i>{{ trans('calendar::seat.unknown') }}</i>
							@endif
						</li>
						<li class="list-group-item">
							<b>{{ trans('calendar::seat.direct_link') }}</b> :
							<a href="{{ url('/calendar/operation', [$op->id]) }}">{{ url('/calendar/operation', [$op->id]) }}</a>
						</li>
					</ul>

					<ul class="list-group col-md-12">
						<li class="list-group-item">
							<b>{{ trans('calendar::seat.description') }}</b> : 
							@if($op->description)
								<span class="pre">{!! $op->description !!}</span>
							@else
								<i>{{ trans('calendar::seat.unknown') }}</i>
							@endif
						</li>
					</ul>

					<div class="clearfix"></div>
					
					<h3 style="display: inline;">{{ trans('calendar::seat.attendees') }}</h3>
					<div class="pull-right">
						@if($op->status == 'incoming')
							<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalSubscribe">
								<i class="fa fa-reply"></i>&nbsp;&nbsp; Subscribe
							</button>
						@endif
						@include('calendar::operation.includes.attendees')
					</div>
					
					<div class="clearfix" style="margin-bottom:20px;"></div>
					
					<table class="table table-condensed" id="attendees">
						<thead>
							<th>{{ trans('calendar::seat.character') }}</th>
							<th>{{ trans('calendar::seat.status') }}</th>
							<th class="no-sort">{{ trans('calendar::seat.comment') }}</th>
							<th class="no-sort">{{ trans('calendar::seat.answered_at') }}</th>
						</thead>
						<tbody>
							@foreach($op->attendees as $attendee)
								<tr>
									<td class="text-nowrap">
										&nbsp;
										<img src="http://image.eveonline.com/Character/{{ $attendee->character_id }}_64.jpg" class="img-circle eve-icon small-icon" />
										<a href="{{ route('character.view.sheet', ['character_id' => $attendee->character->characterID]) }}">
											{{ $attendee->character->characterName }}
										</a>
									</td>
									<td>
										@if ($attendee->status == "yes")
											<span class="label label-success">{{ trans('calendar::seat.attending_yes') }}</span>
										@endif
										@if ($attendee->status == "no")
											<span class="label label-danger">{{ trans('calendar::seat.attending_no') }}</span>
										@endif
										@if ($attendee->status == "maybe")
											<span class="label label-warning">{{ trans('calendar::seat.attending_maybe') }}</span>
										@endif
									</td>
									<td>
										{{ $attendee->comment }}
									</td>
									<td>
										<small class="text-nowrap">{{ $attendee->created_at }}</small>
										@if($attendee->created_at != $attendee->updated_at)
											<br/>
											<small class="text-nowrap">{{ trans('calendar::seat.update') }} : {{ $attendee->updated_at }}</small>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					<button type="button" class="btn btn-block btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
					<div class="clearfix"></div>
				</div>

			</div>
		</div>
	</div>
@endforeach