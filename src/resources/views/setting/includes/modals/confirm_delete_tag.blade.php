<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-red">
                <h4 class="modal-title">
                    <i class="fas fa-trash-alt"></i> {{ trans('calendar::seat.delete') }}
                </h4>
            </div>

            <div class="modal-body">
                <form id="formSubscribe" method="POST" action="{{ route('setting.tag.delete') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="tag_id">

                    <p>This action will delete definitely this tag. This can't be reverted, are you sure ?</p>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            <i class="fas fa-times-circle"></i> {{ trans('web::seat.no') }}
                        </button>
                        <button type="submit" class="btn btn-danger" id="confirm_delete_submit">
                            <i class="fas fa-check-circle"></i> {{ trans('web::seat.yes') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
