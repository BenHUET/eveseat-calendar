<div class="modal fade" role="dialog" id="modalConfirmClose">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-aqua">
				<h4 class="modal-title">
					<i class="fa fa-check"></i>
					{{ trans('calendar::seat.close') }}
				</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<b>{{ trans('calendar::seat.close_confirm_notice') }}</b>
				</p>
				<form id="formClose" method="POST" action="{{ route('operation.close') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">
				</form>
			</div>
			<div class="modal-footer bg-aqua">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					{{ trans('calendar::seat.close_confirm_button_no') }}
				</button>
				<button type="button" class="btn btn-outline" id="confirm_close_submit">
					{{ trans('calendar::seat.close_confirm_button_yes') }}
				</button>
			</div>
		</div>
	</div>
</div>
