<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

// Upload file
function upload_file($file,$path,$userfile=false) {

	$options = get_option( 'upzfile_options' );
	$allowed_fileextensions = $options['allowed_fileextensions'];

	if(strpos($allowed_fileextensions,pathinfo($file['name'], PATHINFO_EXTENSION))) {
		if($userfile==false) {
			if($path!=0) {
				$path = UPZFILES_UPLOADURL.get_folder_pathbyid($path);
			} else {
				return false;
			}
		} else {
			$path = UPZFILES_PRIVATEUPLOADURL."/".$path."/";
			if ( ! is_dir($path)) {
				mkdir($path);
			}
		}

		// Checking if file already exist, if exist, add _1 or _2 or _3... and so on
		$actual_name = pathinfo($path.$file['name'],PATHINFO_FILENAME);
		$original_name = $actual_name;
		$extension = pathinfo($path.$file['name'], PATHINFO_EXTENSION);
		$name = $actual_name.".".$extension;

		$i = 1;
		while(file_exists($path.$actual_name.".".$extension)) {           
			$actual_name = (string)$original_name."_".$i;
			$name = $actual_name.".".$extension;
			$i++;
		}
		if (move_uploaded_file($file['tmp_name'],$path.$name)) {
				return $name;
		}
		else {
				return false;
		}
	} else {
		upzfilemanager_displayerror("Filetype not allowed.");
		return false;
	}
}

function delete_file($path) {
	if(unlink($path)) {
		return true;
	} else {
		return false;
	}
}

function get_filepath_byid($id,$pathtype="full") {
	global $wpdb;
	$dbfile = DB_FILE;
	$dbfolder = DB_FOLDER;
	$path = $wpdb->get_row($wpdb->prepare(
		"
		SELECT folder_id,file_name
		FROM $dbfile dbfile, $dbfolder dbfolder
		WHERE file_id = %d AND
		dbfile.file_folder = dbfolder.folder_id
		"
	,$id));
	if($pathtype=="full") {
		$file = UPZFILES_UPLOADURL.get_folder_pathbyid($path->folder_id)."/".$path->file_name;
	} else {
		$file = UPZFILES_UPLOADURL.get_folder_pathbyid($path->folder_id);
	}
	return $file;
}

function get_filevisiblename_byid($id) {
	global $wpdb;
	$dbfile = DB_FILE;
	$visiblename = $wpdb->get_var($wpdb->prepare(
		"
		SELECT file_visiblename
		FROM $dbfile
		WHERE file_id = %d
		"
	,$id));
	return $visiblename;
}


function get_folderid_byfileid($id) {
	global $wpdb;
	$dbfile = DB_FILE;
	$dbfolder = DB_FOLDER;
	$folderid = $wpdb->get_var($wpdb->prepare(
		"
		SELECT folder_id
		FROM $dbfile dbfile, $dbfolder dbfolder
		WHERE file_id = %d AND
		dbfile.file_folder = dbfolder.folder_id
		"
	,$id));
	return $folderid;
}

// USER FILES FUNCTIONS
function get_userfilepath_byid($id,$pathtype="full") {
	global $wpdb;
	$dbfile = DB_USERFILE;
	$path = $wpdb->get_row($wpdb->prepare(
		"
		SELECT file_name, file_folder
		FROM $dbfile
		WHERE file_id = %d
		"
	,$id));
	if($pathtype=="full") {
		$file = UPZFILES_PRIVATEUPLOADURL."/".$path->file_folder."/".$path->file_name;
	} else {
		$file = UPZFILES_PRIVATEUPLOADURL."/".$path->file_folder;
	}

	return $file;
}

function get_userfilevisiblename_byid($id) {
	global $wpdb;
	$dbfile = DB_USERFILE;
	$visiblename = $wpdb->get_var($wpdb->prepare(
		"
		SELECT file_visiblename
		FROM $dbfile
		WHERE file_id = %d
		"
	,$id));
	return $visiblename;
}

function get_fileownerid_byid($id) {
	global $wpdb;
	$dbfile = DB_USERFILE;
	$fileownerid = $wpdb->get_var($wpdb->prepare(
		"
		SELECT file_folder
		FROM $dbfile
		WHERE file_id = %d
		"
	,$id));
	return $fileownerid;
}

// Check if file visible filename already in use
function checkifvisiblenameok_files($visiblename,$directoryid,$editing) {
	global $wpdb;
	$dbfile = DB_FILE;
	if($editing==false) {
		$filename_used = $wpdb->get_var($wpdb->prepare(
							"
							SELECT COUNT(*)
							FROM $dbfile dbfile
							WHERE file_visiblename = %s AND
							file_folder = %d
							",
							$visiblename,
							$directoryid
							));
	} else {
		$filename_used = $wpdb->get_var($wpdb->prepare(
							"
							SELECT COUNT(*)
							FROM $dbfile dbfile
							WHERE file_visiblename = %s AND
							file_folder = %d AND
							file_id != %d
							",
							$visiblename,
							$directoryid,
							$editing
							));	
	}
	if($filename_used==0) { return true; }
	else { return false; }
}

?>