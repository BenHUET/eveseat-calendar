<div class="modal fade" role="dialog" id="modalConfirmDelete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-red">
				<h4 class="modal-title">
					<i class="fa fa-trash"></i>
					{{ trans('calendar::seat.delete') }}
				</h4>
			</div>
			<div class="modal-body">
				<p class="text-center text-uppercase">
					<b>{{ trans('calendar::seat.delete_confirm_notice') }}</b>
				</p>
				<form id="formDelete" method="POST" action="{{ route('operation.delete') }}">
					{{ csrf_field() }}
					<input type="hidden" name="operation_id">
				</form>
			</div>
			<div class="modal-footer bg-red">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					{{ trans('calendar::seat.delete_confirm_button_no') }}
				</button>
				<button type="button" class="btn btn-outline" id="confirm_delete_submit">
					{{ trans('calendar::seat.delete_confirm_button_yes') }}
				</button>
			</div>
		</div>
	</div>
</div>
