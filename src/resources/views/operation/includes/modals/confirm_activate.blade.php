<div class="modal fade" role="dialog" id="modalConfirmActivate">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-green">
				<h4 class="modal-title">
					<i class="fa fa-undo"></i>
					{{ trans('calendar::seat.activate') }}
				</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<b>{{ trans('calendar::seat.activate_confirm_notice') }}</b>
				</p>
				<form id="formActivate" method="POST" action="{{ route('operation.activate') }}">
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
			<div class="modal-footer bg-green">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					{{ trans('calendar::seat.activate_confirm_button_no') }}
				</button>
				<button type="button" class="btn btn-outline" id="confirm_activate_submit">
					{{ trans('calendar::seat.activate_confirm_button_yes') }}
				</button>
			</div>
		</div>
	</div>
</div>
