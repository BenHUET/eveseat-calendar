@extends('web::layouts.grids.12')

@section('title', trans('calendar::seat.plugin_name') . ' | ' . trans('calendar::seat.operations'))
@section('page_header', trans('calendar::seat.all_operations'))

@section('full')

	@if(auth()->user()->has('calendar.create', false))
	<div class="row">
		<div class="col-md-offset-8 col-md-4">
			<div class="panel-body">
				<div class="pull-right">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateOperation">
						<i class="fa fa-plus"></i>&nbsp;&nbsp;
						{{ trans('calendar::seat.add_operation') }}
					</button>
				</div>
			</div>
		</div>
	</div>
	@endif

	@include('calendar::operation.includes.modals.create_operation')
	@include('calendar::operation.includes.modals.update_operation')
	@include('calendar::operation.includes.modals.confirm_delete')
	@include('calendar::operation.includes.modals.confirm_close')
	@include('calendar::operation.includes.modals.confirm_cancel')
	@include('calendar::operation.includes.modals.confirm_activate')
	@include('calendar::operation.includes.modals.subscribe')
	@include('calendar::operation.includes.modals.details')

	<div class="row">
		<div class="col-md-12">
			@include('calendar::operation.includes.ongoing')
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@include('calendar::operation.includes.incoming')
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@include('calendar::operation.includes.faded')
		</div>
	</div>

@stop

@push('head')
	<link rel="stylesheet" href="{{ asset('web/css/daterangepicker.css') }}" />
	<link rel="stylesheet" href="{{ asset('web/css/bootstrap-slider.min.css') }}" />

	<link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
@endpush

@push('javascript')
	<script src="{{ asset('web/js/daterangepicker.js') }}"></script>
	<script src="{{ asset('web/js/bootstrap-slider.min.js') }}"></script>
	<script src="{{ asset('web/js/jquery.autocomplete.min.js') }}"></script>
	<script src="{{ asset('web/js/natural.js') }}"></script>
	
	<script src="{{ asset('web/js/calendar.js') }}"></script>
	<script src="{{ asset('web/js/details.js') }}"></script>
	<script type="text/javascript">
        $('#modalDetails')
			.on('show.bs.modal', function(e){
				var link = '{{ route('operation.detail', 0) }}';
				// load detail content dynamically
				$(this).find('.modal-body')
					.load(link.replace('/0', '/' + $(e.relatedTarget).attr('data-op-id')), "", function(){
						// attach the datatable to the loaded modal
					    var table = $('#attendees');
						if (! $.fn.DataTable.isDataTable(table)) {
							table.DataTable({
								"ajax": "/calendar/lookup/attendees?id=" + $(e.relatedTarget).attr('data-op-id'),
								"ordering": true,
								"info": false,
								"paging": true,
								"processing": true,
								"order": [[ 1, "asc" ]],

								"aoColumnDefs": [
									{ orderable: false, targets: "no-sort" }
								],
								"columns": [
									{ data: '_character' },
									{ data: '_status' },
									{ data: '_comment' },
									{ data: '_timestamps' }
								],
								createdRow: function(row, data, dataIndex) {
									$(row).find('td:eq(0)').attr('data-order', data._character_name);
									$(row).find('td:eq(0)').attr('data-search', data._character_name);
								}
							});
						}
					});
			})
			.on('hidden.bs.modal', function(e) {
				var table = $(this).find('#attendees').DataTable();
				table.destroy();
			});

        // direct link
        $(function(){
            if ($('tr[data-attr-default=true]').length > 0)
                $('tr[data-attr-default]').find('.hidden-xs').find('.fa-eye').click();
		});
	</script>
@endpush