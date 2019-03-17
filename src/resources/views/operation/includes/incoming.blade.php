<div class="box box-widget widget-user-2">
    <div class="widget-user-header bg-aqua">
        <h4 class="widget-user-username">
            <i class="fa fa-arrow-circle-right"></i>
            {{ trans('calendar::seat.incoming_operations') }}
        </h4>
    </div>
    <div class="box-footer no-padding">
        <table class="table table-striped table-hover" id="calendar-incoming" style="margin-top: 0 !important;">
            <thead class="bg-aqua">
                <tr>
                    <th>{{ trans('calendar::seat.title') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.tags') }}</th>
                    <th>{{ trans('calendar::seat.importance') }}</th>
                    <th>{{ trans('calendar::seat.starts_in') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.duration') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.fleet_commander') }}</th>
                    <th>{{ trans('calendar::seat.staging') }}</th>
                    <th class="hidden-portrait-xs">{{ trans('calendar::seat.subscription') }}</th>
                    <th class="hidden-xs"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
