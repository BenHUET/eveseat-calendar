{!! img('character', $row->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
<a href="{{ route('character.view.sheet', ['character_id' => $row->character_id]) }}">
    {{ $row->character->name }}
</a>
@if ($row->main_character && $row->main_character->character_id != $row->character_id)
    <span class="text-muted pull-right"><i>({{ $row->main_character->name }})</i></span>
@endif
