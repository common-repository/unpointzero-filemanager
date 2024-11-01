<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */
 
// DISPLAY FOLDERS USER CAN VIEW
function display_authorized_folders($subdirectory=0) {
	$dbfolder = DB_FOLDER;
	$dbgroup = DB_GROUP;
	$user_id = get_current_user_id();
	$options = get_option( 'upzfile_options' );
	global $wpdb;
	$folders = $wpdb->get_results($wpdb->prepare(
		"
		SELECT folder_id,folder_name,folder_group,folder_right
		FROM $dbfolder
		WHERE folder_parentid = %d
		"
	,$subdirectory));
	if($folders!=null) {
		$folders = stripslashes_deep($folders);
		$content = '<ul class="upzfiles_folderstree">';
		foreach($folders as $folder) {

			// IF PRIVATE FOLDER
			if($folder->folder_right=="private") {
				$folder_groups = unserialize($folder->folder_group);
				if($folder_groups!=null) {
					foreach($folder_groups as $folder_group) {
						$group = $wpdb->get_row($wpdb->prepare(
						"
						SELECT group_users_id
						FROM $dbgroup
						WHERE group_id = %d
						",$folder_group));
						$groups_users_id = unserialize($group->group_users_id);
							if((($groups_users_id!=null) && in_array($user_id,$groups_users_id)) || is_super_admin()) {
								$content .= display_authorized_folders_view($folder->folder_id,$folder->folder_name);
							}
					}
				}
			}
			// IF FOLDER HAS PARENT FOLDER GET SAME RIGHTS
			elseif($folder->folder_right=="parent") {
				if(get_folder_right($folder->folder_id)=="private") {
					$top_parent_group = get_folder_parentid_group($folder->folder_id);
					$folder_groups = unserialize($top_parent_group);
					foreach($folder_groups as $folder_group) {
					$group = $wpdb->get_row($wpdb->prepare(
					"
					SELECT group_users_id
					FROM $dbgroup
					WHERE group_id = %d
					",$folder_group));
					$groups_users_id = unserialize($group->group_users_id);
						if((($groups_users_id!=null) && in_array($user_id,$groups_users_id)) || is_super_admin()) {
							$content .= display_authorized_folders_view($folder->folder_id,$folder->folder_name);
						}
					}
				} else {
					$content .= display_authorized_folders_view($folder->folder_id,$folder->folder_name);
				}
			}
			// ELSE IT'S A PUBLIC FOLDER
			else {
				$content .= display_authorized_folders_view($folder->folder_id,$folder->folder_name);
			}
		}
		$content .= "</ul>";
	}
	return $content;
}

function display_authorized_folders_view($folder_id,$folder_name) {
	$options = get_option( 'upzfile_options' );
	$filecount = get_folder_filecount($folder_id);
	$content = '<li class="upzfiles_singlefolder '.get_folder_right($folder_id).'">';
	if($filecount>0) {
		$content .= '<a rel="'.$folder_id.'" class="upzfiles_folder_link upzfiles_folder_'.$folder_id.'" href="javascript:">';
	}
	$content .= '<span class="upzfiles_singlefolder_name">'.$folder_name;
	if($options['display_filecount']) { 
		$content .= ' ('.get_folder_filecount($folder_id).')';
	}
	if($filecount>0) {
		$content .= '</a>';
	}
	$content .= '</span>';
	$content .= display_authorized_folders($folder_id);
	$content .= '</li>';
	
	return $content; 
}

// DISPLAY FOLDER CONTENT BY FOLDER ID
function display_authorized_folders_content($folderid=null,$type="global") {
	global $wpdb;

	if($type=="global") { $dbfile = DB_FILE; $displaysource="panel"; }
	elseif($type=="user") { $dbfile = DB_USERFILE; $displaysource="singleuser"; }

	$files = $wpdb->get_results($wpdb->prepare(
		"
		SELECT file_id,file_name,file_ext,file_size
		FROM $dbfile
		WHERE file_folder = %d
		"
	,$folderid));

	if($files!=null) {
	$content = '<ul class="upzfiles_folder_content upzfiles_folder_'.$folderid.'">';
	
	foreach ($files as $file) {
		$content .= '<li class="upzfiles_folder_singlefile">'.display_upzfiles_singlefile($file->file_id,$displaysource).'</li>';
	}
	
	$content .= '</ul>';
	}
	elseif($folderid==null) {
		$content = '<span class="upzfiles_nofiles">'.__("Please select a directory.","upzfiles").'</span>';
	}
	else {
		$content = '<span class="upzfiles_nofiles">'.__("There's no file in this directory!","upzfiles").'</span>';
	}
	return $content;
}

// DISPLAY FULL PANEL
function display_authorized_fullpanel() {	
	$return = '<div id="upzfiles_fullpanel">';
	$return .= '<div id="upzfiles_foldercontainer">';
	$return .= display_authorized_folders();
	$return .= '</div>';
	$return .= '<div id="upzfiles_filescontainer">';
	$return .= display_authorized_folders_content();
	$return .='</div>';
	$return .= '</div>';
	
	return $return;
} 

// DISPLAY SINGLE FILE
function display_upzfiles_singlefile($id,$displaysource="panel",$displayeditlinks=true) {
	$dbfolder = DB_FOLDER;
	$dbfile = DB_FILE;
	$dbuserfile = DB_USERFILE;
	$dbgroup = DB_GROUP;
	$user_id = get_current_user_id();
	global $wpdb;
	if($displaysource!="singleuser") {
		$file = $wpdb->get_row($wpdb->prepare(
			"
			SELECT folddb.folder_group,folddb.folder_parentid, folddb.folder_right, filedb.file_ext, filedb.file_name, filedb.file_visiblename, filedb.file_size
			FROM $dbfile filedb,$dbfolder folddb
			WHERE file_id = %d
			AND filedb.file_folder = folddb.folder_id
			"
			,$id));
		$display_link = call_display_link(array("id"=>$id,"name"=>$file->file_visiblename,"ext"=>$file->file_ext,"displayeditlinks"=>$displayeditlinks,"displaysource"=>$displaysource,"size"=>$file->file_size,"dltype"=>"global"));
			
		if($file->folder_parentid == 0) {
			if($file->folder_right=="private") {
				$folder_groups = unserialize($file->folder_group);
				foreach($folder_groups as $folder_group) {
					$group = $wpdb->get_row($wpdb->prepare(
					"
					SELECT group_users_id
					FROM $dbgroup
					WHERE group_id = %d
					",$folder_group));
							
					$groups_users_id = unserialize($group->group_users_id);
					if((($groups_users_id!=null) && in_array($user_id,$groups_users_id)) || is_super_admin()) {
						return $display_link;
					}
				}
			} else {
				return $display_link;
			}
		} else {
			return $display_link;
		}
	} else {
		if(!is_admin()) {
			$file = $wpdb->get_row($wpdb->prepare(
				"
				SELECT file_ext, file_name, file_visiblename, file_size
				FROM $dbuserfile
				WHERE file_id = %d
				AND file_folder = %d
				"
				,$id,$user_id));
		} else {
			$file = $wpdb->get_row($wpdb->prepare(
				"
				SELECT file_ext, file_name, file_visiblename, file_size, file_folder
				FROM $dbuserfile
				WHERE file_id = %d
				"
				,$id));
		}
		if($file!=null) {
			$display_link = call_display_link(array("id"=>$id,"name"=>$file->file_visiblename,"ext"=>$file->file_ext,"displayeditlinks"=>$displayeditlinks,"displaysource"=>$displaysource,"size"=>$file->file_size,"dltype"=>"user","attruser"=>$file->file_folder));
			return $display_link;
		}
	}
}

// DISPLAY DOWNLOAD LINK
function call_display_link($params) {
	$params = stripslashes_deep($params);
	$options = get_option( 'upzfile_options' );
	$requesturl = admin_url( 'admin.php?page=UPZFileManager' )."&action=";
	$display_filesize = $options['display_filesize'];
	$displaysource = $params['displaysource'];
	$display_editlinks = $params['displayeditlinks'];
	$file_id = $params['id'];
	$name = $params['name'];
	$ext = $params['ext'];
	$dltype = $params['dltype'];
	$size = formatBytes($params['size']);
	$download_url = upzfiles_download($file_id,$dltype);

	if(!file_exists(UPZFILES_PLUGFULLDIR.'images/32px/'.$ext.'.png')) {
		$ext = "_blank";
	}
	if($displaysource=="panel") { $return = '<span class="upzfiles_single_file_panel">'; $iconsize = "32px"; } else { $return = '<span class="upzfiles_single_file_line">'; $iconsize = "16px"; }
	$return .= '<a target="_blank" href="'.$download_url.'"><img class="upzfile_file_icon" src="'.UPZFILES_PLUGDIR.'images/'.$iconsize.'/'.$ext.'.png" /><span class="upzfile_file_name">'.$name.'</span>';
	if($display_filesize==true) {
	$return .= '<span class="upzfiles_filesize">'.$size.'</span>';
	}
	$return .= '</a>';
	
	if(is_super_admin(get_current_user_id())&&$display_editlinks==true) {
		$return .= '<span class="upzfiles_admin_singlefiles">';
		if(is_admin()) {
			if($displaysource=="singleuser") {
				$return.= '<a href="#" onclick="javascript:generateUserShortcode('.$file_id.');return false;">Generate shortcode</a><span class="separatorfile"> - </span>';
			} else {
				$return.= '<a href="#" onclick="javascript:generateShortcode('.$file_id.');return false;">Generate shortcode</a><span class="separatorfile"> - </span>';
			}
		}
		if($displaysource=="singleuser") {
			$return .= '<a class="editfile" href="'.$requesturl.'edituserfile_form&id='.$file_id.'">Edit file</a><span class="separatorfile"> - </span><a class="deletefile" href="'.$requesturl.'deleteuserfile_form&id='.$file_id.'">Delete file</a></span>';
		} else {
			$return .= '<a class="editfile" href="'.$requesturl.'editfile_form&id='.$file_id.'">Edit file</a><span class="separatorfile"> - </span><a class="deletefile" href="'.$requesturl.'deletefile_form&id='.$file_id.'">Delete file</a></span>';
		}
	}
	$return .= '</span>';
	return $return;
}

?>