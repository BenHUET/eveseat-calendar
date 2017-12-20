<div class="modal fade" role="dialog" id="modalCreateOperation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h4 class="modal-title">
                    <i class="fa fa-space-shuttle"></i>
                    {{ trans('calendar::seat.add_operation') }}
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal-errors alert alert-danger hidden">
                    <ul></ul>
                </div>
                <form class="form-horizontal" id="formCreateOperation">
                    {{-- Operation title --}}
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">{{ trans('calendar::seat.title') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="title" placeholder="{{ trans('calendar::seat.placeholder_title') }}">
                        </div>
                    </div>
                    {{-- Operation importance --}}
                    <div class="form-group">
                        <label for="importance" class="col-sm-3 control-label">{{ trans('calendar::seat.importance') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="slider form-control" value="2" data-slider-min="0" data-slider-max="5"
                                   data-slider-step="0.5" data-slider-value="2" data-slider-id="sliderImportance"
                                   data-slider-tooltip="show" data-slider-handle="round" name="importance"/>
                        </div>
                    </div>
                    {{-- Operation tags --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('calendar::seat.tags') }}</label>
                        <div class="col-sm-9">
                            @foreach($tags->chunk(4) as $tags)
                                <div class="row">
                                    @foreach($tags as $tag)
                                        <div class="col-sm-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checkbox-{{$tag->id}}" value="{{$tag->id}}">
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
                    <div class="form-group">
                        <label for="known_duration" class="col-sm-3 control-label">{{ trans('calendar::seat.known_duration') }}</label>
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
                    <div class="form-group datepicker">
                        <label for="time_start" class="col-sm-3 control-label">{{ trans('calendar::seat.starts_at') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="time_start" />
                        </div>
                    </div>
                    {{-- Operation duration --}}
                    <div class="form-group datepicker hidden">
                        <label for="time_start_end" class="col-sm-3 control-label">{{ trans('calendar::seat.duration') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="time_start_end" id="time_start_end">
                        </div>
                    </div>
                    {{-- Operation staging system --}}
                    <div class="form-group">
                        <label for="staging_sys" class="col-sm-3 control-label">{{ trans('calendar::seat.staging_sys') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="staging_sys"
                                   placeholder="{{ trans('calendar::seat.placeholder_staging_sys') }}">
                            <input type="hidden" name="staging_sys_id">
                        </div>
                    </div>
                    {{-- Operation staging info --}}
                    <div class="form-group">
                        <label for="staging_info" class="col-sm-3 control-label">{{ trans('calendar::seat.staging_info') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="staging_info" id="staging_info"
                                   placeholder="{{ trans('calendar::seat.placeholder_staging_info') }}">
                        </div>
                    </div>
                    {{-- Operation FC --}}
                    <div class="form-group">
                        <label for="fc" class="col-sm-3 control-label">{{ trans('calendar::seat.fleet_commander') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="fc"
                                   placeholder="{{ trans('calendar::seat.placeholder_fc') }}">
                            <input type="hidden" name="fc_character_id">
                        </div>
                    </div>
                    {{-- Operation description --}}
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">{{ trans('calendar::seat.description') }}</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="description" rows="8"
                                      placeholder="{{ trans('calendar::seat.placeholder_description') }}"></textarea>
                        </div>
                    </div>
                    {{-- Operation slack --}}
                    @if(setting('kassie.calendar.slack_integration', true) == true)
                    <div class="form-group">
                        <label for="notify" class="col-sm-3 control-label">
                            <i class="fa fa-slack"></i>&nbsp;
                            {{ trans('calendar::seat.notification_enable') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="checkbox" name="notify" checked>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer bg-green">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    {{ trans('calendar::seat.close') }}
                </button>
                <button type="button" class="btn btn-outline" id="create_operation_submit">
                    {{ trans('calendar::seat.create_confirm_button_yes') }}
                </button>
            </div>
        </div>
    </div>
</div>
