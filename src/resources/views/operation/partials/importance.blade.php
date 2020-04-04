@if ($op->importance == 5)
    <span class="text-red">
        <span data-toggle="tooltip" data-placement="right" title="{{ $op->importance }}/5">
            {!! Seat\Kassie\Calendar\Helpers\Helper::ImportanceAsEmoji($op->importance, '<i class="fas fa-star"></i>', '<i class="fas fa-star-half-alt"></i>', '<i class="far fa-star"></i>') !!}
        </span>
    </span>
@elseif ($op->importance >= 3)
    <span class="text-yellow">
        <span data-toggle="tooltip" data-placement="right" title="{{ $op->importance }}/5">
            {!! Seat\Kassie\Calendar\Helpers\Helper::ImportanceAsEmoji($op->importance, '<i class="fas fa-star"></i>', '<i class="fas fa-star-half-alt"></i>', '<i class="far fa-star"></i>') !!}
        </span>
    </span>
@else
    <span class="text-green">
        <span data-toggle="tooltip" data-placement="right" title="{{ $op->importance }}/5">
            {!! Seat\Kassie\Calendar\Helpers\Helper::ImportanceAsEmoji($op->importance, '<i class="fas fa-star"></i>', '<i class="fas fa-star-half-alt"></i>', '<i class="far fa-star"></i>') !!}
        </span>
    </span>
@endif
