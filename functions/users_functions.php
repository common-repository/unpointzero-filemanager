<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */
 
// LISTING ALL USERS RETURN ARRAY
function list_all_users($type="array") {
	$args = array('blog_id' => $GLOBALS['blog_id']);
	$users = get_users($args);
	
	if($type=="array") {
		$return = array();
	}
	
	foreach ($users as $user) {
		$user_login = $user->user_login;
		$user_id = $user->ID;
		if($type=="array") {
			$return[$user_id] = $user_login;
		}
	}
	return $return;
}

// LISTING ALL SELECTED USERS RETURN ARRAY
function list_selected_users($users_array) {
	$return = array();
	$users_array = unserialize($users_array);
	$args = array('blog_id' => $GLOBALS['blog_id'],'include' => $users_array );
	$users = get_users($args);
	foreach ($users as $user) {
		$user_login = $user->user_login;
		$user_id = $user->ID;
		$return[$user_id] = $user_login;
	}
	return $return;
}

// LISTING ALL EXCLUDED USERS RETURN ARRAY
function list_excluded_users($users_array) {
	$return = array();
	$users_array = unserialize($users_array);
	$args = array('blog_id' => $GLOBALS['blog_id'],'exclude' => $users_array );
	$users = get_users($args);
	foreach ($users as $user) {
		$user_login = $user->user_login;
		$user_id = $user->ID;
		$return[$user_id] = $user_login;
	}
	return $return;
}

// CREATE USER DIRECTORY ON USER CREATION
function upzfiles_userdirectory_on_usercreate($user_id) {
	require_once( 'folders_functions.php' );
	$user = get_user_by('id',$user_id);
	create_folder_fullpath(UPZFILES_PRIVATEUPLOADURL.'/'.$user->name);
}

?>