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
                <form id="formCancel" method="POST" action="{{ route('operation.cancel') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="operation_id">

                    @if(setting('kassie.calendar.slack_integration', true) == true)
                    <div class="form-group">
                        <label for="cancel-operation-channel" class="col-sm-3 control-label">
                            {{ trans('calendar::seat.notification_enable') }}
                            &nbsp;<i class="fa fa-slack"></i>
                        </label>
                        <div class="col-sm-9">
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

@push('javascript')
<script type="text/javascript">
    $('#cancel-operation-channel').select2({
        placeholder: "{{ trans('calendar::seat.integration_channel') }}",
        allowClear: true
    });
</script>
@endpush