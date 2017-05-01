<div class="modal fade" tabindex="-1" role="dialog" id="modalCreateOperation">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header modal-calendar modal-calendar-green">
				<p>
					<i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.add_operation') }}
				</p>
			</div>
			<div class="modal-body">

			<div class="alert alert-danger hidden" id="modal-errors">
				<ul></ul>
			</div>
				
				<form id="formCreateOperation">

					<div class="form-group row">
						<label for="title" class="col-sm-2 col-form-label">{{ trans('calendar::seat.title') }} *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="title" name="title" placeholder="{{ trans('calendar::seat.placeholder_title') }}">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label">{{ trans('calendar::seat.importance') }} *</label>
						<div class="col-sm-10">
							<input id="sliderImportance" class="form-control" name="importance" data-slider-id='sliderImportance' type="text" data-slider-min="0" data-slider-max="5" data-slider-step="0.5" data-slider-value="0"/>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="tags">{{ trans('calendar::seat.tags') }}</label>
						<div class="col-sm-10">
							@foreach($tags->chunk(6) as $tags)
								<div class="row">
									@foreach($tags as $tag)
										<div class="col-md-2">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="checkbox-{{$tag->id}}" id="checkbox-{{$tag->id}}" value="{{$tag->id}}"> 
													@include('calendar::common.includes.tag', ['tag' => $tag])
												</label>
											</div>
										</div>
									@endforeach
								</div>
							@endforeach
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="type">{{ trans('calendar::seat.known_duration') }}</label>
						<div class="col-sm-10">
							<label class="radio-inline">
								<input type="radio" name="known_duration" id="known_duration1" value="yes"> {{ trans('calendar::seat.yes') }}
							</label>
							<label class="radio-inline">
								<input type="radio" name="known_duration" id="known_duration2" value="no" checked> {{ trans('calendar::seat.no') }}
							</label>
						</div>
					</div>


					<div class="form-group row datepicker">
						<label class="col-sm-2 col-form-label">{{ trans('calendar::seat.starts_at') }} *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="time_start" id="time_start">
						</div>
					</div>

					<div class="form-group row datepicker hidden">
						<label class="col-sm-2 col-form-label">{{ trans('calendar::seat.duration') }} *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="time_start_end" id="time_start_end">
						</div>
					</div>


					<div class="form-group row">
						<label for="staging_sys" class="col-sm-2 col-form-label">{{ trans('calendar::seat.staging_sys') }}</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="staging_sys" name="staging_sys" placeholder="{{ trans('calendar::seat.placeholder_staging_sys') }}">
							<input type="hidden" name="staging_sys_id" id="staging_sys_id">
						</div>
					</div>

					<div class="form-group row">
						<label for="staging_info" class="col-sm-2 col-form-label">{{ trans('calendar::seat.staging_info') }}</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="staging_info" id="staging_info" placeholder="{{ trans('calendar::seat.placeholder_staging_info') }}">
						</div>
					</div>

					<div class="form-group row">
						<label for="fc" class="col-sm-2 col-form-label">{{ trans('calendar::seat.fleet_commander') }}</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="fc" id="fc" placeholder="{{ trans('calendar::seat.placeholder_fc') }}">
							<input type="hidden" name="fc_character_id" id="fc_character_id">
						</div>
					</div>

					<div class="form-group row">
						<label for="description" class="col-sm-2 col-form-label">{{ trans('calendar::seat.description') }}</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="description" id="description" rows="8" placeholder="{{ trans('calendar::seat.placeholder_description') }}"></textarea>
						</div>
					</div>

					@if($slack_integration == true)
						<div class="form-group row">
							<label for="notify" class="col-sm-2 col-form-label">
								<i class="fa fa-slack"></i>&nbsp;
								{{ trans('calendar::seat.notification_enable') }}
							</label>
							<div class="col-sm-10">
								<input type="checkbox" name="notify" id="notify" checked>
							</div>
						</div>
					@endif

					<button type="button" class="btn btn-block btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
					<button type="submit" class="btn btn-block btn-primary" id="create_operation_submit">{{ trans('calendar::seat.create_confirm_button_yes') }}</button>

				</form>

				<div class="clearfix"></div>

			</div>

		</div>
	</div>
</div>