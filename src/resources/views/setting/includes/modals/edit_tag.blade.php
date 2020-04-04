<div class="modal fade" tabindex="-1" role="dialog" id="modalEdit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-yellow">
        <h4 class="modal-title">
          <i class="fas fa-pencil-alt"></i> {{ trans('calendar::seat.delete') }}
        </h4>
      </div>

      <div class="modal-body">
        <div class="overlay dark d-flex justify-content-center align-items-center">
          <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <form method="post" action="{{ route('setting.tag.update') }}" class="form-horizontal" id="form-edit-tag">
          {{ csrf_field() }}

          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">{{ trans('calendar::seat.name') }}</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="name" id="edit_tag_name" placeholder="{{ trans('calendar::seat.name_tag_placeholder') }}" maxlength="7">
            </div>
          </div>

          <div class="form-group row">
            <label for="order" class="col-sm-3 col-form-label">{{ trans('calendar::seat.order') }}</label>
            <div class="col-sm-9">
              <input type="number" class="form-control" name="order" id="tag_order" placeholder="{{ trans('calendar::seat.order_placeholder') }}">
            </div>
          </div>

          <div class="form-group row">
            <label for="analytics" class="col-sm-3 col-form-label">{{ trans('calendar::seat.analytic') }}</label>
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

          <div class="form-group row">
            <label for="quantifier" class="col-sm-3 col-form-label">{{ trans('calendar::seat.quantifier') }}</label>
            <div class="col-sm-9">
              <input type="number" min="0" step="0.5" class="form-control" name="quantifier" id="quantifier" value="1" />
            </div>
          </div>

          <div class="form-group row">
            <label for="bg_color" class="col-sm-3 col-form-label">{{ trans('calendar::seat.background') }}</label>
            <div class="col-md-9">
              <div class="input-group colorpicker-component" id="edit_tag_bg_color">
                <input type="text" name="bg_color" value="#000" class="form-control"
                       maxlength="7" placeholder="{{ trans('calendar::seat.background_placeholder') }}" />
                <span class="input-group-addon"><i></i></span>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="text_color" class="col-sm-3 col-form-label">{{ trans('calendar::seat.text_color') }}</label>
            <div class="col-sm-9">
              <div class="input-group colorpicker-component" id="edit_tag_text_color">
                <input type="text" name="text_color" value="#fff" class="form-control"
                       maxlength="7" placeholder="{{ trans('calendar::seat.text_color_placeholder') }}" />
                <span class="input-group-addon"><i></i></span>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="preview" class="col-sm-3">{{ trans('calendar::seat.preview') }}</label>
            <div class="col-sm-9">
              <span class="badge tag" id="edit_tag_preview">TAG</span>
            </div>
          </div>

          <input type="hidden" name="tag_id" />
        </form>
      </div>

      <div class="modal-footer justify-content-between bg-yellow">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <input type="submit" form="form-edit-tag" class="btn btn-outline-dark" value="Update" />
      </div>
    </div>
  </div>
</div>
