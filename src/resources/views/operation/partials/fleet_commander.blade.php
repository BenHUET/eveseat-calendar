@if($op->fc_character_id)
    @include('web::partials.character', ['character' => $op->fleet_commander])
    @if($op->is_fleet_commander && in_array($op->status, ['ongoing', 'faded']))
    <a href="{{ route('operation.paps', [$op->id]) }}" class="btn btn-xs btn-default">Pap Fleet !</a>
    @endif
@else
    {{ $op->fc }}
@endif
