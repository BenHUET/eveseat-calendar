<div class="card card-info">
    <div class="card-header with-border p-0">
        <h3 class="card-title p-3">
            <i class="fab fa-slack"></i> {{ trans('calendar::seat.slack_integration') }}
        </h3>
    </div>
    <form class="form-horizontal" method="POST" action="{{ route('setting.slack.update') }}">
        {{ csrf_field() }}
        <div class="card-body">
            <div class="form-group row">
                <label for="slack_integration" class="col-sm-3 col-form-label">{{ trans('calendar::seat.enabled') }}</label>
                <div class="col-sm-9">
                    <div class="form-check">
                        @if(setting('kassie.calendar.slack_integration', true) == 1)
                            <input type="checkbox" name="slack_integration" class="form-check-input" id="slack_integration" value="1" checked />
                        @else
                            <input type="checkbox" name="slack_integration" class="form-check-input" id="slack_integration" value="1" />
                        @endif
                    </div>
                </div>
                <label for="slack_integration_default_channel" class="col-sm-3 col-form-label">{{ trans('calendar::seat.default_channel') }}</label>
                <div class="col-sm-9">
                    <select name="slack_integration_default_channel" class="form-control">
                        <option value="">None</option>

                        @foreach ($slack_integrations as $slack_integration)
                            @if ($slack_integration->id == setting('kassie.calendar.slack_integration_default_channel', true)) {
                                <option value="{{ $slack_integration->id }}" selected>{{ $slack_integration->name }}</option>
                            @else
                                <option value="{{ $slack_integration->id }}">{{ $slack_integration->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ trans('calendar::seat.notifications_to_send') }}</label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input type="checkbox"
                               name="notify_create_operation"
                               class="form-check-input"
                               value="1"
                               @if(setting('kassie.calendar.notify_create_operation', true)) checked @endif />
                               {{ trans('calendar::seat.create_operation') }}<br>
                        <input type="checkbox"
                               name="notify_update_operation"
                               class="form-check-input"
                               value="1"
                               @if(setting('kassie.calendar.notify_update_operation', true)) checked @endif />
                               {{ trans('calendar::seat.update_operation') }}<br>
                        <input type="checkbox"
                               name="notify_cancel_operation"
                               class="form-check-input"
                               value="1"
                               @if(setting('kassie.calendar.notify_cancel_operation', true)) checked @endif />
                               {{ trans('calendar::seat.cancel_operation') }}<br>
                        <input type="checkbox"
                               name="notify_activate_operation"
                               class="form-check-input"
                               value="1"
                               @if(setting('kassie.calendar.notify_activate_operation', true)) checked @endif />
                               {{ trans('calendar::seat.activate_operation') }}<br>
                        <input type="checkbox"
                               name="notify_end_operation"
                               class="form-check-input"
                               value="1"
                               @if(setting('kassie.calendar.notify_end_operation', true)) checked @endif />
                               {{ trans('calendar::seat.end_operation') }}
                    </div>
                </div>
            </div>

            <p class="callout callout-info text-justify">
                {!! trans('calendar::seat.help_notify_operation_interval', ['default_interval' => '<code>15,30,60</code>']) !!}
            </p>
            <div class="form-group row">
                <label for="notify_operation_interval" class="col-sm-3 col-form-label">{{ trans('calendar::seat.ping_intervals') }}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="notify_operation_interval"
                           value="{{ setting('kassie.calendar.notify_operation_interval', true) }}">
                </div>
            </div>

            <p class="callout callout-info text-justify">
                {{ trans('calendar::seat.help_emoji') }}
            </p>

            <div class="form-group row">
                <label for="slack_emoji_importance_full" class="col-sm-3 col-form-label">{{ trans('calendar::seat.emoji_full') }}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="slack_emoji_importance_full"
                           id="slack_emoji_importance_full" value="{{ setting('kassie.calendar.slack_emoji_importance_full', true) }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="slack_emoji_importance_half" class="col-sm-3 col-form-label">{{ trans('calendar::seat.emoji_half') }}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="slack_emoji_importance_half" id="slack_emoji_importance_half"
                           value="{{ setting('kassie.calendar.slack_emoji_importance_half', true) }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="slack_emoji_importance_empty" class="col-sm-3 col-form-label">{{ trans('calendar::seat.emoji_empty') }}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="slack_emoji_importance_empty" id="slack_emoji_importance_empty"
                           value="{{ setting('kassie.calendar.slack_emoji_importance_empty', true) }}">
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info float-right">{{ trans('calendar::seat.save') }}</button>
        </div>
    </form>
</div>
