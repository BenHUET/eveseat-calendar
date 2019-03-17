<div class="box box-widget widget-user-2">
    <div class="widget-user-header bg-green">
        <h4 class="widget-user-username">
            <i class="fa fa-play-circle"></i>
            {{ trans('calendar::seat.ongoing_operations') }}
        </h4>
    </div>
    <div class="box-footer no-padding">
        <table class="table table-striped table-hover" id="calendar-ongoing" style="margin-top: 0 !important;">
            <thead class="bg-green">
                <tr>
                    <th>{{ trans('calendar::seat.title') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.tags') }}</th>
                    <th>{{ trans('calendar::seat.importance') }}</th>
                    <th>{{ trans('calendar::seat.started') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.ends_in') }}</th>
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
