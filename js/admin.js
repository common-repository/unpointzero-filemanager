jQuery(document).ready(function($){
	// TABS ADMIN
	var tabContainers = $('div.wrap div.tabs');

	var tabsass = {
		upload_user_file : "#tab-users",
		uploadfile_selectuser : "#tab-users",
		edituserfile_form : "#tab-users",
		deleteuserfile_form : "#tab-users",
		group_management_edit : "#tab-groups",
		group_addnew : "#tab-groups",
		folder_management_edit : "#tab-folders",
		folder_addnew : "#tab-folders",
	}

	$('div.navi ul.tabNavigation a').click(function () {
		tabContainers.hide();
		tabContainers.filter(this.hash).show();
		$('div.tabs ul.tabNavigation a').removeClass('selected');
		$(this).addClass('selected');
		return false;
	});

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

	$('#upzfiles_defaultext').click(function() {
		$(this).prev().append("jpg,jpeg,png,gif,bmp,pdf,doc,docx,ppt,pptx,pps,ppsx,odt,xls,xlsx,zip,rar,mp3,m4a,ogg,wav,mp4,m4v,mov,wmv,avi,mpg,ogv,3gp,3g2");
	});
	
	//TAB FOLDER
	$('#folder_createnew_parent option:eq(1)').prop('selected', true);

	$('#tab-folders #folder_createnew_parent').on('change', function() {
	 	if(this.value!=0) {
	  		$(".folder_createnew_parent_hide").hide();
	  	} else {
	  		$(".folder_createnew_parent_hide").show();
	  	}
	});

	//TAB UPLOAD
	$('#tab-files #upload_directory option:eq(0)').prop('selected', true);
	$("#tab-files #upzfile_submit_sendfile").hide();

	$('#tab-files #upload_directory').on('change', function() {
	 	if(this.value==0 ||this.selectedIndex==0) {
	  		$("#tab-files #upzfile_submit_sendfile").hide();
	  	} else {
	  		$("#tab-files #upzfile_submit_sendfile").show();
	  	}
	});

	//TAB USERS
	$('#tab-users #upload_user option:eq(0)').prop('selected', true);
	$("#tab-users #upzfile_submit_sendfile").hide();

	$('#tab-users #upload_user').on('change', function() {
	 	if(this.value==0 ||this.selectedIndex==0) {
	  		$("#tab-users #upzfile_submit_sendfile").hide();
	  	} else {
	  		$("#tab-users #upzfile_submit_sendfile").show();
	  	}
	});

	//REDIRECT TAB TO USED TAB
	if(GetURLParameter('action')!=null) {
		if(tabsass[GetURLParameter('action')]!=null) {
			tabContainers.hide().filter(tabsass[GetURLParameter('action')]).show();
		} else {
			tabContainers.hide().filter(':first').show();
		}
	} else {
		tabContainers.hide().filter(':first').show();
	}

});

// Get URL parameter
function GetURLParameter(sParam) {
	var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
	    {
	        var sParameterName = sURLVariables[i].split('=');
	        if (sParameterName[0] == sParam) 
	        {
	            return sParameterName[1];
	        }
	    }
}