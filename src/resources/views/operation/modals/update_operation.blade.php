<div class="modal fade" tabindex="-1" role="dialog" id="modalUpdateOperation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-yellow">
                <h4 class="modal-title">
                    <i class="fas fa-pencil-alt"></i>
                    {{ trans('calendar::seat.update_operation') }}
                </h4>
            </div>
            <div class="modal-body">
                <div class="overlay dark d-flex justify-content-center align-items-center">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>

                <div class="modal-errors alert alert-danger d-none">
                    <ul></ul>
                </div>
                <form class="form-horizontal" id="formUpdateOperation">
                    <input type="hidden" name="operation_id">

                    {{-- Operation title --}}
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">{{ trans('calendar::seat.title') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" placeholder="{{ trans('calendar::seat.placeholder_title') }}">
                        </div>
                    </div>
                    {{-- Operation role restriction --}}
                    <div class="form-group row">
                        <label for="update_operation_role" class="col-sm-3 col-form-label">{{ trans_choice('web::seat.role', 1) }}</label>
                        <div class="col-sm-9">
                            <select name="role_name" id="update_operation_role" style="width: 100%">
                                <option value=""></option>
                                @foreach($roles as $role)
                                <option value="{{ $role->title }}">{{ $role->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Operation importance --}}
                    <div class="form-group row">
                        <label for="importance" class="col-sm-3 col-form-label">{{ trans('calendar::seat.importance') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="slider form-control" value="2" data-slider-min="0" data-slider-max="5"
                                   data-slider-step="0.5" data-slider-value="2" data-slider-id="updateSliderImportance"
                                   data-slider-tooltip="show" data-slider-handle="round" name="importance"/>
                        </div>
                    </div>
                    {{-- Operation tags --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{ trans('calendar::seat.tags') }}</label>
                        <div class="col-sm-9">
                            @foreach($tags->chunk(4) as $tags)
                                <div class="row">
                                    @foreach($tags as $tag)
                                        <div class="col-sm-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checkbox-{{$tag->id}}" id="checkbox-update-{{$tag->id}}" value="{{$tag->id}}">
                                                    @include('calendar::common.includes.tag', ['tag' => $tag])
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- Operation duration --}}
                    <div class="form-group row">
                        <label for="known_duration" class="col-sm-3 col-form-label">{{ trans('calendar::seat.known_duration') }}</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="known_duration" value="yes"> {{ trans('calendar::seat.yes') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="known_duration" value="no" checked> {{ trans('calendar::seat.no') }}
                            </label>
                        </div>
                    </div>
                    {{-- Operation starts --}}
                    <div class="form-group row datepicker">
                        <label for="time_start" class="col-sm-3 col-form-label">{{ trans('calendar::seat.starts_at') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="time_start">
                        </div>
                    </div>
                    {{-- Operation duration --}}
                    <div class="form-group row datepicker d-none">
                        <label for="time_start_end" class="col-sm-3 col-form-label">{{ trans('calendar::seat.duration') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="time_start_end">
                        </div>
                    </div>
                    {{-- Operation staging system --}}
                    <div class="form-group row">
                        <label for="staging_sys" class="col-sm-3 col-form-label">{{ trans('calendar::seat.staging_sys') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="staging_sys"
                                   placeholder="{{ trans('calendar::seat.placeholder_staging_sys') }}">
                            <input type="hidden" name="staging_sys_id">
                        </div>
                    </div>
                    {{-- Operation staging info --}}
                    <div class="form-group row">
                        <label for="staging_info" class="col-sm-3 col-form-label">{{ trans('calendar::seat.staging_info') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="staging_info"
                                   placeholder="{{ trans('calendar::seat.placeholder_staging_info') }}">
                        </div>
                    </div>
                    {{-- Operation FC --}}
                    <div class="form-group row">
                        <label for="fc" class="col-sm-3 col-form-label">{{ trans('calendar::seat.fleet_commander') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="fc"
                                   placeholder="{{ trans('calendar::seat.placeholder_fc') }}">
                            <input type="hidden" name="fc_character_id">
                        </div>
                    </div>
                    {{-- Operation description --}}
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">{{ trans('calendar::seat.description') }}</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="description" rows="8"
                                      placeholder="{{ trans('calendar::seat.placeholder_description') }}"></textarea>
                        </div>
                    </div>
                    {{-- Operation slack --}}
                    @if(setting('kassie.calendar.slack_integration', true) == true)
                        <div class="form-group row">
                            <label for="update-operation-channel" class="col-sm-3 col-form-label">
                                <i class="fas fa-bell"></i>&nbsp;
                                {{ trans('calendar::seat.notification_enable') }}
                            </label>
                            <div class="col-sm-9">
                                <select name="integration_id" id="update-operation-channel" style="width: 100%;">
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
                    <button type="button" class="btn btn-light pull-left" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> {{ trans('calendar::seat.close') }}
                    </button>
                    <button type="button" class="btn btn-warning" id="update_operation_submit">
                        <i class="fas fa-check-circle"></i> {{ trans('calendar::seat.update_confirm_button_yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script type="text/javascript">
    $('#update_operation_role').select2({
        placeholder: "{{ trans('calendar::seat.select_role_filter_placeholder') }}",
        allowClear: true
    });

    $('#update-operation-channel').select2({
        placeholder: "{{ trans('calendar::seat.integration_channel') }}",
        allowClear: true
    });
</script>
@endpush