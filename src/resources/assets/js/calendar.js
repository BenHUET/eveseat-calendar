var op_modals = {
    create: $('#modalCreateOperation'),
    update: $('#modalUpdateOperation'),
    subscribe: $('#modalSubscribe')
};

op_modals.create.on('show.bs.modal', function (e) {
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
        },
        parentEl: '#modalCreateOperation'
    };

    options.singleDatePicker = true;
    op_modals.create.find('input[name="time_start"]').daterangepicker(options);
    options.singleDatePicker = false;
    options.endDate = nowRounded.clone().add('2', 'h');
    op_modals.create.find('input[name="time_start_end"]').daterangepicker(options);

    if ($('#sliderImportance').length <= 0)
        $('#modalCreateOperation').find('input[name="importance"]').slider({
            formatter: function (value) {
                return value;
            }
        });
});

op_modals.create.find('input[name="known_duration"]')
    .on('change', function () {
        console.debug('trigger');
        op_modals.create.find('.datepicker').toggleClass("d-none");
    });

$('#create_operation_submit').click(function () {
    $('#formCreateOperation').submit();
});

$('#confirm_cancel_submit').click(function () {
    $('#formCancel').submit();
});

$('#confirm_activate_submit').click(function () {
    $('#formActivate').submit();
});

$('#confirm_delete_submit').click(function () {
    $('#formDelete').submit();
});

$('#confirm_close_submit').click(function () {
    $('#formClose').submit();
});

