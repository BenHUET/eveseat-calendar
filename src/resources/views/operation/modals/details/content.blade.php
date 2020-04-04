<h2>
  {{ $op->title }}
  <span class="float-right">@include('calendar::operation.partials.importance')</span>
</h2>

<h3>{{ trans('calendar::seat.informations') }}</h3>
<hr/>

<div class="row">
  <div class="col-6 p-0">
    <ul class="list-group list-group-flush">
      <li class="list-group-item">
        <b>{{ trans('calendar::seat.status') }}</b> :
        @switch($op->status)
          @case('cancelled')
          <span class="badge badge-danger">{{ trans('calendar::seat.cancelled') }}</span>
          @break
          @case('ongoing')
          <span class="badge badge-success">{{ trans('calendar::seat.ongoing_operations') }}</span>
          @break
          @case('incoming')
          <span class="badge badge-info">{{ trans('calendar::seat.incoming_operations') }}</span>
          @break
          @case('faded')
          <span class="badge badge-secondary">{{ trans('calendar::seat.faded_operations') }}</span>
          @break
        @endswitch
      </li>

      <li class="list-group-item">
        <b>{{ trans('calendar::seat.tags') }}</b> :
        @include('calendar::operation.partials.tags', ['chunk' => false])
      </li>

      <li class="list-group-item">
        <b>{{ trans('calendar::seat.fleet_commander') }}</b> :
        @if($op->fc)
          @include('calendar::operation.partials.fleet_commander')
        @else
          <i>{{ trans('calendar::seat.unknown') }}</i>
        @endif
      </li>

      <li class="list-group-item">
        <b>{{ trans('calendar::seat.staging') }}</b> :
        @if($op->staging_sys_id)
          @include('calendar::operation.partials.staging')
        @else
          <i>{{ trans('calendar::seat.unknown') }}</i>
        @endif
      </li>

      <li class="list-group-item">
        <b>{{ trans('calendar::seat.staging_info') }}</b> :
        @if($op->staging_info)
          {{ $op->staging_info }}
        @else
          <i>{{ trans('calendar::seat.unknown') }}</i>
        @endif
      </li>

    </ul>
  </div>
  <div class="col-6 p-0">
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><b>{{ trans('calendar::seat.starts_at') }}</b> : {{ $op->start_at }}</li>
      <li class="list-group-item">
        <b>{{ trans('calendar::seat.ends_at') }}</b> :
        @if($op->end_at)
          {{ $op->end_at }}
        @else
          <i>{{ trans('calendar::seat.unknown') }}</i>
        @endif
      </li>
      <li class="list-group-item no-border">
        <b>{{ trans('calendar::seat.duration') }}</b> :
        @if($op->end_at)
          {{ $op->duration }}
        @else
          <i>{{ trans('calendar::seat.unknown') }}</i>
        @endif
      </li>
      <li class="list-group-item no-border">
        <b>{{ trans('calendar::seat.direct_link') }}</b> :
        <a href="{{ url('/calendar/operation', [$op->id]) }}">{{ url('/calendar/operation', [$op->id]) }}</a>
      </li>

    </ul>
  </div>
</div>

<div class="row">
  <div class="col-12">
    @if($op->description)
      <blockquote class="quote-secondary">{!! $op->parsed_description !!}</blockquote>
    @else
      <i>{{ trans('calendar::seat.unknown') }}</i>
    @endif
  </div>
</div>

<h3>
  {{ trans('calendar::seat.attendees') }}
  <span class="float-right">
    @if($op->status == 'incoming')
      <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalSubscribe">
        <i class="fas fa-reply"></i>&nbsp;&nbsp; {{ trans('calendar::seat.subscribe') }}
      </button>
    @endif
    @include('calendar::operation.partials.attendees')
  </span>
</h3>
<hr/>

<table class="table table-condensed" id="attendees">
  <thead>
    <tr>
      <th>{{ trans('calendar::seat.character') }}</th>
      <th>{{ trans('calendar::seat.status') }}</th>
      <th class="no-sort">{{ trans('calendar::seat.comment') }}</th>
      <th class="no-sort">{{ trans('calendar::seat.answered_at') }}</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<h3>{{ trans('calendar::seat.confirmed') }}</h3>
<hr/>

<table class="table table-condensed" id="confirmed">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.character', 1) }}</th>
      <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
      <th>{{ trans('web::seat.ship_type') }}</th>
      <th>{{ trans_choice('web::seat.group', 1) }}</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>