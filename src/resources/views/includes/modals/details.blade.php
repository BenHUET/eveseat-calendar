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

					@foreach($op->attendees as $attendee)
						{{-- {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!} --}}
						{{ $attendee->user }}
					@endforeach

					<button type="button" class="btn btn-block btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
					<div class="clearfix"></div>
				</div>

			</div>
		</div>
	</div>
@endforeach