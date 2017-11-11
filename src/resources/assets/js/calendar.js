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
	$('#modalCreateOperation').find('input[name=time_start]').daterangepicker(options);
	options.singleDatePicker = false;
	options.endDate = nowRounded.clone().add('2', 'h');
	$('#modalCreateOperation').find('input[name=time_start_end]').daterangepicker(options);

	if ($('#sliderImportance').length <= 0)
		$('#modalCreateOperation').find('input[name=importance]').slider({
			formatter: function(value) {
				return value;
			}
		});
});

$('#modalCreateOperation').find('input[name=known_duration]:radio').change(function () {
	$('#modalCreateOperation').find('.datepicker').toggleClass("hidden");
});

$('#create_operation_submit').click(function(){
    $('#formCreateOperation').submit();
});

$('#confirm_cancel_submit').click(function(){
	$('#formCancel').submit();
});

$('#confirm_activate_submit').click(function(){
	$('#formActivate').submit();
});

$('#confirm_delete_submit').click(function(){
	$('#formDelete').submit();
});

$('#confirm_close_submit').click(function(){
	$('#formClose').submit();
});

$('#formCreateOperation').submit(function(e) {
	e.preventDefault();

	$('#create_operation_submit').prop('disabled', true);

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
			$('#modalCreateOperation').find('.modal-errors ul').empty();
			$('#modalCreateOperation').find('.modal-errors').removeClass('hidden');
			$.each(response['responseJSON'], function (index, value) {
				$('#modalCreateOperation').find('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
				$('#modalCreateOperation').find('.modal-errors ul').append('<li>' + value + '</li>');
			});
			$('#create_operation_submit').prop('disabled', false);
		}
	});
});

$('#modalUpdateOperation').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);

	var ROUNDING = 15 * 60 * 1000;
	nowRounded = moment.utc();
	nowRounded = moment.utc(Math.ceil((+nowRounded) / ROUNDING) * ROUNDING);

	$.getJSON("/calendar/operation/find/" + operation_id, function(op) {
		$('#modalUpdateOperation').find('input[name=title]').val(op.title);
		$('#modalUpdateOperation').find('option[value="' + op.type + '"]').prop('selected', true);
		$('#modalUpdateOperation').find('input[name=staging_sys]').val(op.staging_sys);
		$('#modalUpdateOperation').find('input[name=staging_sys_id]').val(op.staging_sys_id);
		$('#modalUpdateOperation').find('input[name=staging_info]').val(op.staging_info);
		$('#modalUpdateOperation').find('input[name=fc]').val(op.fc);
		$('#modalUpdateOperation').find('input[name=fc_character_id]').val(op.fc_character_id);
		$('#modalUpdateOperation').find('input[name=description]').val(op.description);
		
		$.each(op.tags, function(i, tag) {
			$('#checkbox-update-' + tag.id).prop('checked', true);
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
		$('#modalUpdateOperation').find('input[name=time_start]').daterangepicker(options);

		options.singleDatePicker = false;
		if (op.end_at) {
			options.endDate = moment.utc(op.end_at);
			$('#modalUpdateOperation').find('input[name=known_duration][value=yes]').prop('checked', true);
			$('#modalUpdateOperation').find('input[name=time_start]').closest('div.form-group').addClass('hidden');
            $('#modalUpdateOperation').find('input[name=time_start_end]').closest('div.form-group').removeClass('hidden');
		} else {
			options.endDate = moment.utc(op.start_at).clone().add('2', 'h');
			$('#modalUpdateOperation').find('input[name=known_duration][value=no]').prop('checked', true);
			$('#modalUpdateOperation').find('input[name=time_start]').closest('div.form-group').removeClass('hidden');
			$('#modalUpdateOperation').find('input[name=time_start_end]').closest('div.form-group').addClass('hidden');
		}
		$('#modalUpdateOperation').find('input[name=time_start_end]').daterangepicker(options);

		if ($('#updateSliderImportance').length > 0) {
            $('#modalUpdateOperation').find('input[name=importance]').slider('destroy');
        }

		$('#modalUpdateOperation').find('input[name=importance]').attr('data-slider-value', op.importance);
		$('#modalUpdateOperation').find('input[name=importance]').slider({
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

$('#update_operation_submit').click(function(){
	$('#formUpdateOperation').submit();
});

$('#formUpdateOperation').submit(function(e) {
	e.preventDefault();

	$('#update_operation_submit').prop('disabled', true);

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
			$('#modalUpdateOperation').find('.modal-errors ul').empty();
			$('#modalUpdateOperation').find('.modal-errors').removeClass('hidden');
			$.each(response['responseJSON'], function (index, value) {
				$('#modalUpdateOperation').find('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
				$('#modalUpdateOperation').find('.modal-errors ul').append('<li>' + value + '</li>');
			});
			$('#update_operation_submit').prop('disabled', false);
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
		$("#status").trigger('change');
	}
});

$('#subscribe_submit').click(function(){
	$('#formSubscribe').submit();
});

$("#status").change(function() {
	$("#headerModalSubscribe").removeClass('modal-calendar-green modal-calendar-yellow modal-calendar-red');

	switch ($(this).find(":selected").val())
	{
		case "yes":
            $("#headerModalSubscribe").addClass('modal-calendar-green');
			break;
		case "maybe":
            $("#headerModalSubscribe").addClass('modal-calendar-yellow');
			break;
		case "no":
            $("#headerModalSubscribe").addClass('modal-calendar-red');
			break;
	}
});

$('#modalConfirmDelete, #modalConfirmClose, #modalConfirmCancel, #modalConfirmActivate').on('show.bs.modal', function(e) {
	var operation_id = $(e.relatedTarget).data('op-id');

	$(e.currentTarget).find('input[name="operation_id"]').val(operation_id);
});

$('input[name=fc]').autocomplete({
    serviceUrl: 'lookup/characters/',
    onSelect: function (suggestion) {
        $('input[name=fc_character_id]').val(suggestion.data);
        $('input[name=fc]').css('border-color', 'green');
    },
    onInvalidateSelection: function () {
    	$('input[name=fc_character_id]').val(null);
    	$('input[name=fc]').css('border-color', '');
    },
    minChars: 3
});

$('input[name=staging_sys]').autocomplete({
    serviceUrl: 'lookup/systems/',
    onSelect: function (suggestion) {
        $('input[name=staging_sys_id]').val(suggestion.data);
        $('input[name=staging_sys]').css('border-color', 'green');
    },
    onInvalidateSelection: function () {
		$('input[name=staging_sys_id]').val(null);
		$('input[name=staging_sys]').css('border-color', '');
    },
    minChars: 1
});

$('input[name=staging_sys]').focusout(function() {
	if ($('input[name=staging_sys_id]').val() == '')
		$('input[name=staging_sys]').val(null);
});