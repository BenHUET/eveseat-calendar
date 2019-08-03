@if(! is_null($row->corporation))
{!! img('corporation', $row->character->corporation_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
<span class="id-to-name" data-id="{{ $row->character->corporation_id }}">{{ trans('web::seat.unknown') }}</span>
@endif