$('#formCreateOperation').submit(function (e) {
    e.preventDefault();

    $('#create_operation_submit').prop('disabled', true);

    $.ajax({
        type: "POST",
        url: seat_calendar.url.create_operation,
        data: $(this).serializeArray(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $(location).attr('href', 'operation');
        },
        error: function (response) {
            op_modals.create.find('.form-group').removeClass('has-error');
            op_modals.create.find('.modal-errors ul').empty();
            op_modals.create.find('.modal-errors').removeClass('hidden');
            $.each(response['responseJSON'], function (index, value) {
                op_modals.create.find('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
                op_modals.create.find('.modal-errors ul').append('<li>' + value + '</li>');
            });
            $('#create_operation_submit').prop('disabled', false);
        }
    });
});

op_modals.update.on('show.bs.modal', function (e) {
    var ROUNDING = 15 * 60 * 1000;
    var operation_id = $(e.relatedTarget).data('op-id');

    op_modals.update.find('.overlay').removeClass('d-none').addClass('d-flex');

    $(e.currentTarget).find('input[name="operation_id"]').val(operation_id);

    nowRounded = moment.utc();
    nowRounded = moment.utc(Math.ceil((+nowRounded) / ROUNDING) * ROUNDING);

    op_modals.update.find('input[name="known_duration"][value="yes"]').prop('checked', false);
    op_modals.update.find('input[name="known_duration"][value="no"]').prop('checked', true);
    op_modals.update.find('input[name="time_start"]').closest('div.form-group').removeClass('d-none');
    op_modals.update.find('input[name="time_start_end"]').closest('div.form-group').addClass('d-none');

    $.getJSON("/calendar/operation/find/" + operation_id, function (op) {
        op_modals.update.find('input[name="title"]').val(op.title);

        if (op.role_name !== null) {
            $('#update_operation_role').val(op.role_name);
            $('#update_operation_role').trigger('change');
        }

        if (op.integration_id !== null) {
            $('#update-operation-channel').val(op.integration_id);
            $('#update-operation-channel').trigger('change');
        }

        op_modals.update.find('option[value="' + op.type + '"]').prop('selected', true);
        op_modals.update.find('input[name="staging_sys"]').val(op.staging_sys);
        op_modals.update.find('input[name="staging_sys_id"]').val(op.staging_sys_id);
        op_modals.update.find('input[name="staging_info"]').val(op.staging_info);
        op_modals.update.find('input[name="fc"]').val(op.fc);
        op_modals.update.find('input[name="fc_character_id"]').val(op.fc_character_id);
        op_modals.update.find('textarea[name="description"]').val(op.description);

        $.each(op.tags, function (i, tag) {
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
            },
            parentEl: '#modalUpdateOperation'
        };

        options.singleDatePicker = true;
        op_modals.update.find('input[name="time_start"]').daterangepicker(options);
        options.singleDatePicker = false;

        if (op.end_at) {
            options.endDate = moment.utc(op.end_at);
            op_modals.update.find('input[name="known_duration"][value="yes"]').prop('checked', true).trigger('change');
            op_modals.update.find('input[name="time_start"]').closest('div.form-group').addClass('hidden');
            op_modals.update.find('input[name="time_start_end"]').closest('div.form-group').removeClass('hidden');
        } else {
            options.endDate = moment.utc(op.start_at).clone().add('2', 'h');
            op_modals.update.find('input[name="time_start"]').closest('div.form-group').removeClass('hidden');
            op_modals.update.find('input[name="time_start_end"]').closest('div.form-group').addClass('hidden');
        }

        op_modals.update.find('input[name="time_start_end"]').daterangepicker(options);

        if ($('#updateSliderImportance').length > 0) {
            $('#modalUpdateOperation').find('input[name="importance"]').slider('destroy');
        }

        op_modals.update.find('input[name="importance"]').attr('data-slider-value', op.importance);
        op_modals.update.find('input[name="importance"]').slider({
            formatter: function (value) {
                return value;
            }
        });
    }).fail(function () {
        $(location).attr('href', 'operation');
    }).done(function () {
        op_modals.update.find('.overlay').addClass('d-none').removeClass('d-flex');
    });
});

op_modals.update.find('input[name="known_duration"]')
    .on('change', function () {
        op_modals.update.find('.datepicker').toggleClass("d-none");
    });

$('#update_operation_submit').click(function () {
    $('#formUpdateOperation').submit();
});

$('#formUpdateOperation').submit(function (e) {
    e.preventDefault();

    $('#update_operation_submit').prop('disabled', true);

    $.ajax({
        type: "POST",
        url: seat_calendar.url.update_operation,
        data: $(this).serializeArray(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $(location).attr('href', 'operation');
        },
        error: function (response) {
            op_modals.update.find('.form-group').removeClass('has-error');
            op_modals.update.find('.modal-errors ul').empty();
            op_modals.update.find('.modal-errors').removeClass('hidden');
            $.each(response['responseJSON'], function (index, value) {
                op_modals.update.find('[name="' + index + '"]').closest('div.form-group').addClass('has-error');
                op_modals.update.find('.modal-errors ul').append('<li>' + value + '</li>');
            });
            $('#update_operation_submit').prop('disabled', false);
        }
    });
});

op_modals.subscribe
    .on('show.bs.modal', function (e) {
        var operation_id = $(e.relatedTarget).data('op-id');
        var character_id = $(e.relatedTarget).data('character-id');
        var status = $(e.relatedTarget).data('status');

        $(e.currentTarget).find('input[name="operation_id"]').val(operation_id);

        $('#status').select2();

        if (status && character_id) {
            op_modals.subscribe.find("input[name=character_id][value=" + character_id + "]").prop('checked', true);
            op_modals.subscribe.find('option[value="' + status + '"]').prop('selected', true);
            $("#status").trigger('change');
        }
    });

$('#subscribe_submit').click(function () {
    $('#formSubscribe').submit();
});

$("#status").change(function () {
    $("#headerModalSubscribe").removeClass('modal-calendar-green modal-calendar-yellow modal-calendar-red');

    switch ($(this).find(":selected").val()) {
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

$('#modalConfirmDelete, #modalConfirmClose, #modalConfirmCancel, #modalConfirmActivate').on('show.bs.modal', function (e) {
    var operation_id = $(e.relatedTarget).data('op-id');

    $(e.currentTarget).find('input[name="operation_id"]').val(operation_id);
});

$('input[name=fc]').autocomplete({
    serviceUrl: seat_calendar.url.characters_lookup,
    onSelect: function (suggestion) {
        $('input[name="fc_character_id"]').val(suggestion.data);
        $('input[name="fc"]').css('border-color', 'green');
    },
    onInvalidateSelection: function () {
        $('input[name="fc_character_id"]').val(null);
        $('input[name="fc"]').css('border-color', '');
    },
    minChars: 3
});

$('input[name=staging_sys]').autocomplete({
    serviceUrl: seat_calendar.url.systems_lookup,
    onSelect: function (suggestion) {
        $('input[name="staging_sys_id"]').val(suggestion.data);
        $('input[name="staging_sys"]').css('border-color', 'green');
    },
    onInvalidateSelection: function () {
        $('input[name="staging_sys_id"]').val(null);
        $('input[name="staging_sys"]').css('border-color', '');
    },
    minChars: 1
});

$('input[name="staging_sys"]').focusout(function () {
    if ($('input[name="staging_sys_id"]').val() == '')
        $('input[name="staging_sys"]').val(null);
});