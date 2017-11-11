@if ($op->importance == 5)
<span class="text-red">
@elseif ($op->importance >= 3)
<span class="text-yellow">
@else
<span class="text-green">
@endif
	<span data-toggle="tooltip" data-placement="right" title="{{ $op->importance }}/5">
		<?php echo Seat\Kassie\Calendar\Helpers\Helper::ImportanceAsEmoji($op->importance, '<i class="fa fa-star"></i>', '<i class="fa fa-star-half-o"></i>', '<i class="fa fa-star-o"></i>') ?>
	</span>
</span>