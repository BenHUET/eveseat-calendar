var formCreateFirst = false;
$('#modalCreateOperation').on('show.bs.modal', function(e) {
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
	$('#modalCreateOperation').find('#time_start').daterangepicker(options);
	options.singleDatePicker = false;
	options.endDate = nowRounded.clone().add('2', 'h');
	$('#modalCreateOperation').find('#time_start_end').daterangepicker(options);

	if (formCreateFirst == false)
		$('#modalCreateOperation').find('#sliderImportance').slider({
			formatter: function(value) {
				return value;
			}
		});

	formCreateFirst = true;
});

$('#modalCreateOperation').find('input[name=known_duration]:radio').change(function () {
	$('#modalCreateOperation').find('.datepicker').toggleClass("hidden");
});

$('#formCreateOperation').submit(function(e) {
	e.preventDefault();

	$('#modalCreateOperation').find('#create_operation_submit').prop('disabled', true);

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
			$('#modalCreateOperation').find('.form-group').removeClass('has-error');
			$('#modalCreateOperation').find('#modal-errors ul').empty();
			$('#modalCreateOperation').find('#modal-errors').removeClass('hidden');
			$.each(response['responseJSON'], function (index, value) {
				$('#modalCreateOperation').find('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
				$('#modalCreateOperation').find('#modal-errors ul').append('<li>' + value + '</li>');
			});
			$('#modalCreateOperation').find('#create_operation_submit').prop('disabled', false);
		}
	});
});

$('#modalUpdateOperation').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);

	var ROUNDING = 15 * 60 * 1000;
	nowRounded = moment.utc();
	nowRounded = moment.utc(Math.ceil((+nowRounded) / ROUNDING) * ROUNDING);

	var operation_id = $(e.relatedTarget).data('op-id');

	$.getJSON("/calendar/operation/find/" + operation_id, function(op) {
		$('#modalUpdateOperation').find('#title').val(op.title);
		$('#modalUpdateOperation').find('option[value="' + op.type + '"]').prop('selected', true);
		$('#modalUpdateOperation').find('#staging_sys').val(op.staging_sys);
		$('#modalUpdateOperation').find('#staging_sys_id').val(op.staging_sys_id);
		$('#modalUpdateOperation').find('#staging_info').val(op.staging_info);
		$('#modalUpdateOperation').find('#fc').val(op.fc);
		$('#modalUpdateOperation').find('#fc_character_id').val(op.fc_character_id);
		$('#modalUpdateOperation').find('#description').val(op.description);

		
		$.each(op.tags, function(i, tag) {
			$('#modalUpdateOperation').find('#checkbox-update-' + tag.id).prop('checked', true);
			console.log($('#modalUpdateOperation').find('#checkbox-update-' + tag.id));
        });

		var options = {
		timePicker: true,
		timePickerIncrement: 15,
		timePicker24Hour: true,
		minDate: nowRounded,
		startDate: moment.utc(op.start_at),
		locale: {
			"format": "MM/DD/YYYY HH:mm"
			}
		};

		options.singleDatePicker = true;
		$('#modalUpdateOperation').find('#time_start').daterangepicker(options);

		options.singleDatePicker = false;
		if (op.end_at) {
			options.endDate = moment.utc(op.end_at);
			$('#modalUpdateOperation').find('input[name=known_duration][value=yes]').prop('checked', true);
			$('#modalUpdateOperation').find('#time_start').closest('div.form-group').addClass('hidden');
		}
		else {
			options.endDate = moment.utc(op.start_at).clone().add('2', 'h');
			$('#modalUpdateOperation').find('input[name=known_duration][value=no]').prop('checked', true);
			$('#modalUpdateOperation').find('#time_start_end').closest('div.form-group').addClass('hidden');
		}
		$('#modalUpdateOperation').find('#time_start_end').daterangepicker(options);

		$('#modalUpdateOperation').find('#sliderImportance').slider('destroy');

		$('#modalUpdateOperation').find('#sliderImportance').attr('data-slider-value', op.importance);
		$('#modalUpdateOperation').find('#sliderImportance').slider({
			formatter: function(value) {
				return value;
			},
		});
	}).fail(function() {
		$(location).attr('href', 'operation');
	});
});

$('#modalUpdateOperation').find('input[name=known_duration]:radio').change(function () {
	$('#modalUpdateOperation').find('.datepicker').toggleClass("hidden");
});

$('#formUpdateOperation').submit(function(e) {
	e.preventDefault();

	$('#modalUpdateOperation').find('#update_operation_submit').prop('disabled', true);

	$.ajax({
		type: "POST",
		url: "operation/update",
		data: $(this).serializeArray(),
		headers: {
			'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
		},
		success: function (response) {
			$(location).attr('href', 'operation');
		},
		error: function (response) {
			$('#modalUpdateOperation').find('.form-group').removeClass('has-error');
			$('#modalUpdateOperation').find('#modal-errors ul').empty();
			$('#modalUpdateOperation').find('#modal-errors').removeClass('hidden');
			$.each(response['responseJSON'], function (index, value) {
				$('#modalUpdateOperation').find('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
				$('#modalUpdateOperation').find('#modal-errors ul').append('<li>' + value + '</li>');
			});
			$('#modalUpdateOperation').find('#update_operation_submit').prop('disabled', false);
		}
	});
});

$('#modalSubscribe').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');
	var character_id = $(e.relatedTarget).data('character-id');
	var status = $(e.relatedTarget).data('status');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);

	if (status && character_id) {
		$('#modalSubscribe').find("input[name=character_id][value=" + character_id + "]").prop('checked', true);
		$('#modalSubscribe').find('option[value="' + status + '"]').prop('selected', true);
		$("select#status").trigger('change');
	}
});

$("select#status").change(function() {
	$("#headerModalSubscribe").removeClass('modal-calendar-green modal-calendar-yellow modal-calendar-red');
	status = $(this).find(":selected").val();
	if (status == "yes")
		$("#headerModalSubscribe").addClass('modal-calendar-green');
	if (status == "maybe")
		$("#headerModalSubscribe").addClass('modal-calendar-yellow');
	if (status == "no")
		$("#headerModalSubscribe").addClass('modal-calendar-red');
});

$('#modalConfirmDelete, #modalConfirmClose, #modalConfirmCancel, #modalConfirmActivate').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);
});

$('input#fc').autocomplete({
    serviceUrl: 'lookup/characters/',
    onSelect: function (suggestion) {
        $('input#fc_character_id').val(suggestion.data);
        $('input#fc').css('border-color', 'green');
    },
    onInvalidateSelection: function () {
    	$('input#fc_character_id').val(null);
    	$('input#fc').css('border-color', '');
    },
    minChars: 3
});

$('input#staging_sys').autocomplete({
    serviceUrl: 'lookup/systems/',
    onSelect: function (suggestion) {
        $('input#staging_sys_id').val(suggestion.data);
        $('input#staging_sys').css('border-color', 'green');
    },
    onInvalidateSelection: function () {
		$('input#staging_sys_id').val(null);
		$('input#staging_sys').css('border-color', '');
    },
    minChars: 1
});

$('input#staging_sys').focusout(function() {
	if ($('input#staging_sys_id').val() == '')
		$('input#staging_sys').val(null);
});

$('.default-op').modal('show');