jQuery(document).ready(function($){

		// Files list scrollbar
		$("#upzfiles_filescontainer").css("height",$("#upzfiles_foldercontainer").css("height"));

		$('.upzfiles_folder_link').click(function() {
			$.post(upzfiles_getfoldercontent_ajax.ajaxurl, {
			action: 'upzfiles_getdirectoryfiles_ajax',
			folder_id: $(this).prop('rel')
			}, function(data) {
				$('#upzfiles_filescontainer').html(data);
				$("#upzfiles_filescontainer").mCustomScrollbar();
			});
		});
		
});

function generateShortcode(id) {
		alert("Shortcode is : [upzfiles_single id="+id+"]");
	}
