<div class="modal fade" role="dialog" id="modalSubscribe">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-green">
				<h4 class="modal-title">
					<i class="fa fa-space-shuttle"></i>
					{{ trans('calendar::seat.subscribe') }}
				</h4>
			</div>
			<div class="modal-body">
				@if ($userCharacters->count() == 0)
				<p class="callout callout-danger text-justify">
					{{ trans('calendar::seat.warning_no_character') }}
				</p>
				@else
				<form class="form-horizontal" id="formSubscribe" method="POST" action="{{ route('operation.subscribe') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">
					<input type="hidden" name="status">

					{{-- Character --}}
					<div class="form-group">
						<label class="col-sm-2 control-label">{{ trans('calendar::seat.character') }}</label>
						<div class="col-sm-10">
							@foreach($userCharacters->chunk(3) as $characters)
							<div class="row">
								@foreach($characters as $character)
								<div class="radio col-md-4"  style="margin-top:-5px">
									<label>
										<input type="radio" name="character_id" value="{{ $character->characterID }}"
										   @if($loop->parent->first && $loop->first)
										   checked
											@endif
										>
										{!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
										@if($character->main)
										<b>{{ $character->characterName }}</b>
										<span class="text-muted"><i>(main)</i></span>
										@else
										{{ $character->characterName }}
										@endif
									</label>
								</div>
								@endforeach
							</div>
							@endforeach
						</div>
					</div>

					{{-- Status --}}
					<div class="form-group">
						<label for="status" class="col-sm-2 control-label">{{ trans('calendar::seat.status') }}</label>
						<div class="col-sm-10">
							<select name="status" class="selectpicker" id="status">
								<option value="yes">{{ trans('calendar::seat.attending_yes') }}</option>
								<option value="maybe">{{ trans('calendar::seat.attending_maybe') }}</option>
								<option value="no">{{ trans('calendar::seat.attending_no') }}</option>
							</select>
						</div>
					</div>

					{{-- Comment --}}
					<div class="form-group">
						<label for="comment" class="col-sm-2 control-label">{{ trans('calendar::seat.comment') }}</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="comment" name="comment"
								   placeholder="{{ trans('calendar::seat.placeholder_comment') }}">
						</div>
					</div>
				</form>
				@endif
			</div>
			<div class="modal-footer bg-green">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					{{ trans('calendar::seat.close') }}
				</button>
				@if ($userCharacters->count() > 0)
				<button type="button" class="btn btn-outline" id="subscribe_submit">
					{{ trans('calendar::seat.subscribe') }}
				</button>
				@endif
			</div>
		</div>
	</div>
</div>
