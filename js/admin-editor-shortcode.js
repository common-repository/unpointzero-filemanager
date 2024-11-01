// Generate the single file shortcode
function generateShortcode(id) {
		tinymce.activeEditor.execCommand('mceInsertContent', false, "[upzfiles_single id="+id+"]");
		tb_remove();
	}