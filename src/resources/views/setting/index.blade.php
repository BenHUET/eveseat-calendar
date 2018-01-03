@extends('web::layouts.grids.4-4-4')

@section('title', trans('calendar::seat.plugin_name') . ' | ' . trans('calendar::seat.settings'))
@section('page_header', trans('calendar::seat.settings'))

@section('left')
    @include('calendar::setting.includes.slack')
@stop

@section('center')
    @include('calendar::setting.includes.tags')
@stop

@include('calendar::setting.includes.modals.confirm_delete_tag')

@include('calendar::setting.includes.modals.edit_tag')

@push('head')
    <link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
@endpush

@push('javascript')
    <script src="{{ asset('web/js/settings.js') }}"></script>
    <script type="text/javascript">
        $('#modalEdit').on('show.bs.modal', function(e){
            var link = '{{ route('tags.show', 0) }}';
            var modal = $(this);

            modal.find('.overlay').show();
            modal.find('input[type="text"]').each(function(index, input){
                $(input).val('');
            });

            $.ajax({
                url: link.replace('/0', '/' + $(e.relatedTarget).attr('data-tag-id')),
                dataType: 'json',
                method: 'GET'
            }).done(function(data){
                modal.find('input[name="name"]').val(data.name);
                modal.find('input[name="order"]').val(data.order);
                modal.find('select[name="analytics"]').val(data.analytics);
                modal.find('input[name="quantifier"]').val(data.quantifier);
                modal.find('input[name="bg_color"]').val(data.bg_color);
                modal.find('input[name="text_color"]').val(data.text_color);
                modal.find('input[name="tag_id"]').val(data.id);

                $('#tag_bg_color').change();
            });

            $(this).find('.overlay').hide();
        });
    </script>
@endpush
