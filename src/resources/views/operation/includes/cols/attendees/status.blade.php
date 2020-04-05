@if ($row->status == "yes")
    <span class="badge badge-success">{{ trans('calendar::seat.attending_yes') }}</span>
@endif
@if ($row->status == "no")
    <span class="badge badge-danger">{{ trans('calendar::seat.attending_no') }}</span>
@endif
@if ($row->status == "maybe")
    <span class="badge badge-warning">{{ trans('calendar::seat.attending_maybe') }}</span>
@endif
