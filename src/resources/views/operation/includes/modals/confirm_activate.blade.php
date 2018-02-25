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
                <form id="formActivate" method="POST" action="{{ route('operation.activate') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="operation_id">

                    @if(setting('kassie.calendar.slack_integration', true) == true)
                    <div class="form-group">
                        <label for="activate-operation-channel" class="col-sm-3 control-label">
                            {{ trans('calendar::seat.notification_enable') }}
                            &nbsp;<i class="fa fa-slack"></i>
                        </label>
                        <div class="col-sm-9">
                            <select name="integration_id" id="activate-operation-channel" style="width: 100%;">
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

@push('javascript')
    <script type="text/javascript">
        $('#activate-operation-channel').select2({
            placeholder: "{{ trans('calendar::seat.integration_channel') }}",
            allowClear: true
        });
    </script>
@endpush