@if($op->staging_sys_id)
    @include('web::partials.system', ['system' => $op->staging->itemName, 'security' => $op->staging->security])
@endif

{{ $op->staging_info }}