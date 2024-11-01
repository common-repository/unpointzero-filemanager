<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */


$actionpost = $_GET['action'];

// GROUP ACTIONS

	// ADD GROUP
	if($actionpost == "group_addnew") {
		global $wpdb;
		$groupname = $_POST['group_createnew_name'];

		if($_POST['group_createnew_users']!=null) {
			$group_users_id = serialize($_POST['group_createnew_users']);
			$dbgroup = DB_GROUP;
				if($groupname!=null) {
					if(checkifnameok_groups($groupname)) {
						$wpdb->insert( 
						$dbgroup, 
						array( 
							'group_name' => $groupname, 
							'group_users_id' => $group_users_id
						), 
						array( 
							'%s',
							'%s'
						)
						);
						upzfilemanager_displaymessage("group added successfully");
					} else {
						upzfilemanager_displayerror("A group with that name already exist");
					}
				}

			if($groupname==null) { upzfilemanager_displayerror("Please fill the group name field"); }
		} else {
			upzfilemanager_displayerror("group must have min. 1 user");
		}
	}

	// DELETE & EDIT GROUP
	elseif($actionpost == "group_management_edit") {
		$group_id = $_GET['id'];
	
		if(isset($_POST['group_edit_deletegroup_btn'])) {
			global $wpdb;
			$dbgroup = DB_GROUP;
			$wpdb->delete($dbgroup, array('group_id' => $group_id));
			upzfilemanager_displaymessage("group deleted successfully");
		}
		
		// EDIT GROUP
		if(isset($_POST['group_update_edit_users'])) {
			
			global $wpdb;
			$dbgroup = DB_GROUP;
			$groupid = $_GET['id'];
			if($_POST['group_edit_users']!=null) {
				$users = serialize($_POST['group_edit_users']);
				$wpdb->update( 
					$dbgroup, 
					array( 
						'group_users_id' => $users
					), 
					array( 'group_id' => $groupid ), 
					array( 
						'%s'
					), 
					array( '%d' ) 
				);
				upzfilemanager_displaymessage("group edited successfully");
			} else {
				upzfilemanager_displayerror("group must have min. 1 user");
			}
		}
	}
	
