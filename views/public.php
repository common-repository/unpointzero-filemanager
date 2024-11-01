<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

// Single download shortcode
function upzfiles_displaysinglefile_shortcode($atts) {
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	return display_upzfiles_singlefile($id,"single");
}

// Single user download shortcode
function upzfiles_displaysingleuserfile_shortcode($atts) {
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	return display_upzfiles_singlefile($id,"singleuser");
}  

// Panel Shortcode 
function upzfiles_displaypanel_shortcode() {
	return display_authorized_fullpanel();
}

// Ajax panel
function upzfiles_displaypanel_ajaxfiles() {
	$folder_id = $_POST['folder_id'];
	echo display_authorized_folders_content($folder_id);
	die();
}
?>