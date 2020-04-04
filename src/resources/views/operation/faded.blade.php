<div class="card">
    <div class="card-header bg-gradient-secondary">
        <h3 class="mb-0">
            <i class="fas fa-stop-circle"></i>
            {{ trans('calendar::seat.faded_operations') }}
        </h3>
    </div>
    <div class="card-body p-0 m-0">
        <table class="table table-striped table-hover mt-0" id="calendar-faded" style="margin-top: 0 !important;">
            <thead class="bg-secondary">
                <tr>
                    <th>{{ trans('calendar::seat.title') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.tags') }}</th>
                    <th>{{ trans('calendar::seat.importance') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.started_at') }}</th>
                    <th>{{ trans('calendar::seat.ended_at') }}</th>
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
