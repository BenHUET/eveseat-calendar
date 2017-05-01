$('[id^=modalDetails]').on('show.bs.modal', function(e) {
	operation_id = $(this).attr('id').split("-")[1];
	table = $(this).find('#attendees');

	if (! $.fn.DataTable.isDataTable(table)) {
		table.DataTable({
			"ajax": "/calendar/lookup/attendees?id=" + operation_id,

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

$('[id^=modalDetails]').on('hidden.bs.modal', function(e) {
	var table = $(this).find('#attendees').DataTable();
	table.destroy();
});