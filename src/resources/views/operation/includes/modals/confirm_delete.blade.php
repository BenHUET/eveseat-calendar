<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header modal-calendar modal-calendar-red">
				<p>
					<i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;{{ trans('calendar::seat.delete') }}
				</p>
			</div>

			<div class="modal-body">
				<p class="text-center text-uppercase"><b>{{ trans('calendar::seat.delete_confirm_notice') }}</b></p>

				<form id="formSubscribe" method="POST" action="{{ route('operation.delete') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">
				
					<button type="button " class="btn btn-block btn-lg btn-primary" data-dismiss="modal">{{ trans('calendar::seat.delete_confirm_button_no') }}</button>
					<button type="submit" class="btn btn-block btn-sm btn-danger" id="confirm_delete_submit">{{ trans('calendar::seat.delete_confirm_button_yes') }}</button>
				</form>
			</div>

		</div>
	</div>
</div>