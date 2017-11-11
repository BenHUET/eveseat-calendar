<div class="modal fade" role="dialog" id="modalConfirmCancel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-yellow">
				<h4 class="modal-title">
					<i class="fa fa-ban"></i>
					{{ trans('calendar::seat.cancel') }}
				</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<b>{{ trans('calendar::seat.cancel_confirm_notice') }}</b>
				</p>
				<form id="formCancel" method="POST" action="{{ route('operation.cancel') }}">
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
				</form>
			</div>
			<div class="modal-footer bg-yellow">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					{{ trans('calendar::seat.cancel_confirm_button_no') }}
				</button>
				<button type="button" class="btn btn-outline" id="confirm_cancel_submit">
					{{ trans('calendar::seat.cancel_confirm_button_yes') }}
				</button>
			</div>
		</div>
	</div>
</div>
