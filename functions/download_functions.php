<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

function upzfiles_download($id,$dltype){    
    return site_url("/?upzdl=$id&dltype=$dltype"); 
}

function upzfiles_download_function() {
$fileid = intval($_GET['upzdl']);
$dltype = $_GET['dltype'];
if($fileid!=null && $dltype!=null) {
	// GET USER INFO
	$user_id = get_current_user_id();
	// CHECK IF USER HAVE RIGHTS TO DL THE FILE
	global $wpdb;
	$dbfile = DB_FILE;
	$dbuserfile = DB_USERFILE;
	$dbfolder = DB_FOLDER;
	$dbgroup = DB_GROUP;
	
	if($dltype=="global") {
		$file = $wpdb->get_row($wpdb->prepare(
			"
			SELECT folddb.folder_group, folddb.folder_parentid,folddb.folder_id
			FROM $dbfile filedb,$dbfolder folddb
			WHERE file_id = %d
			AND filedb.file_folder = folddb.folder_id
			"
			,$fileid));
			$folder_right = get_folder_right($file->folder_id);
			if($folder_right=="public") {
				upzfiles_calldownload($fileid,$dltype);
			}
			else {
					if($file->folder_parentid==0) {
						$folder_groups = unserialize($file->folder_group);	
					} else {
						$folder_groups = unserialize(get_folder_parentid_group($file->folder_parentid));
					}
					if($folder_groups) {
						foreach($folder_groups as $folder_group) {
							$group = $wpdb->get_row($wpdb->prepare(
							"
							SELECT group_users_id
							FROM $dbgroup
							WHERE group_id = %d
							",$folder_group));
						
							$groups_users_id = unserialize($group->group_users_id);
							if(($groups_users_id!=null) && in_array($user_id,$groups_users_id)) {
										upzfiles_calldownload($fileid,$dltype);
							}
						}
					}
			}
	}

	elseif($dltype=="user") {
		$file = $wpdb->get_row($wpdb->prepare(
			"
			SELECT file_ext, file_name, file_visiblename, file_size
			FROM $dbuserfile
			WHERE file_id = %d
			AND file_folder = %d
			"
			,$fileid,$user_id));
		if($file!=null) {
			upzfiles_calldownload($fileid,$dltype);
		}
	}

}
}

function upzfiles_calldownload($fileid,$dltype) {
global $wpdb;
$dbfile = DB_FILE;
$dbuserfile = DB_USERFILE;
$dbfolder = DB_FOLDER;
$upload_dir = wp_upload_dir();
$upload_dir = $upload_dir['basedir'];
if($dltype=="global") {
	$file = $wpdb->get_row($wpdb->prepare(
			"
			SELECT filedb.file_id,filedb.file_name,filedb.file_ext,filedb.file_size,folddb.folder_group,folddb.folder_id
			FROM $dbfile filedb,$dbfolder folddb
			WHERE file_id = %d
			AND filedb.file_folder = folddb.folder_id
			"
			,$fileid));
	$fileurl = $upload_dir."/upz-uploads".get_folder_pathbyid($file->folder_id).$file->file_name;
}
elseif($dltype=="user") {
	$user_id = get_current_user_id();
	$file = $wpdb->get_row($wpdb->prepare(
			"
			SELECT file_ext, file_name, file_visiblename, file_size
			FROM $dbuserfile
			WHERE file_id = %d
			AND file_folder = %d
			"
			,$fileid,$user_id));
	$fileurl = $upload_dir."/upz-uploads/private/".$user_id."/".$file->file_name;
}
$filesize = filesize($fileurl);
$mime_type = get_mime_type($file->file_ext);

// GET PLUGIN OPTIONS
$options = get_option( 'upzfile_options' );

// DESACTIVATE GZIP IF ACTIVE
if (ini_get("zlib.output_compression")) {
    ini_set("zlib.output_compression", "Off");
}
ob_clean();
set_time_limit(0);
session_write_close();

header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if($options['openpdf']==true && $file->file_ext=="pdf") {
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="'.$file->file_name.'"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.$filesize);
header('Accept-Ranges: bytes');
@readfile($fileurl);
} else {
header("Content-Type: ".$mime_type."");
header('Content-Disposition: attachment; filename="'.$file->file_name.'"');
header("Content-Length: ".$filesize);
}
// SEND FILE
readfile($fileurl);
}
?>