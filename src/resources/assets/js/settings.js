$(function(){
    function refreshPreview(background, foreground, text, preview) {
        var name = $(text).val();
        var bg_color = $(background).val();
        var text_color = $(foreground).val();
        var tag_preview = $(preview);

        var bg_color_hex  = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(bg_color);
        var text_color_hex  = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(text_color);

        if (bg_color_hex === true) {
            tag_preview.css('background-color', bg_color);
        }
        else {
            tag_preview.css('background-color', '#000');
        }

        if (text_color_hex === true) {
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
    }

    $("#tag_name, #tag_bg_color, #tag_text_color").on('change', function() {
        refreshPreview('#tag_bg_color input', '#tag_text_color input', '#tag_name', '#tag_preview');
    });

    $("#edit_tag_name, #edit_tag_bg_color, #edit_tag_text_color").on('change', function() {
        refreshPreview('#edit_tag_bg_color input', '#edit_tag_text_color input', '#edit_tag_name', '#edit_tag_preview');
    });

    $('#tag_bg_color, #tag_text_color').colorpicker();

    $('#modalConfirmDelete').on('show.bs.modal', function(e) {
        var tag_id = $(e.relatedTarget).data('tag-id');

        $(e.currentTarget).find('input[name="tag_id"]').val(tag_id);
    });
});
