@foreach($ops_all as $op)
	<div class="modal fade" tabindex="-1" role="dialog" id="modalDetails-{{ $op->id }}" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">

				<div class="modal-header modal-calendar modal-calendar-green">
					<p>
						<i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.details') }}
					</p>
				</div>

				<div class="modal-body">
					<h2 class="text-center"><b>“{{ $op->title }}”</b></h2>

					<p>{{ $op->description }}</p>

					<table>
						<thead>
							<th></th>
						</thead>
						@foreach($op->attendees as $attendee)
							<img src="http://image.eveonline.com/Character/{{ $attendee->character_id }}_64.jpg" class="img-circle eve-icon medium-icon" />
							&nbsp;
							{{ $attendee->character->characterName }}
						@endforeach
					</table>

					<button type="button" class="btn btn-block btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
					<div class="clearfix"></div>
				</div>

			</div>
		</div>
	</div>
@endforeach