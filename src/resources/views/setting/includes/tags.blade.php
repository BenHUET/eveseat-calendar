<div class="card card-info">
    <div class="card-header d-flex p-0 with-border">
        <h3 class="card-title p-3"><i class="fas fa-tags"></i> {{ trans('calendar::seat.tags') }}</h3>

        <ul class="nav nav-pills ml-auto p-2">
            <li role="presentation" class="nav-item">
                <a href="#new_tag" class="nav-link active" aria-controls="new_tag" data-toggle="pill">{{ trans('calendar::seat.new') }}</a>
            </li>
            <li role="presentation" class="nav-item">
                <a href="#list_tag" class="nav-link" aria-controls="list_tag" data-toggle="pill">{{ trans('calendar::seat.list') }}</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane active" id="new_tag">
                <form class="form-horizontal" method="POST" action="{{ route('setting.tag.create') }}" id="form-new-tag">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">{{ trans('calendar::seat.name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="tag_name" placeholder="{{ trans('calendar::seat.name_tag_placeholder') }}" maxlength="7">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="order" class="col-sm-3 col-form-label">{{ trans('calendar::seat.order') }}</label>
                        <div class="col-sm-9">
                            <input type="number" min="0" step="1" class="form-control" name="order" id="tag_order" placeholder="{{ trans('calendar::seat.order_placeholder') }}">
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
                            <div class="input-group colorpicker-component" id="tag_bg_color">
                                <input type="text" name="bg_color" value="#000" class="form-control"
                                       maxlength="7" placeholder="{{ trans('calendar::seat.background_placeholder') }}" />
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text_color" class="col-sm-3 col-form-label">{{ trans('calendar::seat.text_color') }}</label>
                        <div class="col-sm-9">
                            <div class="input-group colorpicker-component" id="tag_text_color">
                                <input type="text" name="text_color" value="#fff" class="form-control"
                                       maxlength="7" placeholder="{{ trans('calendar::seat.text_color_placeholder') }}" />
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="preview" class="col-sm-3">{{ trans('calendar::seat.preview') }}</label>
                        <div class="col-sm-9">
                            <span class="badge tag" id="tag_preview">TAG</span>
                        </div>
                    </div>

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
                                <span class="clickable text-right">
                                    <button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-tag-id="{{ $tag->id }}" data-target="#modalEdit">
                                        <i class="fas fa-pencil-alt text-primary"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-tag-id="{{ $tag->id }}" data-target="#modalConfirmDelete">
                                        <i class="fas fa-trash-alt text-danger"></i>
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
    <div class="card-footer">
        <button type="submit" class="btn btn-info float-right" form="form-new-tag">{{ trans('calendar::seat.save') }}</button>
    </div>
</div>

@push('head')
    <style type="text/css">
        .card-header .nav-pills .nav-link.active, .card-header .nav-pills .show > .nav-link {
            color: #1f2d3d !important;
            background-color: #f8f9fa !important;
        }

        .card-header .nav-pills .nav-link:not(.active):hover {
            color: #1f2d3d !important;
        }
    </style>
@endpush