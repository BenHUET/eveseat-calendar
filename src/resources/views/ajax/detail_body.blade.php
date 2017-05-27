<h2>{{ $op->title }}
    <span class="pull-right">@include('calendar::operation.includes.importance')</span>
</h2>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid no-border no-shadow no-margin">
            <div class="box-header with-border">
                <h3>{{ trans('calendar::seat.informations') }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group no-margin">
                            <li class="list-group-item no-border">
                                <b>{{ trans('calendar::seat.status') }}</b> :
                                @if($op->status == 'cancelled')
                                    <span class="label label-danger">{{ trans('calendar::seat.cancelled') }}</span>
                                @else
                                    @if($op->status == 'ongoing')
                                        <span class="label label-success">{{ trans('calendar::seat.ongoing_operations') }}</span>
                                    @endif
                                    @if($op->status == 'incoming')
                                        <span class="label label-primary">{{ trans('calendar::seat.incoming_operations') }}</span>
                                    @endif
                                    @if($op->status == 'faded')
                                        <span class="label label-faded">{{ trans('calendar::seat.faded_operations') }}</span>
                                    @endif
                                @endif
                            </li>

                            <li class="list-group-item no-border">
                                <b>{{ trans('calendar::seat.tags') }}</b> :
                                @include('calendar::operation.includes.tags', ['chunk' => false])
                            </li>

                            <li class="list-group-item no-border">
                                <b>{{ trans('calendar::seat.fleet_commander') }}</b> :
                                @if($op->fc)
                                    @if($op->fc_character_id)
                                        &nbsp;
                                    @endif
                                    @include('calendar::operation.includes.fleet_commander')
                                @else
                                    <i>{{ trans('calendar::seat.unknown') }}</i>
                                @endif
                            </li>

                            <li class="list-group-item no-border">
                                <b>{{ trans('calendar::seat.staging') }}</b> :
                                @if($op->staging_sys_id)
                                    @include('calendar::operation.includes.staging')
                                @else
                                    <i>{{ trans('calendar::seat.unknown') }}</i>
                                @endif
                            </li>

                            <li class="list-group-item no-border">
                                <b>{{ trans('calendar::seat.staging_info') }}</b> :
                                @if($op->staging_info)
                                    {{ $op->staging_info }}
                                @else
                                    <i>{{ trans('calendar::seat.unknown') }}</i>
                                @endif
                            </li>

                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group no-margin">
                            <li class="list-group-item no-border"><b>{{ trans('calendar::seat.starts_at') }}</b> : {{ $op->start_at }}</li>
                            <li class="list-group-item no-border">
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
                    <div class="col-md-12">
                        <ul class="list-group no-margin">
                            <li class="list-group-item no-border">
                                <b>{{ trans('calendar::seat.description') }}</b> :
                                @if($op->description)
                                    <span class="pre">{!! $op->parsed_description !!}</span>
                                @else
                                    <i>{{ trans('calendar::seat.unknown') }}</i>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid no-border no-shadow">
            <div class="box-header with-border">
                <h3 class="inline">{{ trans('calendar::seat.attendees') }}</h3>
                <span class="pull-right">
                    @if($op->status == 'incoming')
                        <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" data-toggle="modal" data-op-id="{{ $op->id }}" data-target="#modalSubscribe">
                            <i class="fa fa-reply"></i>&nbsp;&nbsp; {{ trans('calendar::seat.subscribe') }}
                        </button>
                    @endif
                    @include('calendar::operation.includes.attendees')
                </span>
            </div>
            <div class="box-body">
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
            </div>
        </div>
    </div>
</div>
