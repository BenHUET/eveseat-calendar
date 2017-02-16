// Datepickers
var ROUNDING = 15 * 60 * 1000;
nowRounded = moment.utc();
nowRounded = moment.utc(Math.ceil((+nowRounded) / ROUNDING) * ROUNDING);

var options = {
	timePicker: true,
	timePickerIncrement: 15,
	timePicker24Hour: true,
	minDate: moment.utc(),
	startDate: nowRounded,
	locale: {
		"format": "MM/DD/YYYY HH:mm"
	}
};

options.singleDatePicker = true;
$('#time_start').daterangepicker(options);
options.singleDatePicker = false;
$('#time_start_end').daterangepicker(options);

$("input[name=known_duration]:radio").change(function () {
	$('.datepicker').toggleClass("hidden");
});

var slider = $('#sliderImportance').slider({
	formatter: function(value) {
		return value;
	}
});

// AJAX Add Operation form
$('#formCreateOperation').submit(function(e) {
	e.preventDefault();

	$.ajax({
		type: "POST",
		url: "operation",
		data: $(this).serializeArray(),
		headers: {
			'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
		},
		success: function (response) {
			$(location).attr('href', 'operation');
		},
		error: function (response) {
			$('.form-group').removeClass('has-error');
			$("#modal-errors ul").empty();
			$("#modal-errors").removeClass('hidden');
			$.each(response['responseJSON'], function (index, value) {
				$('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
				$("#modal-errors ul").append('<li>' + value + '</li>');
			});
		}
	});
});

$('#modalSubscribe').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');
	var status = $(e.relatedTarget).data('status');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);
	$(e.currentTarget).find('input[name="status"]').val(status);


	$('.modal-attending').addClass('hidden');
	$('.attending-' + status).removeClass('hidden');
});

$('#modalConfirmDelete, #modalConfirmClose, #modalConfirmCancel, #modalConfirmActivate').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);
});

$('input#fc').autocomplete({
    serviceUrl: 'lookup/characters/',
    onSelect: function (suggestion) {
        $('input#fc_character_id').val(suggestion.data);
    },
    onInvalidateSelection: function () {
    	$('input#fc_character_id').val(null);
    },
    minChars: 3
});