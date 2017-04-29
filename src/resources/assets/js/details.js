$('[id^=modalDetails]').on('show.bs.modal', function(e) {
	operation_id = $(this).attr('id').split("-")[1];
	table = $(this).find('#attendees');

	if (! $.fn.DataTable.isDataTable(table)) {
		table.DataTable({
			"ordering": true,
			"info": false,
			"paging": true,
			columnDefs: [{
				orderable: false,
				targets: "no-sort"
			}],
			"processing": true,
			"serverSide": true,
			"ajax": "/calendar/lookup/attendees?id=" + operation_id,
			"columns": [
				{ data: getCharacterField, sort: 'character.characterName' },
				{ data: getStatusField },
				{ data: 'comment' },
				{ data: getAnswerField }
			]
		});
	}
});

function getCharacterField(data, type, dataToSet) {
	var field = '<img src="http://image.eveonline.com/Character/' + data.character.characterID + '_64.jpg" class="img-circle eve-icon small-icon" />&nbsp;';
	field += '<a href="' + data.character_sheet_url + '">' + data.character.characterName + '</a>';

	if (data.main_character != null && data.character.characterID != data.main_character.characterID) {
		field += '<span class="text-muted pull-right"><i>(' + data.main_character.characterName + ')</i></span>'
	}

	return field;
}

function getStatusField(data, type, dataToSet) {
	if (data.status == "yes")
		field = '<span class="label label-success">' + data.localized_status + '</span>'
	if (data.status == "maybe")
		field = '<span class="label label-warning">' + data.localized_status + '</span>'
	if (data.status == "no")
		field = '<span class="label label-danger">' + data.localized_status + '</span>'

	return field;
}

function getAnswerField(data, type, dataToSet) {
	field = '<small class="text-nowrap">' + data.created_at + '</small>';
	if (data.created_at != data.updated_at)
		field += '<br/><small class="text-nowrap">Updated at : ' + data.updated_at + '</small>';

	return field;
}