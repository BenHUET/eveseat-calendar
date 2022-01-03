<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow">
                <h4 class="modal-title">
                    <i class="fas fa-ban"></i>
                    {{ trans('calendar::seat.cancel') }}
                </h4>
            </div>
            <div class="modal-body">

                <p>{{ trans('calendar::seat.cancel_confirm_notice') }}</p>

                <form id="formCancel" method="POST" action="{{ route('operation.cancel') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="operation_id">

                    @if(setting('kassie.calendar.slack_integration', true) && setting('kassie.calendar.notify_cancel_operation', true))
                        <div class="form-group row">
                            <label for="cancel-operation-channel" class="col-sm-4 col-form-label">
                                {{ trans('calendar::seat.notification_enable') }}
                                &nbsp;<i class="fas fa-bell"></i>
                            </label>
                            <div class="col-sm-8">
                                <select name="integration_id" id="cancel-operation-channel" style="width: 100%;">
                                    <option></option>
                                    @foreach($notification_channels as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </form>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> {{ trans('web::seat.no') }}
                    </button>
                    <button type="button" class="btn btn-warning" id="confirm_cancel_submit">
                        <i class="fas fa-check-circle"></i> {{ trans('web::seat.yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script type="text/javascript">
    $('#cancel-operation-channel').select2({
        placeholder: "{{ trans('calendar::seat.integration_channel') }}",
        allowClear: true
    });
</script>
@endpush