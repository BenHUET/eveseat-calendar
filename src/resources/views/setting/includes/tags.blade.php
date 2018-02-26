<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tags"></i> {{ trans('calendar::seat.tags') }}</h3>
    </div>
    <div class="box-body">

        <ul class="nav nav-pills">
            <li role="presentation" class="active">
                <a href="#new_tag" aria-controls="new_tag" role="tab" data-toggle="tab">{{ trans('calendar::seat.new') }}</a>
            </li>
            <li role="presentation">
                <a href="#list_tag" aria-controls="list_tag" role="tab" data-toggle="tab">{{ trans('calendar::seat.list') }}</a>
            </li>
        </ul>

        <div class="tab-content">

            <div role="tabpanel" class="tab-pane active" id="new_tag">
                <form class="form-horizontal" method="POST" action="{{ route('setting.tag.create') }}">
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

                    <button type="submit" class="btn btn-info pull-right">{{ trans('calendar::seat.save') }}</button>

                </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="list_tag">
                <table class="table table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>{{ trans('calendar::seat.preview') }}</th>
                            <th>{{ trans('calendar::seat.analytic') }}</th>
                            <th>{{ trans('calendar::seat.order') }}</th>
                            <th>{{ trans('calendar::seat.quantifier') }}</th>
                            <th>{{ trans('calendar::seat.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags->sortBy('order') as $tag)
                        <tr class="tr-hoverable">
                            <td>
                                @include('calendar::common.includes.tag', ['tag' => $tag])
                            </td>
                            <td>
                                @if($tag->analytics === 'strategic')
                                {{ trans('calendar::seat.strategic') }}
                                @elseif($tag->analytics === 'pvp')
                                {{ trans('calendar::seat.pvp') }}
                                @elseif($tag->analytics === 'mining')
                                {{ trans('calendar::seat.mining') }}
                                @elseif($tag->analytics === 'other')
                                {{ trans('calendar::seat.other') }}
                                @elseif($tag->analytics === 'untracked')
                                {{ trans('calendar::seat.untracked') }}
                                @else
                                {{ $tag->analytics }}
                                @endif
                            </td>
                            <td>{{ $tag->order }}</td>
                            <td>{{ $tag->quantifier }}</td>
                            <td>
                                <span class="clickable pull-right">
                                    <button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-tag-id="{{ $tag->id }}" data-target="#modalEdit">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-tag-id="{{ $tag->id }}" data-target="#modalConfirmDelete">
                                        <i class="fa fa-trash text-danger"></i>
                                    </button>
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
