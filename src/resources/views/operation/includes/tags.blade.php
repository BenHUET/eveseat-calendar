@foreach ($op->tags->sortBy('name') as $tag)
	@include('calendar::common.includes.tag', ['tag' => $tag])
	@if((!isset($chunk) || $chunk != false) && $loop->iteration % 3 == 0)
		<br/>
	@endif
@endforeach