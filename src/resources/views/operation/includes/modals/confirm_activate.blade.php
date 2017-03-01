<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmActivate" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header modal-calendar modal-calendar-orange">
				<p>
					<i class="fa fa-undo"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.activate') }}
				</p>
			</div>

			<div class="modal-body">
				<p class="text-center"><b>{{ trans('calendar::seat.activate_confirm_notice') }}</b></p>

				<form id="formSubscribe" method="POST" action="{{ route('operation.activate') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">
					
					<button type="button " class="btn btn-block btn-lg btn-primary" data-dismiss="modal">{{ trans('calendar::seat.activate_confirm_button_no') }}</button>
					<button type="submit" class="btn btn-block btn-sm btn-default" id="confirm_activate_submit">{{ trans('calendar::seat.activate_confirm_button_yes') }}</button>
				</form>
			</div>

			<div class="modal-footer"></div>

		</div>
	</div>
</div>