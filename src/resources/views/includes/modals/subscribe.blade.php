<div class="modal fade" tabindex="-1" role="dialog" id="modalSubscribe" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header modal-calendar modal-calendar-green modal-attending attending-yes hidden">
				<p>
					<i class="fa fa-smile-o"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.attending_yes') }}
				</p>
			</div>

			<div class="modal-header modal-calendar modal-calendar-yellow modal-attending attending-maybe hidden">
				<p>
					<i class="fa fa-meh-o"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.attending_maybe') }}
				</p>
			</div>

			<div class="modal-header modal-calendar modal-calendar-red modal-attending attending-no hidden">
				<p>
					<i class="fa fa-frown-o"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.attending_no') }}
				</p>
			</div>

			<div class="modal-body">
				<form id="formSubscribe" method="POST" action="{{ route('calendar.operation.subscribe') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">
					<input type="hidden" name="status">
					<div class="form-group row">
						<label for="comment" class="col-sm-2 col-form-label">{{ trans('calendar::seat.comment') }}</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="comment" name="comment" placeholder="{{ trans('calendar::seat.placeholder_comment') }}">
						</div>
					</div>
					<div class="pull-right">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('calendar::seat.close') }}</button>
						<button type="submit" class="btn btn-success" id="subscibe_submit">{{ trans('calendar::seat.confirm') }}</button>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>

			<div class="modal-footer"></div>

		</div>
	</div>
</div>