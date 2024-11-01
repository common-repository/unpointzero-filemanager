<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

// Create folder with full path
function create_folder_fullpath($folder_name) {
	if (!file_exists($folder_name)) {
		wp_mkdir_p($folder_name);
	}
}

// Create folder with name and folder parent ID
function create_folder($foldername,$folderparentid) {
	$folderpath = UPZFILES_UPLOADURL.get_folder_pathbyid($folderparentid);
	if (!file_exists($folderpath.$foldername)) {
		wp_mkdir_p($folderpath.$foldername);
		return true;
	}
	else {
		return false;
	}
}

// Delete folder
function delete_folder($folderpath) {
	$folderpath = UPZFILES_UPLOADURL.$folderpath;
	if(rrmdir($folderpath)) {
	return true;
	} else { return false; };
}

// Delete folder child on database
function delete_folder_and_child_db($id) {
	global $wpdb;
		$dbfolder = DB_FOLDER;
		$dbfile = DB_FILE;
		$wpdb->delete($dbfolder, array('folder_id' =>$id));
		$wpdb->delete($dbfile, array('file_folder' =>$id));
		$folders = $wpdb->get_results($wpdb->prepare(
			"
			SELECT folder_id
			FROM $dbfolder
			WHERE folder_parentid = %d
			",
			$id
		));
		if($folders!=null) {
			foreach ($folders as $folder_unique) {
				delete_folder_and_child_db($folder_unique->folder_id);
			}
		}
}

// List all folders informations
function list_folders($subdirectory=0) {
	global $wpdb;
	$return = array();
	$dbfolder = DB_FOLDER;
	$folders = $wpdb->get_results($wpdb->prepare(
		"
		SELECT *
		FROM $dbfolder
		WHERE folder_parentid = %d
		",
		$subdirectory));

	foreach($folders as $folder) {
	
		$return[] = array(	"id" => $folder->folder_id,
							"name" => $folder->folder_name,
							"named_path" => get_folder_pathbyid($folder->folder_id,"/","name"),
							"path" => get_folder_pathbyid($folder->folder_id),
							"right" => $folder->folder_right,
							"group" => $folder->folder_group
					);

		$child = check_folder_haschild($folder->folder_id);
		if(sizeof($child)!=null) {
				$return = array_merge($return,list_folders($folder->folder_id));
		}
	}

	return $return;
}

// List all folders ids and names
function list_folders_idname($subdirectory=0) {
	global $wpdb;
	$return = array();
	$dbfolder = DB_FOLDER;
	$folders = $wpdb->get_results($wpdb->prepare(
		"
		SELECT folder_id
		FROM $dbfolder
		WHERE folder_parentid = %d
		",
		$subdirectory));

		foreach($folders as $folder) {

			$return[] = array( 	"id" => $folder->folder_id,
								"path" => get_folder_pathbyid($folder->folder_id,"","name")
						);

			$child = check_folder_haschild($folder->folder_id);
			if(sizeof($child)!=null) {
					$return = array_merge($return,list_folders_idname($folder->folder_id));
			}

		}

	return $return;
}

function list_folders_idname_select($retarray) {
	$return[0] = "/";
	foreach($retarray as $ret) {
		$return[$ret['id']] = $ret['named_path'];
	}
	return $return;
}

// Check if folder has child
function check_folder_haschild($id) {
	global $wpdb;
		$return = array();
		$dbfolder = DB_FOLDER;
		$folders = $wpdb->get_results($wpdb->prepare(
			"
			SELECT folder_id
			FROM $dbfolder
			WHERE folder_parentid = %d
			",$id
		));
		foreach($folders as $folder) {
			$return[] = $folder->folder_id;
		}
	return $return;
}

// Get Folder path by ID
function get_folder_pathbyid($id,$full_path="/",$viewtype="real_name") {
	if($id==0) {
		return "/";
	} else {
		global $wpdb;
		$return = array();
		$dbfolder = DB_FOLDER;
		$folder = $wpdb->get_row($wpdb->prepare(
			"
			SELECT folder_name,folder_real_name,folder_parentid
			FROM $dbfolder
			WHERE folder_id = %d
			",$id
		));
		if($viewtype=="real_name") {
			$folder_path = $folder->folder_real_name.$full_path;
		} else {
			$folder_path = $folder->folder_name.$full_path;
		}
		if($folder->folder_parentid==0) {
			return "/".$folder_path;
		} else {
			return get_folder_pathbyid($folder->folder_parentid,"/".$folder_path,$viewtype);
		}
	}
}

// Get folder top parent id group
function get_folder_parentid_group($id) {
	global $wpdb;
	$dbfolder = DB_FOLDER;
		$folder = $wpdb->get_row( $wpdb->prepare(
			"
			SELECT folder_parentid, folder_group, folder_id
			FROM $dbfolder
			WHERE folder_id = %d
			",$id
		));
		if(($folder->folder_parentid)==0) {
			return $folder->folder_group;
		} else {
			return get_folder_parentid_group($folder->folder_parentid);
		}
}

// get folder top parent right
function get_folder_right($id) {
	global $wpdb;
	$dbfolder = DB_FOLDER;
		$folder = $wpdb->get_row( $wpdb->prepare(
			"
			SELECT folder_parentid, folder_right
			FROM $dbfolder
			WHERE folder_id = %d
			",$id
		));
		if(($folder->folder_parentid)==0) {
			return $folder->folder_right;
		} else {
			return get_folder_right($folder->folder_parentid);
		}
}

// Get number of files contained inside a folder
function get_folder_filecount($id) {
	global $wpdb;
	$dbfile = DB_FILE;
	$filecount = $wpdb->get_var( $wpdb->prepare(
			"
			SELECT COUNT(*)
			FROM $dbfile
			WHERE file_folder = %d
			",$id
		));
	return $filecount;
}

// Create folder for a user if not exist
function create_userfolder($user_id) {
	$user = get_user_by('id',$user_id);
	create_folder_fullpath(UPZFILES_PRIVATEUPLOADURL.'/'.$user->name);
}

?>