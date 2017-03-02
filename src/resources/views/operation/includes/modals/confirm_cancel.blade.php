<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmCancel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header modal-calendar modal-calendar-orange">
				<p>
					<i class="fa fa-ban"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.cancel') }}
				</p>
			</div>

			<div class="modal-body">
				<p class="text-center"><b>{{ trans('calendar::seat.cancel_confirm_notice') }}</b></p>

				<form id="formSubscribe" method="POST" action="{{ route('operation.cancel') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">

					@if($slack_integration == true)
						<div class="checkbox">
							<label>
								<input type="checkbox" name="notify" id="notify">
								{{ trans('calendar::seat.notification_enable') }}
								&nbsp;<i class="fa fa-slack"></i>
							</label>
						</div>
					@endif
					
					<button type="button " class="btn btn-block btn-lg btn-primary" data-dismiss="modal">{{ trans('calendar::seat.cancel_confirm_button_no') }}</button>
					<button type="submit" class="btn btn-block btn-sm btn-default" id="confirm_cancel_submit">{{ trans('calendar::seat.cancel_confirm_button_yes') }}</button>
				</form>
			</div>

			<div class="modal-footer"></div>

		</div>
	</div>
</div>