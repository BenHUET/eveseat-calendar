<?php 
	$tmp = explode('.', $op->importance);
	$val = $tmp[0];
	$dec = 0;
	if (count($tmp) > 1)
		$dec = $tmp[1];

	$output = "";

	for ($i = 0; $i < $val; $i++) {
		$output .= '<i class="fa fa-star"></i>';
	}

	$left = 5;
	if ($dec != 0) {
		$output .= '<i class="fa fa-star-half-o"></i>';
		$left--;
	}

	for ($i = $val; $i < $left; $i++) {
		$output .= '<i class="fa fa-star-o"></i>';
	}

	$html = $output;
?>

<span data-toggle="tooltip" data-placement="right" title="{{ $op->importance }}/5">
<?php echo Seat\Kassie\Calendar\Helpers\Helper::ImportanceAsEmoji($op->importance, '<i class="fa fa-star"></i>', '<i class="fa fa-star-half-o"></i>', '<i class="fa fa-star-o"></i>') ?>
</span>