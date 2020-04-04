<div class="modal fade" tabindex="-1" role="dialog" id="modalSubscribe">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h4 class="modal-title">
                    <i class="fas fa-space-shuttle"></i>
                    {{ trans('calendar::seat.subscribe') }}
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="formSubscribe" method="POST" action="{{ route('operation.subscribe') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="operation_id">
                    <input type="hidden" name="status">

                    {{-- Character --}}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{ trans('calendar::seat.character') }}</label>
                        <div class="col-sm-10">
                            @foreach($characters->chunk(3) as $chunk)
                            <div class="row">
                                @foreach($chunk as $character)
                                <div class="radio col-md-4" style="margin-top:-5px">
                                    <label>
                                        @if($loop->parent->first && $loop->first)
                                            <input type="radio" name="character_id" value="{{ $character->character_id }}" checked="checked" />
                                        @else
                                            <input type="radio" name="character_id" value="{{ $character->character_id }}" />
                                        @endif
                                        {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                                        {{ $character->name }}
                                        @if($character->main)
                                            <span class="text-muted"><i>(main)</i></span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">{{ trans('calendar::seat.status') }}</label>
                        <div class="col-sm-10">
                            <select name="status" id="status" style="width: 100%;">
                                <option value="yes">{{ trans('calendar::seat.attending_yes') }}</option>
                                <option value="maybe">{{ trans('calendar::seat.attending_maybe') }}</option>
                                <option value="no">{{ trans('calendar::seat.attending_no') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Comment --}}
                    <div class="form-group row">
                        <label for="comment" class="col-sm-2 col-form-label">{{ trans('calendar::seat.comment') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="comment" name="comment"
                                   placeholder="{{ trans('calendar::seat.placeholder_comment') }}">
                        </div>
                    </div>
                </form>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> {{ trans('web::seat.no') }}
                    </button>
                    <button type="button" class="btn btn-success" id="subscribe_submit">
                        <i class="fas fa-check-circle"></i> {{ trans('web::seat.yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
