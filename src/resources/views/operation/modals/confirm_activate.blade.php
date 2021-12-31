<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmActivate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h4 class="modal-title">
                    <i class="fas fa-undo"></i>
                    {{ trans('calendar::seat.activate') }}
                </h4>
            </div>
            <div class="modal-body">
                <p>{{ trans('calendar::seat.activate_confirm_notice') }}</p>

                <form id="formActivate" method="POST" action="{{ route('operation.activate') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="operation_id">

                    @if(setting('kassie.calendar.slack_integration', true) == true && setting('kassie.calendar.notify_activate_operation', true))
                    <div class="form-group row">
                        <label for="activate-operation-channel" class="col-sm-4 col-form-label">
                            {{ trans('calendar::seat.notification_enable') }}
                            &nbsp;<i class="fas fa-bell"></i>
                        </label>
                        <div class="col-sm-8">
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

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> {{ trans('web::seat.no') }}
                    </button>
                    <button type="button" class="btn btn-success" id="confirm_activate_submit">
                        <i class="fas fa-check-circle"></i> {{ trans('web::seat.yes') }}
                    </button>
                </div>
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