// FOLDER ACTIONS
	
	// ADD FOLDER
	elseif($actionpost == "folder_addnew") {
		global $wpdb;
		$wpdb->show_errors();
		$foldername = $_POST['folder_createnew_name'];
		$folderrealname = randomString(30);
		$folderparent = get_folder_pathbyid($_POST['folder_createnew_parent']);
		$folderparentid = $_POST['folder_createnew_parent'];
		if($folderparentid==0) {
		$folderright = $_POST['folder_createnew_rights'];
		$foldergroups = serialize($_POST['folder_createnew_groups']);
		} else {
		$folderright = "parent";
		$foldergroups = null;
		}
		if($folderright==null) {
			$folderright=="public";
		}
		if($folderright=="parent"||$folderright=="public"||($folderright=="private"&&$_POST['folder_createnew_groups']!=null)) {
			if($foldername!=null) {
				if(create_folder($folderrealname,$folderparentid)) {
					$dbfolder = DB_FOLDER;
					$wpdb->insert( 
					$dbfolder, 
					array( 
						'folder_name' => $foldername,
						'folder_real_name' => $folderrealname,
						'folder_parentid' => $folderparentid,
						'folder_right' => $folderright,
						'folder_group' => $foldergroups
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
					);
				}
				upzfilemanager_displaymessage("Folder created successfully");
			}
		} else {
			upzfilemanager_displayerror("Please select one or multiple group(s)");
		}
		if($foldername==null) { upzfilemanager_displayerror("Please fill the foldername field"); }
	}
	elseif($actionpost == "folder_management_edit") {

		// RENAME FOLDER
		if(isset($_POST['folder_rename'])) {
			$folderid = $_GET['id'];
			$name = $_POST['folder_name'];
				global $wpdb;
				$dbfolder = DB_FOLDER;
				$folder_id = $_GET['id'];
				$wpdb->update(
				$dbfolder, 
				array( 
					'folder_name' => $name
				), 
				array( 'folder_id' => $folderid ), 
				array( 
					'%s'
				), 
				array( '%d' ) 
			);
				upzfilemanager_displaymessage("Folder renamed successfully");	
		}

		// DELETE FOLDER
		if(isset($_POST['folder_delete'])) {
			$path = $_POST['real_path'];
			if(delete_folder($path)) {
				delete_folder_and_child_db($_GET['id']);
			}
		}
		
		// CHANGE FOLDER GROUP
		if(isset($_POST['folder_updategroup'])) {
			global $wpdb;
			$dbfolder = DB_FOLDER;
			$folderid = $_GET['id'];
			if($_POST['folder_group']!=null) {
				$group = serialize($_POST['folder_group']);
				$wpdb->update( 
					$dbfolder, 
					array( 
						'folder_group' => $group
					), 
					array( 'folder_id' => $folderid ), 
					array( 
						'%s'
					), 
					array( '%d' ) 
				);
				upzfilemanager_displaymessage("Folder group updated successfully");
			} else {
				upzfilemanager_displayerror("Please select one or multiple group(s)");
			}
		}
		
		// CHANGE FOLDER RIGHTS
		if(isset($_POST['folder_updaterights'])) {
			global $wpdb;
			$dbfolder = DB_FOLDER;
			$folderid = $_GET['id'];
			$rights = $_POST['folder_rights'];
			$wpdb->update( 
				$dbfolder, 
				array( 
					'folder_right' => $rights
				), 
				array( 'folder_id' => $folderid ), 
				array( 
					'%s'
				), 
				array( '%d' ) 
			);
			upzfilemanager_displaymessage("Folder rights updated successfully");
		}
		
	}
	
// FILES ACTIONS
	
	// UPLOAD FILE
	elseif($actionpost == "upload_file") {
		global $wpdb;
		$directory = $_POST['upload_directory'];
		$fileup = $_FILES['upload_file'];
		$filename = $fileup['name'];
		$filevisiblename = $_POST['upload_filename'];
		$fileext = pathinfo($filename, PATHINFO_EXTENSION);
		$filesize = $fileup['size'];

		if($directory!=null&&$fileup!=null&&$filevisiblename!=null) {
			if(checkifvisiblenameok_files($filevisiblename,$directory,false)) {
				if($filename_uploaded = upload_file($fileup,$directory)) {
					$dbfile = DB_FILE;
					$wpdb->insert( 
					$dbfile, 
					array( 
						'file_name' => $filename_uploaded, 
						'file_visiblename' => $filevisiblename,
						'file_ext' => $fileext,
						'file_size' => $filesize,
						'file_folder' => $directory
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%d'
					)
					);
					upzfilemanager_displaymessage("File uploaded");
				}
			} else {
				upzfilemanager_displayerror("File name (visible name) already used in this directory.");
			}
		}
		if($directory==null) { upzfilemanager_displayerror("Please select a directory"); }
		if($fileup==null) { upzfilemanager_displayerror("Please select a file"); }
		if($filevisiblename==null) { upzfilemanager_displayerror("Please fill the folder name field"); }
	}
	
	elseif($actionpost == "edit_file") {
		global $wpdb;
		$fileid = $_POST['oldfile_id'];
		$oldfile = $_POST['oldfile_fullpath'];
		$directoryid = $_POST['oldfile_pathid'];
		$newfile = $_FILES['upload_new_file'];
		$filename = $newfile['name'];
		$filevisiblename = $_POST['oldfile_name'];
		$fileext = pathinfo($filename, PATHINFO_EXTENSION);
		$filesize = $newfile['size'];
		
		$dbfile = DB_FILE;
		if($newfile!=null && $newfile['size']!=0) {
			if($filevisiblename==null) {
				$filevisiblename=get_filevisiblename_byid($fileid);
			}
			if(checkifvisiblenameok_files($filevisiblename,$directoryid,$fileid)) {
				if(upload_file($newfile,$directoryid)) {
					if(delete_file($oldfile)) {
						$wpdb->update(
						$dbfile, 
						array( 
							'file_name' => $filename,
							'file_visiblename' => $filevisiblename,
							'file_ext' => $fileext,
							'file_size' => $filesize
						), 
						array( 'file_id' => $fileid ), 
						array( 
							'%s',
							'%s',
							'%s',
							'%s'
						), 
						array( '%d' ) 
						);
					}
				}
			} else {
				upzfilemanager_displayerror("File name (visible name) already used in this directory.");
			}

			upzfilemanager_displaymessage("File edited");
		} elseif($newfile['size']==0&&$filevisiblename!=null) {
			if(checkifvisiblenameok_files($filevisiblename,$directoryid,$fileid)) {
				$wpdb->update(
					$dbfile, 
					array( 
						'file_visiblename' => $filevisiblename,
					), 
					array( 'file_id' => $fileid ), 
					array( 
						'%s'
					), 
					array( '%d' ) 
					);
				upzfilemanager_displaymessage("File edited");
			} else {
				upzfilemanager_displayerror("File name (visible name) already used in this directory.");
			}
		} else {
			upzfilemanager_displayerror("An error occured. Please check the fields");
		}
	}
	
	elseif($actionpost == "delete_file") {
		global $wpdb;
		$fileid = $_POST['oldfile_id'];
		$oldfile = $_POST['oldfile_fullpath'];
		$dbfile = DB_FILE;
		if(delete_file($oldfile)) {
			$wpdb->delete($dbfile, array('file_id' => $fileid));
		}
		upzfilemanager_displaymessage("File deleted");
	}

// USER FILES ACTIONS
elseif($actionpost == "upload_user_file") {
	global $wpdb;
	$user = $_POST['upload_user'];
	$fileup = $_FILES['upload_file'];
	$filename = $fileup['name'];
	$filevisiblename = $_POST['upload_filename'];
	$fileext = pathinfo($filename, PATHINFO_EXTENSION);
	$filesize = $fileup['size'];
	if($user!=null&&$fileup!=null&&$filevisiblename!=null) {
		if(upload_file($fileup,$user,true)) {
			$dbuserfile = DB_USERFILE;
			$wpdb->insert( 
			$dbuserfile, 
			array( 
				'file_name' => $filename, 
				'file_visiblename' => $filevisiblename,
				'file_ext' => $fileext,
				'file_size' => $filesize,
				'file_folder' => $user
			), 
			array( 
				'%s',
				'%s',
				'%s',
				'%s',
				'%d'
			)
			);
		}
		upzfilemanager_displaymessage("File uploaded");
	}
	if($user==null) { upzfilemanager_displayerror("Please select a user"); }
	if($fileup==null) { upzfilemanager_displayerror("Please select a file"); }
	if($filevisiblename==null) { upzfilemanager_displayerror("Please fill the folder name field"); }
}

elseif($actionpost == "edit_userfile") {
	global $wpdb;
	$fileid = $_POST['oldfile_id'];
	$oldfile = $_POST['oldfile_fullpath'];
	$directoryid = $_POST['oldfile_pathid'];
	$newfile = $_FILES['upload_new_file'];
	$filename = $newfile['name'];
	$filevisiblename = $_POST['oldfile_name'];
	$fileext = pathinfo($filename, PATHINFO_EXTENSION);
	$filesize = $newfile['size'];
	$dbfile = DB_USERFILE;
	if($newfile!=null && $newfile['size']!=0) {
		if($filevisiblename==null) {
			$filevisiblename=get_userfilevisiblename_byid($fileid);
		}
		if(upload_file($newfile,$directoryid,true)) {
			if(delete_file($oldfile)) {
				$wpdb->update(
				$dbfile, 
				array( 
					'file_name' => $filename,
					'file_visiblename' => $filevisiblename,
					'file_ext' => $fileext,
					'file_size' => $filesize
				), 
				array( 'file_id' => $fileid ), 
				array( 
					'%s',
					'%s',
					'%s',
					'%s'
				), 
				array( '%d' ) 
				);
			}
		}
		upzfilemanager_displaymessage("File edited");
	} elseif($newfile['size']==0&&$filevisiblename!=null) {
			$wpdb->update(
				$dbfile, 
				array( 
					'file_visiblename' => $filevisiblename,
				), 
				array( 'file_id' => $fileid ), 
				array( 
					'%s'
				), 
				array( '%d' ) 
				);
		upzfilemanager_displaymessage("File edited");
	} else {
		upzfilemanager_displayerror("An error occured. Please check the fields");
	}
}
	
	elseif($actionpost == "delete_userfile") {
		global $wpdb;
		$fileid = $_POST['oldfile_id'];
		$oldfile = $_POST['oldfile_fullpath'];
		$dbfile = DB_USERFILE;
		if(delete_file($oldfile)) {
			$wpdb->delete($dbfile, array('file_id' => $fileid));
		}
		upzfilemanager_displaymessage("User file deleted");
}