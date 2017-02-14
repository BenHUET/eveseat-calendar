<div class="modal fade" tabindex="-1" role="dialog" id="modalCreateOperation" data-backdrop="static" data-keyboard="false">
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
							@for ($i = 0; $i <= 10; $i++)
								<label class="radio-inline">
									<input type="radio" name="importance" id="{{ 'importanceRadio' . $i }}" value="{{ $i }}"> {{ $i }}
								</label>
							@endfor
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

					<div class="form-group row datepicker hidden">
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
						</div>
					</div>

					<div class="form-group row">
						<label for="description" class="col-sm-2 col-form-label">{{ trans('calendar::seat.description') }}</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="description" id="description" rows="8" placeholder="{{ trans('calendar::seat.placeholder_description') }}"></textarea>
						</div>
					</div>

					<div class="pull-right">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
						<button type="submit" class="btn btn-success" id="create_operation_submit">{{ trans('calendar::seat.confirm') }}</button>
					</div>

				</form>

				<div class="clearfix"></div>

			</div>

			<div class="modal-footer"></div>

		</div>
	</div>
</div>