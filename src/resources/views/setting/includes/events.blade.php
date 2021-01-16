<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-slack"></i> {{ trans('calendar::seat.events') }}</h3>
    </div>
    <form class="form-horizontal" method="POST" action="{{ route('setting.events.update') }}">
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="event_create" class="col-sm-3 control-label">{{ trans('calendar::seat.event.create') }}</label>
                <div class="col-sm-9">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="event_create" id="event_create" value="1"
                                   @if(setting('kassie.calendar.event.create', true) == 1) checked @endif>
                        </label>
                    </div>
                </div>
                <label for="event_edit" class="col-sm-3 control-label">{{ trans('calendar::seat.event.edit') }}</label>
                <div class="col-sm-9">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="event_edit" id="event_edit" value="1"
                                   @if(setting('kassie.calendar.event.edit', true) == 1) checked @endif>
                        </label>
                    </div>
                </div>
                <label for="event_remind" class="col-sm-3 control-label">{{ trans('calendar::seat.event.remind') }}</label>
                <div class="col-sm-9">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="event_remind" id="event_remind" value="1"
                                   @if(setting('kassie.calendar.event.remind', true) == 1) checked @endif>
                        </label>
                    </div>
                </div>
                <label for="event_cancel" class="col-sm-3 control-label">{{ trans('calendar::seat.event.cancel') }}</label>
                <div class="col-sm-9">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="event_cancel" id="event_cancel" value="1"
                                   @if(setting('kassie.calendar.event.cancel', true) == 1) checked @endif>
                        </label>
                    </div>
                </div>
                <label for="event_start" class="col-sm-3 control-label">{{ trans('calendar::seat.event.start') }}</label>
                <div class="col-sm-9">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="event_start" id="event_start" value="1"
                                   @if(setting('kassie.calendar.event.start', true) == 1) checked @endif>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">{{ trans('calendar::seat.save') }}</button>
        </div>
    </form>
</div>
