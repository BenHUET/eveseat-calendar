$("#tag_name, #tag_bg_color, #tag_text_color").change(function() {
	var name = $("#tag_name").val();
	var bg_color = $("#tag_bg_color").val();
	var text_color = $("#tag_text_color").val();
	var tag_preview = $("#tag_preview");

	var bg_color_hex  = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(bg_color);
	var text_color_hex  = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(text_color);

	if (bg_color_hex == true) {
		tag_preview.css('background-color', bg_color);
	}
	else {
		tag_preview.css('background-color', '#000');
	}

	if (text_color_hex == true) {
		tag_preview.css('color', text_color);
	}
	else {
		tag_preview.css('color', '#fff');
	}


	if (name.length !== 0) {
		tag_preview.html(name);
	}
	else {
		tag_preview.html('tag');
	}
});

$('#modalConfirmDelete').on('show.bs.modal', function(e) {
	var tag_id = $(e.relatedTarget).data('tag-id');

	$(e.currentTarget).find('input[name="tag_id"]').val(tag_id);
});