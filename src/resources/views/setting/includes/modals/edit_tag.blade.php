<div class="modal fade" tabindex="-1" role="dialog" id="modalEdit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-yellow">
                <h4 class="modal-title">
                    <i class="fa fa-pencil"></i> {{ trans('calendar::seat.delete') }}
                </h4>
            </div>
            <form method="post" action="{{ route('setting.tag.update') }}" class="form-horizontal">
            <div class="modal-body">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">{{ trans('calendar::seat.name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="tag_name" placeholder="{{ trans('calendar::seat.name_tag_placeholder') }}" maxlength="7">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="order" class="col-sm-3 control-label">{{ trans('calendar::seat.order') }}</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="order" id="tag_order" placeholder="{{ trans('calendar::seat.order_placeholder') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="analytics" class="col-sm-3 control-label">{{ trans('calendar::seat.analytic') }}</label>
                        <div class="col-sm-9">
                            <select name="analytics" id="analytics" class="form-control">
                                <option value="strategic">{{ trans('calendar::seat.strategic') }}</option>
                                <option value="pvp">{{ trans('calendar::seat.pvp') }}</option>
                                <option value="mining">{{ trans('calendar::seat.mining') }}</option>
                                <option value="other">{{ trans('calendar::seat.other') }}</option>
                                <option value="untracked">{{ trans('calendar::seat.untracked') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantifier" class="col-sm-3 control-label">{{ trans('calendar::seat.quantifier') }}</label>
                        <div class="col-sm-9">
                            <input type="number" min="0" step="0.5" class="form-control" name="quantifier" id="quantifier" value="1" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bg_color" class="col-sm-3 control-label">{{ trans('calendar::seat.background') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bg_color" id="tag_bg_color" placeholder="{{ trans('calendar::seat.background_placeholder') }}" maxlength="7">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="text_color" class="col-sm-3 control-label">{{ trans('calendar::seat.text_color') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="text_color" id="tag_text_color" placeholder="{{ trans('calendar::seat.text_color_placeholder') }}" maxlength="7">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="preview" class="col-sm-3 control-label">{{ trans('calendar::seat.preview') }}</label>
                        <div class="col-sm-9">
                            <span class="badge tag" style="background-color: black" id="tag_preview">TAG</span>
                        </div>
                    </div>

                    <input type="hidden" name="tag_id" />

                <div class="overlay">
                    <i class="fa fa-spin fa-refresh"></i>
                </div>
            </div>

            <div class="modal-footer bg-yellow">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-outline" value="Update" />
            </div>
            </form>

        </div>
    </div>
</div>
