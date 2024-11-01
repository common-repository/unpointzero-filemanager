<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

$wp_path = wp_upload_dir();
define( 'UPZFILES_BASEDIR', $wp_path['basedir'] );
define( 'UPZFILES_UPLOADPATH', '/upz-uploads' );
define( 'UPZFILES_UPLOADURL', $wp_path['basedir'].'/upz-uploads' );
define( 'UPZFILES_PRIVATEUPLOADURL', $wp_path['basedir'].'/upz-uploads/private' );

$dbprefix = $wpdb->prefix;
$userfile_table = $dbprefix."upzfilemanager_userfiles";
$file_table = $dbprefix."upzfilemanager_files";
$folder_table = $dbprefix."upzfilemanager_folders";
$group_table = $dbprefix."upzfilemanager_group";
$dir = plugin_dir_url(".")."unpointzero-filemanager/";
define( 'UPZFILES_PLUGDIR', $dir );
global $wpdb;

define( 'DB_USERFILE', $userfile_table );
define( 'DB_FILE', $file_table );
define( 'DB_FOLDER', $folder_table );
define( 'DB_GROUP', $group_table );
?>