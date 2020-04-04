<span>{{ $row->title }}</span>
<span class="float-right">
    @include('calendar::operation.partials.attendees', ['op' => $row])
</span>