{!! img('character', $row->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
<a href="{{ route('character.view.sheet', ['character_id' => $row->character_id]) }}">
    @if (! is_null($row->character)){{ $row->character->name }}@else<span rel="id-to-name">{{ $row->character_id }}</span>@endif
</a>
@if (! is_null($row->user) && ! is_null($row->user->group) && $row->user->group->main_character && $row->user->group->main_character_id != $row->character_id)
<span class="text-muted pull-right">
    <i>({{ optional($row->user->group->main_character)->name }})</i>
</span>
@endif
