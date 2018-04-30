@if($op->fc_character_id)
    {!! img('character', $op->fc_character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    <a href="{{ route('character.view.sheet', ['character_id' => $op->fc_character_id]) }}">{{ $op->fc }}</a>
    @if(in_array($op->fc_character_id, auth()->user()->associatedCharacterIds()->toArray()) && in_array($op->status, ['ongoing', 'faded']))
    <a href="{{ route('operation.paps', [$op->id]) }}" class="btn btn-xs btn-default">Pap Fleet !</a>
    @endif
@else
    {{ $op->fc }}
@endif
