<div class="modal fade" tabindex="-1" role="dialog" id="modalUpdateOperation">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header modal-calendar modal-calendar-yellow">
				<p>
					<i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.update_operation') }}
				</p>
			</div>

			<div class="modal-body">

				<div class="alert alert-danger hidden" id="modal-errors">
					<ul></ul>
				</div>
				
				<form id="formUpdateOperation">

					<input type="hidden" name="operation_id">

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
						<label class="col-sm-2 col-form-label" for="type">{{ trans('calendar::seat.type') }} *</label>
						<div class="col-sm-10">
							<select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="type" name="type">
								<option value="Other">{{ trans('calendar::seat.other') }}</option>
								<option value="PvE">PvE</option>
								<option value="PvP">PvP</option>
							</select>
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

					<div class="form-group row datepicker">
						<label class="col-sm-2 col-form-label">{{ trans('calendar::seat.duration') }} *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="time_start_end" id="time_start_end">
						</div>
					</div>


					<div class="form-group row">
						<label for="staging" class="col-sm-2 col-form-label">{{ trans('calendar::seat.staging') }}</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="staging" id="staging" placeholder="{{ trans('calendar::seat.placeholder_staging') }}">
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
								<input type="checkbox" name="notify" id="notify">
							</div>
						</div>
					@endif

					<button type="button" class="btn btn-block btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
					<button type="submit" class="btn btn-block btn-primary" id="update_operation_submit">{{ trans('calendar::seat.update_confirm_button_yes') }}</button>

				</form>

				<div class="clearfix"></div>

			</div>

		</div>
	</div>
</div>