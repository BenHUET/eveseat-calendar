@if($op->fc_character_id)
	{!! img('character', $op->fc_character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
	&nbsp;
	<a href="{{ route('character.view.sheet', ['character_id' => $op->fc_character_id]) }}">{{ $op->fc }}</a>
@else
	{{ $op->fc }}
@endif
