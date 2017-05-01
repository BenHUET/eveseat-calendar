<div class="modal fade modal-subscribe" tabindex="-1" role="dialog" id="modalSubscribe">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header modal-calendar modal-calendar-green" id="headerModalSubscribe">
				<p>
					<i class="fa fa-reply"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.subscribe') }}
				</p>
			</div>

			<div class="modal-body">
				@if ($userCharacters->count() == 0)
					<p class="callout callout-danger text-justify">
						{{ trans('calendar::seat.warning_no_character') }}
					</p>
				@else
					<form id="formSubscribe" method="POST" action="{{ route('operation.subscribe') }}">
						{{ csrf_field() }}
						<input type="hidden" name="operation_id">
						<input type="hidden" name="status">

						<div class="form-group row">
							<label for="character" class="col-sm-2 col-form-label">{{ trans('calendar::seat.character') }}</label>
							<div class="col-sm-10">
								@foreach($userCharacters->chunk(4) as $characters)
									<div class="row">
										@foreach($characters as $character)
											<div class="radio col-md-3"  style="margin-top:-5px">
												<label>
													<input type="radio" name="character_id" id="character_id" value="{{ $character->characterID }}" 
														@if($loop->parent->first && $loop->first)
															checked 
														@endif
													>
													{!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
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

						<div class="form-group row">
							<label for="status" class="col-sm-2 col-form-label">{{ trans('calendar::seat.status') }}</label>
							<div class="col-sm-10">
								<select name="status" class="selectpicker" id="status">
									<option value="yes">{{ trans('calendar::seat.attending_yes') }}</option>
									<option value="maybe">{{ trans('calendar::seat.attending_maybe') }}</option>
									<option value="no">{{ trans('calendar::seat.attending_no') }}</option>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label for="comment" class="col-sm-2 col-form-label">{{ trans('calendar::seat.comment') }}</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="comment" name="comment" placeholder="{{ trans('calendar::seat.placeholder_comment') }}">
							</div>
						</div>

					@endif

					<button type="button" class="btn btn-block btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>

					@if ($userCharacters->count() > 0)
						<button type="submit" class="btn btn-block btn-primary" id="subscibe_submit">{{ trans('calendar::seat.subscribe') }}</button>
					@endif

				</form>
				<div class="clearfix"></div>
			</div>

		</div>
	</div>
</div>