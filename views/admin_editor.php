<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */
?>
<?php

// ADD UPLOAD BUTTON TO PAGES
function upzfilemanager_custombutton($context) {

  $context .= '<a title="File selection" data-editor="content" class="thickbox button upzfilemanager_uploadlink" id="insert-upzfile-button" href="#TB_inline?width=480&inlineId=upzfilemanager_popup_content&width=640&height=566"><span class="wp-media-buttons-icon"></span>Files from filemanager</a>';

  return $context;
}

function upzfilemanager_custombutton_add_popup_content() {
?>
<div id="upzfilemanager_popup_content" style="display:none;">
 	<h2>Select a file</h2>
	<?php echo display_authorized_fullpanel(); ?>
	 <p>Folders in <span class="upzfiles_private">red</span> are private; Only allowed users can view them.<br />
 	Folders in <span class="upzfiles_public">green</span> are public; Everyone can view them.
 	</p>
</div>
<?php
}

?>