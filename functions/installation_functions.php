<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

function install_CreateDB() {
	global $wpdb;
	$dbprefix = $wpdb->prefix;
	$userfile_table = $dbprefix."upzfilemanager_userfiles";
	$file_table = $dbprefix."upzfilemanager_files";
	$folder_table = $dbprefix."upzfilemanager_folders";
	$group_table = $dbprefix."upzfilemanager_group";

	$userfile_sql = "CREATE TABLE IF NOT EXISTS $userfile_table (
	  file_id bigint(20) NOT NULL AUTO_INCREMENT,
	  file_name varchar(255) NOT NULL,
	  file_visiblename varchar(255) NOT NULL,
	  file_ext varchar(5) NOT NULL,
	  file_size bigint(20) NOT NULL,
	  file_folder int(11) NOT NULL,
	  UNIQUE KEY file_id (file_id)
	) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

	$file_sql = "CREATE TABLE IF NOT EXISTS $file_table (
	  file_id bigint(20) NOT NULL AUTO_INCREMENT,
	  file_name varchar(255) NOT NULL,
	  file_visiblename varchar(255) NOT NULL,
	  file_ext varchar(5) NOT NULL,
	  file_size bigint(20) NOT NULL,
	  file_folder int(11) NOT NULL,
	  UNIQUE KEY file_id (file_id)
	) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	$folder_sql = "CREATE TABLE IF NOT EXISTS $folder_table (
	  folder_id bigint(20) NOT NULL AUTO_INCREMENT,
	  folder_name varchar(255) NOT NULL,
	  folder_real_name varchar(255) NOT NULL,
	  folder_path varchar(255) NOT NULL,
	  folder_parentid bigint(20) NOT NULL,
	  folder_right varchar(8) NOT NULL,
	  folder_group varchar(255) NOT NULL,
	  UNIQUE KEY folder_id (folder_id)
	) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

	$group_sql = "CREATE TABLE IF NOT EXISTS $group_table (
	  group_id int(11) NOT NULL AUTO_INCREMENT,
	  group_name varchar(255) NOT NULL,
	  group_users_id text NOT NULL,
	  UNIQUE KEY group_id (group_id)
	) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $userfile_sql );
	dbDelta( $file_sql );
	dbDelta( $folder_sql );
	dbDelta( $group_sql );
}

function install_CreateFolders() {
	require_once( 'folders_functions.php' );
	create_folder_fullpath(UPZFILES_UPLOADURL);
	create_folder_fullpath(UPZFILES_PRIVATEUPLOADURL);
}

function install_CreateHtAccess() {
	$fp = fopen(UPZFILES_UPLOADURL.'/.htaccess', 'w');
	if(fwrite($fp, 'order deny,allow'."\r\n".'deny from all')){
	fclose($fp);
	return true;
	} else { return false; }
}

function install_CreateUserFolders() {
	require_once( 'users_functions.php' );
	require_once( 'folders_functions.php' );
	$users = list_all_users();
	foreach($users as $key => $value) {
		create_folder_fullpath(UPZFILES_PRIVATEUPLOADURL.'/'.$key);
	}
}

function install_defaultoptions() {
	$default_options = array(
		"allowed_fileextensions" => "jpg,jpeg,png,gif,bmp,pdf,doc,docx,ppt,pptx,pps,ppsx,odt,xls,xlsx,zip,rar,mp3,m4a,ogg,wav,mp4,m4v,mov,wmv,avi,mpg,ogv,3gp,3g2"
	);

	add_option("upzfile_options", $default_options, '', 'yes');
}

?>