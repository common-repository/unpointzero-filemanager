<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */

function list_all_groups() {
	global $wpdb;
	$return = array();
	$dbgroup = DB_GROUP;
	$groups = $wpdb->get_results( 
		"
		SELECT * 
		FROM $dbgroup
		"
	);
	foreach($groups as $group) {
		$return[] = $group;
	}
	return (object)$return;
}

function list_nameid_groups() {
	global $wpdb;
	$return = array();
	$dbgroup = DB_GROUP;
	$groups = $wpdb->get_results( 
		"
		SELECT group_id,group_name
		FROM $dbgroup
		"
	);
	foreach($groups as $group) {
		$group_id = $group->group_id;
		$group_name = $group->group_name;
		$return[$group_id] = $group_name;
	}
	return $return;
}

// Check if group name already in use
function checkifnameok_groups($name,$editing=false) {
	global $wpdb;
	$dbgroup = DB_GROUP;
	$group_exist = $wpdb->get_var($wpdb->prepare(
							"
							SELECT COUNT(*)
							FROM $dbgroup
							WHERE group_name = %s
							",
							$name
							));
	if($group_exist==0) { return true; }
	else { return false; }
}
?>