@if($op->fc_character_id)
	<img src="http://image.eveonline.com/Character/{{ $op->fc_character_id }}_64.jpg" class="img-circle eve-icon small-icon" />
	&nbsp;
	<a href="{{ route('character.view.sheet', ['character_id' => $op->fc_character_id]) }}">{{ $op->fc }}</a>
@else
	{{ $op->fc }}
@endif